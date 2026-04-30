<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Models\ModuleConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function index()
    {
        $modules = ModuleConfig::where('is_active', true)
                               ->orderBy('sort_order')
                               ->get();

        $sessions = ChatSession::where('user_id', Auth::id())
                               ->where('status', 'active')
                               ->orderByDesc('last_message_at')
                               ->take(10)
                               ->get();

        return view('chat.index', compact('modules', 'sessions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'module'     => 'required|string',
            'message'    => 'required|string|max:2000',
            'session_id' => 'nullable|exists:chat_sessions,id',
        ]);

        $user   = Auth::user();
        $module = ModuleConfig::findBySlug($request->module);

        if (!$module) {
            return response()->json(['error' => 'Módulo no encontrado'], 404);
        }

        if (!$user->hasCredits()) {
            return response()->json(['error' => 'No tienes créditos disponibles esta semana'], 429);
        }

        // Obtener o crear sesión
        if ($request->session_id) {
            $session = ChatSession::where('id', $request->session_id)
                                ->where('user_id', $user->id)
                                ->firstOrFail();
        } else {
            $session = ChatSession::create([
                'user_id'               => $user->id,
                'module'                => $request->module,
                'title'                 => substr($request->message, 0, 60),
                'system_prompt_version' => $module->version,
                'injected_context'      => [
                    'institution'   => $user->institution?->name ?? 'IE de Huancavelica',
                    'ugel'          => $user->ugel?->name ?? 'UGEL Huancavelica',
                    'local_context' => $user->institution?->local_context ?? '',
                ],
                'status' => 'active',
            ]);
        }

        // Guardar mensaje del usuario
        ChatMessage::create([
            'session_id' => $session->id,
            'user_id'    => $user->id,
            'role'       => 'user',
            'content'    => $request->message,
        ]);

        // Construir historial para Claude — máximo últimos 6 mensajes
        $allMessages = ChatMessage::where('session_id', $session->id)
                                ->orderBy('created_at')
                                ->get()
                                ->map(fn($m) => [
                                    'role'    => $m->role,
                                    'content' => mb_substr($m->content, 0, 2000),
                                ])
                                ->toArray();

        // Tomar solo los últimos 6
        $history = array_slice($allMessages, -6);

        // Claude requiere que el historial empiece siempre con 'user'
        while (!empty($history) && $history[0]['role'] !== 'user') {
            array_shift($history);
        }

        // Si quedó vacío agregar el mensaje actual
        if (empty($history)) {
            $history = [['role' => 'user', 'content' => $request->message]];
        }

        // Claude requiere que el historial empiece siempre con 'user'
        while (!empty($history) && $history[0]['role'] !== 'user') {
            array_shift($history);
        }

        // Si el historial quedó vacío agregar el mensaje actual
        if (empty($history)) {
            $history = [['role' => 'user', 'content' => $request->message]];
        }

        // Construir system prompt
        $context = [
            'institution_context' => $session->injected_context['institution'] ?? 'IE de Huancavelica',
            'area'             => $request->area ?? '',
            'grade'            => $request->grade ?? '',
            'bimester'         => $request->bimester ?? '',
            'weeks'            => $request->weeks ?? '9',
            'situation'        => $request->situation ?? '',
            'context_tags'     => $request->context_tags ?? '',
            'duration'         => $request->duration ?? '90',
            'methodology'      => $request->methodology ?? '',
            'topic'            => $request->topic ?? '',
            'level'            => $request->level ?? '',
            'grades'           => $request->grades ?? '',
            'areas'            => $request->areas ?? '',
            'duration_weeks'   => $request->duration_weeks ?? '4',
            'problem_context'  => $request->problem_context ?? '',
            'competency'       => $request->competency ?? '',
            'evaluated_product'=> $request->evaluated_product ?? '',
        ];

        $systemPrompt = $module->buildSystemPrompt($context);
        $sessionId    = $session->id;
        $userId       = $user->id;
        $modelName    = $module->model;
        $maxTokens    = $module->max_tokens;
        $apiKey       = config('services.anthropic.key');

        $payload = json_encode([
            'model'      => $modelName,
            'max_tokens' => $maxTokens,
            'stream'     => true,
            'system'     => $systemPrompt,
            'messages'   => $history,
        ]);

        return response()->stream(function () use (
            $apiKey, $payload, $sessionId, $userId, $modelName, $user, $session
        ) {
            $fullReply  = '';
            $tokensIn   = 0;
            $tokensOut  = 0;
            $start      = microtime(true);

            $ch = curl_init('https://api.anthropic.com/v1/messages');
            curl_setopt_array($ch, [
                CURLOPT_POST           => true,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'x-api-key: ' . $apiKey,
                    'anthropic-version: 2023-06-01',
                ],
                CURLOPT_POSTFIELDS     => $payload,
                CURLOPT_WRITEFUNCTION  => function ($ch, $data) use (&$fullReply) {
                    $lines = explode("\n", $data);
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (str_starts_with($line, 'data: ')) {
                            $json = substr($line, 6);
                            if ($json === '[DONE]') continue;
                            $event = json_decode($json, true);
                            if (!$event) continue;
                            if (($event['type'] ?? '') === 'content_block_delta') {
                                $chunk = $event['delta']['text'] ?? '';
                                if ($chunk !== '') {
                                    $fullReply .= $chunk;
                                    echo 'data: ' . json_encode(['chunk' => $chunk]) . "\n\n";
                                    if (ob_get_level()) ob_flush();
                                    flush();
                                }
                            }
                            if (($event['type'] ?? '') === 'message_delta') {
                                $tokensOut = $event['usage']['output_tokens'] ?? 0;
                            }
                            if (($event['type'] ?? '') === 'message_start') {
                                $tokensIn = $event['message']['usage']['input_tokens'] ?? 0;
                            }
                        }
                    }
                    return strlen($data);
                },
                CURLOPT_TIMEOUT        => 120,
            ]);

            curl_exec($ch);
            curl_close($ch);

            $latency = max(0, (int) round((microtime(true) - $start) * 1000));

            // Guardar respuesta
            ChatMessage::create([
                'session_id'    => $sessionId,
                'user_id'       => $userId,
                'role'          => 'assistant',
                'content'       => $fullReply,
                'tokens_input'  => $tokensIn,
                'tokens_output' => $tokensOut,
                'latency_ms'    => $latency,
                'model_used'    => $modelName,
            ]);

            $user->increment('weekly_credits_used');
            $session->increment('total_tokens_used', $tokensIn + $tokensOut);
            $session->increment('messages_count');
            $session->update(['last_message_at' => now()]);

            $remaining = $user->remainingCredits();
            echo 'data: ' . json_encode([
                'done'             => true,
                'session_id'       => $sessionId,
                'credits_remaining'=> $remaining,
            ]) . "\n\n";
            if (ob_get_level()) ob_flush();
            flush();

        }, 200, [
            'Content-Type'  => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}