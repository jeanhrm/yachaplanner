<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Models\ModuleConfig;
use App\Models\ProjectIdea;
use App\Support\PlanningContext;
use App\Support\WhatsApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $modules = ModuleConfig::where('is_active', true)
                               ->orderBy('sort_order')
                               ->get();

        $sessions = ChatSession::where('user_id', $user->id)
                       ->where('status', 'active')
                       ->whereNotIn('title', [ChatSession::DEFAULT_TITLE, '__sugerencia_interna__'])
                       ->orderByDesc('updated_at')
                       ->take(6)
                       ->get();

        // Respuestas de la última vez + datos del perfil, para no volver a preguntar
        $defaults = $user->preferences['planificador'] ?? [];
        if (empty($defaults['nivel']) && in_array($user->nivel, ['Primaria', 'Secundaria'])) {
            $defaults['nivel'] = $user->nivel;
        }
        if (empty($defaults['area']) && $user->area_docente && $user->area_docente !== 'Todas las áreas (primaria)') {
            $defaults['area'] = $user->area_docente;
        }

        // "Usar en planificador" desde el banco de ideas
        $idea = null;
        if ($request->filled('idea')) {
            $found = ProjectIdea::where('id', $request->integer('idea'))->where('is_active', true)->first();
            if ($found) {
                $idea = [
                    'title'       => $found->title,
                    'tema'        => trim($found->title . '. ' . $found->description),
                    'areas'       => $found->areas ?? [],
                    'nivel'       => in_array($found->level, ['Primaria', 'Secundaria']) ? $found->level : null,
                ];
            }
        }

        return view('chat.index', compact('modules', 'sessions', 'defaults', 'idea'));
    }

    public function session(ChatSession $session)
    {
        if ((int) $session->user_id !== (int) Auth::id()) abort(403);

        $messages = ChatMessage::where('session_id', $session->id)
                    ->where('content', '!=', '__sugerencia_interna__')
                    ->orderBy('created_at')
                    ->orderBy('id')
                    ->get(['role', 'content'])
                    ->map(fn($m) => [
                        'role'    => $m->role,
                        'content' => $m->role === 'user'
                            ? ChatSession::stripContextPrefix($m->content)
                            : $m->content,
                    ])
                    ->values();

        return response()->json([
            'module'   => $session->module,
            'title'    => $session->display_title,
            'messages' => $messages,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'module'     => 'required|string',
            'message'    => 'required|string|max:4000',
            'session_id' => 'nullable|exists:chat_sessions,id',
            'context'    => 'nullable|string|max:1500',
            'planning'   => 'nullable|array',
        ]);

        $user   = Auth::user();
        $module = ModuleConfig::findBySlug($request->module);

        if (!$module) {
            return response()->json(['error' => 'Módulo no encontrado'], 404);
        }

        if (!$user->hasCredits()) {
            return response()->json([
                'error'       => 'no_credits',
                'message'     => 'Te quedaste sin créditos',
                'whatsapp'    => WhatsApp::creditsLink($user),
                'credits_url' => route('credits.index'),
            ], 429);
        }

        // Declarar ANTES de todo
        $esInterna = $request->boolean('_es_sugerencia');
        $texto     = trim($request->message);
        $contexto  = trim((string) $request->input('context', ''));
        $planning  = $request->filled('planning') ? PlanningContext::sanitize($request->input('planning')) : null;

        // Obtener o crear sesión
        if ($request->session_id) {
            $session = ChatSession::where('id', $request->session_id)
                                  ->where('user_id', $user->id)
                                  ->firstOrFail();

            // Sesión iniciada con la sugerencia interna: toma el título del primer mensaje real
            if (!$esInterna && $session->title === ChatSession::DEFAULT_TITLE) {
                $session->update(['title' => ChatSession::makeTitle($texto)]);
            }
        } else {
            $session = ChatSession::create([
                'user_id'               => $user->id,
                'module'                => $request->module,
                'title'                 => match (true) {
                    $esInterna        => ChatSession::DEFAULT_TITLE,
                    $planning !== null => ChatSession::makeTitle(PlanningContext::title($module->name, $planning)),
                    default           => ChatSession::makeTitle($texto),
                },
                'system_prompt_version' => $module->version,
                'injected_context'      => array_filter([
                    'institution'   => $user->institution?->name ?? $user->institucion ?? 'IE de ' . ($user->region ?? 'Perú'),
                    'ugel'          => $user->ugel ?? 'UGEL no especificada',
                    'local_context' => $user->region ?? 'Perú',
                    'planning'      => $planning,
                ]),
                'status' => 'active',
            ]);
        }

        // Los ajustes posteriores reutilizan lo que el docente respondió en el asistente
        $planning ??= $session->injected_context['planning'] ?? null;

        // Recordar sus condiciones (recursos, estudiantes, comunidad) para la próxima vez
        if ($planning && $request->filled('planning')) {
            $prefs = $user->preferences ?? [];
            $prefs['planificador'] = PlanningContext::remembered($planning);
            $user->update(['preferences' => $prefs]);
        }

        // Guardar mensaje del usuario
        $userMessage = ChatMessage::create([
            'session_id' => $session->id,
            'user_id'    => $user->id,
            'role'       => 'user',
            'content'    => $esInterna ? '__sugerencia_interna__' : $texto,
        ]);

        // Lo que Claude recibe como mensaje actual (el contexto no se guarda ni se muestra)
        $mensajeActual = $esInterna
            ? $request->message
            : trim($contexto . ' ' . $texto);

        // Construir historial para Claude — máximo últimos 6 mensajes
        $allMessages = ChatMessage::where('session_id', $session->id)
                          ->orderBy('created_at')
                          ->orderBy('id')
                          ->get()
                          ->map(fn($m) => [
                              'role'    => $m->role,
                              'content' => match (true) {
                                  (int) $m->id === (int) $userMessage->id           => $mensajeActual,
                                  $m->content === '__sugerencia_interna__' => 'Sugiéreme un punto de partida para este documento.',
                                  // El documento previo debe llegar completo para poder ajustarlo
                                  $m->role === 'assistant'                 => mb_substr($m->content, 0, 16000),
                                  default                                  => mb_substr($m->content, 0, 4000),
                              },
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
            $history = [['role' => 'user', 'content' => $mensajeActual]];
        }

        // Construir system prompt
        $institution = ($session->injected_context['institution'] ?? 'IE de ' . ($user->region ?? 'Perú'))
            . ' — Región: ' . ($user->region ?? 'Perú')
            . (($session->injected_context['ugel'] ?? null) ? ' — ' . $session->injected_context['ugel'] : '');

        if ($planning) {
            $systemPrompt = $module->buildSystemPrompt(PlanningContext::placeholders($planning, $institution))
                          . PlanningContext::conditionsBlock($planning);
        } else {
            // Sesiones antiguas (antes del asistente paso a paso)
            $systemPrompt = $module->buildSystemPrompt([
                'institution_context' => $institution,
                'weeks'               => '9',
                'duration'            => '90',
                'duration_weeks'      => '4',
            ]);
            // Placeholders sin dato: que Claude los tome del mensaje del docente
            $systemPrompt = preg_replace('/\{\{\w+\}\}/', '(ver mensaje del docente)', $systemPrompt);
        }
        $sessionId    = $session->id;
        $userId       = $user->id;
        // El modelo de config (ANTHROPIC_MODEL) manda: así un modelo retirado no rompe la app
        $modelName    = config('services.anthropic.model') ?: $module->model;
        // Los modelos actuales piensan antes de escribir y eso también consume max_tokens
        $maxTokens    = max((int) $module->max_tokens, 16000);
        $apiKey       = config('services.anthropic.key');

        $body = [
            'model'      => $modelName,
            'max_tokens' => $maxTokens,
            'stream'     => true,
            'system'     => $systemPrompt,
            'messages'   => $history,
            // Menos razonamiento interno = el documento empieza y termina antes (la calidad se mantiene para esta tarea)
            'output_config' => ['effort' => 'medium'],
        ];
        $headers = [
            'Content-Type: application/json',
            'x-api-key: ' . $apiKey,
            'anthropic-version: 2023-06-01',
        ];
        // Si el filtro de seguridad rechaza un pedido legítimo, la API reintenta con otro modelo
        if (in_array($modelName, ['claude-sonnet-5-5', 'claude-opus-5-5', 'claude-opus-5', 'claude-fable-5-1'], true)) {
            $body['fallbacks'] = 'default';
            $headers[]         = 'anthropic-beta: server-side-fallback-2026-07-01';
        }
        $payload = json_encode($body);

        return response()->stream(function () use (
            $apiKey, $payload, $headers, $sessionId, $userId, $modelName, $user, $session, $esInterna
        ) {
            // Una generación con razonamiento supera los 30 s por defecto de PHP;
            // si el proceso muere a mitad, no se guarda la respuesta ni se puede exportar
            @set_time_limit(0);
            ignore_user_abort(true);

            // El navegador recibe el id de sesión de inmediato, no solo al final
            echo 'data: ' . json_encode(['session_id' => $sessionId]) . "\n\n";
            if (ob_get_level()) ob_flush();
            flush();

            $fullReply = '';
            $tokensIn  = 0;
            $tokensOut = 0;
            $buffer    = '';   // curl puede partir un evento SSE entre dos llamadas
            $rawError  = '';   // cuerpo de error de la API (no es SSE)
            $stopReason = null;
            $start     = microtime(true);
            $lastSent  = microtime(true);

            $ch = curl_init('https://api.anthropic.com/v1/messages');
            curl_setopt_array($ch, [
                CURLOPT_POST          => true,
                CURLOPT_HTTPHEADER    => $headers,
                CURLOPT_POSTFIELDS    => $payload,
                // Mientras el modelo razona no llega texto; un comentario SSE cada 10 s
                // evita que el proxy de Railway cierre la conexión por inactividad
                CURLOPT_NOPROGRESS       => false,
                CURLOPT_XFERINFOFUNCTION => function () use (&$lastSent) {
                    if (microtime(true) - $lastSent >= 10) {
                        echo ": ping\n\n";
                        if (ob_get_level()) ob_flush();
                        flush();
                        $lastSent = microtime(true);
                    }
                    return 0;
                },
                CURLOPT_WRITEFUNCTION => function ($ch, $data) use (&$fullReply, &$tokensIn, &$tokensOut, &$buffer, &$rawError, &$stopReason, &$lastSent) {
                    $buffer .= $data;
                    $lines   = explode("\n", $buffer);
                    $buffer  = array_pop($lines);   // la última línea puede estar incompleta
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (!str_starts_with($line, 'data: ')) {
                            if ($line !== '' && !str_starts_with($line, 'event:') && strlen($rawError) < 1000) {
                                $rawError .= $line;
                            }
                            continue;
                        }
                        $json = substr($line, 6);
                        if ($json === '[DONE]') continue;
                        $event = json_decode($json, true);
                        if (!$event) continue;

                        // Errores a mitad del stream (sobrecarga, etc.) llegan como evento SSE
                        if (($event['type'] ?? '') === 'error' && strlen($rawError) < 1000) {
                            $rawError .= $json;
                        }

                        if (($event['type'] ?? '') === 'message_start') {
                            $tokensIn = $event['message']['usage']['input_tokens'] ?? 0;
                        }
                        if (($event['type'] ?? '') === 'content_block_delta') {
                            $chunk = $event['delta']['text'] ?? '';
                            if ($chunk !== '') {
                                $fullReply .= $chunk;
                                echo 'data: ' . json_encode(['chunk' => $chunk]) . "\n\n";
                                if (ob_get_level()) ob_flush();
                                flush();
                                $lastSent = microtime(true);
                            }
                        }
                        if (($event['type'] ?? '') === 'message_delta') {
                            $tokensOut  = $event['usage']['output_tokens'] ?? 0;
                            $stopReason = $event['delta']['stop_reason'] ?? $stopReason;
                        }
                    }
                    return strlen($data);
                },
                CURLOPT_TIMEOUT => 600,
            ]);

            curl_exec($ch);
            $httpCode  = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            $rawError .= trim($buffer);
            $latency   = max(0, (int) round((microtime(true) - $start) * 1000));

            // Falló la API: no se guarda respuesta vacía ni se descuenta crédito
            if (trim($fullReply) === '') {
                Log::warning('Claude API sin respuesta', [
                    'session_id' => $sessionId,
                    'http_code'  => $httpCode,
                    'curl_error' => $curlError,
                    'body'       => mb_substr($rawError, 0, 1000),
                ]);
                echo 'data: ' . json_encode([
                    'error'             => 'api_error',
                    'session_id'        => $sessionId,
                    'credits_remaining' => $user->remainingCredits(),
                ]) . "\n\n";
                if (ob_get_level()) ob_flush();
                flush();
                return;
            }

            // Guardar respuesta del asistente
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

            // Solo descontar crédito si NO es sugerencia interna
            if (!$esInterna) {
                $user->increment('weekly_credits_used');
            }

            $session->increment('total_tokens_used', $tokensIn + $tokensOut);
            $session->increment('messages_count');
            $session->update(['last_message_at' => now()]);

            $remaining = $user->remainingCredits();

            if ($stopReason === 'max_tokens') {
                Log::info('Respuesta cortada por max_tokens', ['session_id' => $sessionId, 'tokens_out' => $tokensOut]);
            }

            echo 'data: ' . json_encode([
                'done'              => true,
                'truncated'         => $stopReason === 'max_tokens',
                'session_id'        => $sessionId,
                'credits_remaining' => $remaining,
            ]) . "\n\n";

            if (ob_get_level()) ob_flush();
            flush();

        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}