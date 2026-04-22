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
            'module' => 'required|string',
            'message'=> 'required|string|max:2000',
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
                    'institution' => $user->institution?->name ?? 'IE de Huancavelica',
                    'ugel'        => $user->ugel?->name ?? 'UGEL Huancavelica',
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

        // Construir historial para Claude
        $history = ChatMessage::where('session_id', $session->id)
                              ->orderBy('created_at')
                              ->get()
                              ->map(fn($m) => [
                                  'role'    => $m->role,
                                  'content' => $m->content,
                              ])->toArray();

        // Construir system prompt
        $context = [
            'institution_context' => $session->injected_context['institution'] ?? 'IE de Huancavelica',
            'area'        => $request->area ?? '',
            'grade'       => $request->grade ?? '',
            'bimester'    => $request->bimester ?? '',
            'weeks'       => $request->weeks ?? '9',
            'situation'   => $request->situation ?? '',
            'context_tags'=> $request->context_tags ?? '',
            'duration'    => $request->duration ?? '90',
            'methodology' => $request->methodology ?? '',
            'topic'       => $request->topic ?? '',
            'level'       => $request->level ?? '',
            'grades'      => $request->grades ?? '',
            'areas'       => $request->areas ?? '',
            'duration_weeks' => $request->duration_weeks ?? '4',
            'problem_context'=> $request->problem_context ?? '',
            'competency'  => $request->competency ?? '',
            'evaluated_product' => $request->evaluated_product ?? '',
        ];

        $systemPrompt = $module->buildSystemPrompt($context);

        // Llamar a Claude API
        $start = now();
        $response = Http::withHeaders([
            'x-api-key'         => config('services.anthropic.key'),
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model'      => $module->model,
            'max_tokens' => $module->max_tokens,
            'system'     => $systemPrompt,
            'messages'   => $history,
        ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'Error al conectar con la IA',
                'status' => $response->status(),
                'body' => $response->body(),
            ], 500);
        }

        $data       = $response->json();
        $reply      = $data['content'][0]['text'] ?? '';
        $tokensIn   = $data['usage']['input_tokens'] ?? 0;
        $tokensOut  = $data['usage']['output_tokens'] ?? 0;
        $latency = max(0, (int) round(now()->diffInMilliseconds($start)));

        // Guardar respuesta del asistente
        ChatMessage::create([
            'session_id'    => $session->id,
            'user_id'       => $user->id,
            'role'          => 'assistant',
            'content'       => $reply,
            'tokens_input'  => $tokensIn,
            'tokens_output' => $tokensOut,
            'latency_ms'    => $latency,
            'model_used'    => $module->model,
        ]);

        // Descontar crédito
        $user->increment('weekly_credits_used');

        // Actualizar sesión
        $session->increment('total_tokens_used', $tokensIn + $tokensOut);
        $session->increment('messages_count');
        $session->update(['last_message_at' => now()]);

        return response()->json([
            'reply'      => $reply,
            'session_id' => $session->id,
            'credits_remaining' => $user->remainingCredits(),
        ]);
    }
}