<x-app-layout>
    <x-slot name="header" style="padding:0;">
        <div style="background:linear-gradient(135deg,#059669 0%,#047857 100%);padding:20px 24px;">
            <div style="max-width:800px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                <div>
                    <h1 style="font-size:22px;font-weight:800;color:#fff;margin-bottom:4px;">
                        🌱 Mi Ruta STEAM
                    </h1>
                    <p style="font-size:13px;color:rgba(255,255,255,0.8);">
                        Proyectos completados: <strong>{{ $user->projects_completed ?? 0 }}</strong>
                    </p>
                </div>
                @php $badge = $user->getCurrentBadge(); $next = $user->getNextBadge(); @endphp
                <div style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);border-radius:12px;padding:12px 20px;text-align:center;">
                    <div style="font-size:28px;">{{ $badge['emoji'] }}</div>
                    <div style="font-size:12px;font-weight:700;color:#fff;margin-top:4px;">{{ $badge['name'] }}</div>
                    @if($next['need'] > 0)
                    <div style="font-size:11px;color:rgba(255,255,255,0.7);margin-top:2px;">
                        {{ $next['need'] }} proyecto{{ $next['need'] > 1 ? 's' : '' }} para {{ $next['name'] }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <style>
        .journey-layout { max-width:700px;margin:40px auto;padding:0 24px; }

        /* Línea de tiempo */
        .timeline { position:relative;padding:0 0 40px; }
        .timeline::before {
            content:'';
            position:absolute;
            left:50%;
            top:0; bottom:0;
            width:4px;
            background:linear-gradient(to bottom,#059669,#34d399,#fbbf24,#f87171,#7c3aed);
            transform:translateX(-50%);
            border-radius:4px;
        }

        .stage { display:flex;align-items:center;margin-bottom:48px;position:relative; }
        .stage:nth-child(odd)  { flex-direction:row; }
        .stage:nth-child(even) { flex-direction:row-reverse; }

        .stage-dot {
            width:52px;height:52px;
            border-radius:50%;
            display:flex;align-items:center;justify-content:center;
            font-size:22px;
            position:absolute;
            left:50%;
            transform:translateX(-50%);
            z-index:2;
            border:4px solid #fff;
            box-shadow:0 4px 12px rgba(0,0,0,0.15);
            flex-shrink:0;
        }

        .stage-dot.completed { background:linear-gradient(135deg,#059669,#047857); }
        .stage-dot.active    { background:linear-gradient(135deg,#0284c7,#0369a1);animation:pulse 2s infinite; }
        .stage-dot.locked    { background:#e5e7eb; filter:grayscale(1); }

        @keyframes pulse {
            0%,100% { box-shadow:0 4px 12px rgba(2,132,199,0.3); }
            50%      { box-shadow:0 4px 24px rgba(2,132,199,0.6); }
        }

        .stage-card {
            width:calc(50% - 48px);
            background:#fff;
            border-radius:16px;
            padding:20px;
            border:2px solid #e5e7eb;
            box-shadow:0 2px 12px rgba(0,0,0,0.06);
            transition:all 0.2s;
        }

        .stage-card.completed { border-color:#d1fae5; background:#f0fdf4; }
        .stage-card.active    { border-color:#bfdbfe; background:#eff6ff; }
        .stage-card.locked    { opacity:0.5; }

        .stage:nth-child(odd)  .stage-card { margin-right:auto; }
        .stage:nth-child(even) .stage-card { margin-left:auto; }

        .stage-number { font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:#9ca3af;margin-bottom:6px; }
        .stage-title  { font-size:15px;font-weight:700;color:#111827;margin-bottom:6px; }
        .stage-desc   { font-size:12px;color:#6b7280;line-height:1.5;margin-bottom:12px; }
        .stage-badge  { display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;margin-bottom:12px; }
        .badge-completed { background:#d1fae5;color:#065f46; }
        .badge-active    { background:#dbeafe;color:#1e40af; }
        .badge-locked    { background:#f3f4f6;color:#9ca3af; }

        .btn-stage {
            width:100%;padding:10px;border:none;border-radius:10px;
            font-size:13px;font-weight:600;cursor:pointer;
            font-family:inherit;transition:all 0.2s;
        }
        .btn-complete { background:#059669;color:#fff; }
        .btn-complete:hover { background:#047857;transform:translateY(-1px); }
        .btn-locked { background:#f3f4f6;color:#9ca3af;cursor:not-allowed; }

        .completed-check { font-size:18px;color:#059669; }

        /* Modal de celebración */
        .modal-overlay {
            display:none;position:fixed;inset:0;
            background:rgba(0,0,0,0.5);z-index:1000;
            align-items:center;justify-content:center;
        }
        .modal-overlay.open { display:flex; }
        .modal-box {
            background:#fff;border-radius:24px;padding:40px;
            text-align:center;max-width:400px;width:90%;
            animation:popIn 0.3s ease;
        }
        @keyframes popIn {
            from { transform:scale(0.8);opacity:0; }
            to   { transform:scale(1);opacity:1; }
        }
        .modal-emoji { font-size:64px;margin-bottom:16px; }
        .modal-title { font-size:22px;font-weight:800;color:#111827;margin-bottom:8px; }
        .modal-desc  { font-size:14px;color:#6b7280;line-height:1.6;margin-bottom:24px; }
        .btn-modal   { padding:12px 32px;background:#059669;color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:600;cursor:pointer;font-family:inherit; }
    </style>

    @php
        $currentStage = $user->getCurrentStage();
        $journey      = $user->current_journey ?? [];

        $stages = [
            [
                'icon'   => '🔍',
                'title'  => 'Observo mi comunidad',
                'desc'   => '¿Qué problema veo a mi alrededor? Observo con mis estudiantes y elegimos una situación real para trabajar.',
                'badge'  => 'INSIGNIA: Observador/a',
                'task'   => 'Elige un problema del banco de ideas o escribe el tuyo propio.',
                'action' => 'He elegido un problema real con mis estudiantes',
                'color'  => '#7c3aed',
            ],
            [
                'icon'   => '💡',
                'title'  => 'Mi gran pregunta',
                'desc'   => 'Convierto el problema en una pregunta guía. ¿Cómo podemos proteger nuestras plantas de las heladas?',
                'badge'  => 'INSIGNIA: Preguntón/a',
                'task'   => 'Formula la pregunta esencial que guiará todo el proyecto.',
                'action' => 'Tengo mi pregunta esencial formulada',
                'color'  => '#059669',
            ],
            [
                'icon'   => '📚',
                'title'  => 'Investigo con los sabios',
                'desc'   => 'Entrevisto a los yachaq (sabios) de mi comunidad y hago experimentos: mido la temperatura, uso instrumentos.',
                'badge'  => 'INSIGNIA: Investigador/a',
                'task'   => 'Genera la base científica y los saberes ancestrales con el planificador.',
                'action' => 'He investigado con mis estudiantes',
                'color'  => '#dc2626',
            ],
            [
                'icon'   => '⚡',
                'title'  => 'Diseño y construyo',
                'desc'   => 'Creo una solución que une los saberes de mi tierra con la tecnología. Hago mi prototipo y lo pruebo.',
                'badge'  => 'INSIGNIA: Creador/a',
                'task'   => 'Construye el prototipo con tus estudiantes usando recursos locales.',
                'action' => 'Hemos construido nuestro prototipo',
                'color'  => '#d97706',
            ],
            [
                'icon'   => '🌟',
                'title'  => 'Comparto y celebro',
                'desc'   => 'Presento el proyecto a mi comunidad. Mis estudiantes son los protagonistas del cambio.',
                'badge'  => 'INSIGNIA: Yachaq STEAM',
                'task'   => 'Organiza la presentación final a la comunidad.',
                'action' => 'Hemos presentado a la comunidad',
                'color'  => '#059669',
            ],
        ];
    @endphp

    <div class="journey-layout">

        {{-- Progreso general --}}
        <div style="background:#fff;border-radius:16px;padding:20px;border:1px solid #d1fae5;margin-bottom:32px;box-shadow:0 2px 12px rgba(5,150,105,0.06);">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                <span style="font-size:13px;font-weight:600;color:#374151;">Proyecto actual</span>
                <span style="font-size:13px;color:#9ca3af;">Etapa {{ $currentStage + 1 }} de 5</span>
            </div>
            <div style="background:#f3f4f6;border-radius:20px;height:10px;overflow:hidden;">
                <div style="background:linear-gradient(to right,#059669,#34d399);height:100%;width:{{ ($currentStage / 5) * 100 }}%;border-radius:20px;transition:width 0.5s;"></div>
            </div>
            <div style="display:flex;justify-content:space-between;margin-top:8px;">
                @foreach($stages as $i => $s)
                <div style="width:20%;text-align:center;">
                    <div style="font-size:16px;">{{ $i < $currentStage ? '✅' : ($i === $currentStage ? $s['icon'] : '🔒') }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Línea de tiempo --}}
        <div class="timeline">
            @foreach($stages as $i => $stage)
            @php
                $status = $i < $currentStage ? 'completed' : ($i === $currentStage ? 'active' : 'locked');
            @endphp
            <div class="stage">
                {{-- Dot central --}}
                <div class="stage-dot {{ $status }}" style="{{ $status === 'locked' ? '' : 'background:' . $stage['color'] }}">
                    {{ $status === 'completed' ? '✅' : $stage['icon'] }}
                </div>

                {{-- Card --}}
                <div class="stage-card {{ $status }}">
                    <div class="stage-number">Etapa {{ $i + 1 }}</div>
                    <div class="stage-title">{{ $stage['title'] }}</div>
                    <div class="stage-desc">{{ $stage['desc'] }}</div>
                    <div class="stage-badge badge-{{ $status }}">
                        🏅 {{ $stage['badge'] }}
                    </div>

                    @if($status === 'completed')
                    <div style="font-size:13px;color:#059669;font-weight:600;">✅ Completada</div>

                    @elseif($status === 'active')
                    <div style="font-size:12px;color:#1e40af;background:#eff6ff;border-radius:8px;padding:8px 12px;margin-bottom:10px;line-height:1.5;">
                        📌 {{ $stage['task'] }}
                    </div>
                    <button type="button" class="btn-stage btn-complete js-complete-stage"
                        data-stage="{{ $i }}"
                        data-action="{{ $stage['action'] }}"
                        data-badge="{{ $stage['badge'] }}">
                        {{ $stage['action'] }} →
                    </button>

                    @else
                    <button class="btn-stage btn-locked" disabled>
                        🔒 Completa la etapa anterior
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Botón reiniciar --}}
        @if($currentStage > 0)
        <div style="text-align:center;margin-top:16px;">
            <form method="POST" action="{{ route('journey.reset') }}"
                onsubmit="return confirm('¿Reiniciar el proyecto actual? Las insignias ganadas se mantienen.')">
                @csrf
                <button type="submit"
                    style="background:none;border:1px solid #e5e7eb;color:#9ca3af;padding:8px 20px;border-radius:8px;font-size:12px;cursor:pointer;font-family:inherit;">
                    Reiniciar proyecto actual
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Modal de celebración --}}
    <div class="modal-overlay" id="celebrationModal">
        <div class="modal-box">
            <div class="modal-emoji" id="modalEmoji">🎉</div>
            <div class="modal-title" id="modalTitle">¡Etapa completada!</div>
            <div class="modal-desc" id="modalDesc">Has ganado una nueva insignia.</div>
            <button class="btn-modal" onclick="closeModal()">¡Continuar! →</button>
        </div>
    </div>

    <script>
        document.querySelectorAll('.js-complete-stage').forEach(function (btn) {
            btn.addEventListener('click', function () {
                completeStage(btn, parseInt(btn.dataset.stage, 10), btn.dataset.action, btn.dataset.badge);
            });
        });

        async function completeStage(btn, stageIndex, action, badge) {
            btn.disabled = true;
            try {
                const res = await fetch('{{ route("journey.advance") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        stage: stageIndex,
                        stage_data: { action: action, badge: badge }
                    })
                });

                if (res.status === 409) { window.location.reload(); return; }

                const data = await res.json();

                if (data.success) {
                    // Mostrar celebración
                    const isProjectComplete = data.project_complete;

                    document.getElementById('modalEmoji').textContent = isProjectComplete ? data.badge.emoji : '🏅';
                    document.getElementById('modalTitle').textContent = isProjectComplete
                        ? '¡Proyecto completado!'
                        : '¡Etapa superada!';
                    document.getElementById('modalDesc').textContent = isProjectComplete
                        ? `¡Felicitaciones! Ganaste la insignia "${data.badge.name}". Llevas ${data.completed} proyecto${data.completed > 1 ? 's' : ''} completado${data.completed > 1 ? 's' : ''}.`
                        : `Has ganado la insignia "${badge}". ¡Sigue adelante!`;

                    document.getElementById('celebrationModal').classList.add('open');
                }
                else { btn.disabled = false; }
            } catch(e) {
                btn.disabled = false;
                alert('Error al avanzar. Intenta de nuevo.');
            }
        }

        function closeModal() {
            document.getElementById('celebrationModal').classList.remove('open');
            window.location.reload();
        }
    </script>
</x-app-layout>