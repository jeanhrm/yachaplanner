<x-app-layout>
    <x-slot name="header" style="padding:0;">
        <div style="background:linear-gradient(135deg,#059669 0%,#047857 100%);padding:20px 24px;">
            <div style="max-width:900px;margin:0 auto;">
                <a href="{{ route('ideas.index') }}"
                    style="color:rgba(255,255,255,0.8);text-decoration:none;font-size:13px;display:inline-flex;align-items:center;gap:6px;margin-bottom:12px;">
                    ← Volver al banco de ideas
                </a>
                <h1 style="font-size:24px;font-weight:800;color:#fff;margin-bottom:6px;">
                    {{ $idea->title }}
                </h1>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <span style="background:rgba(255,255,255,0.2);color:#fff;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">
                        📚 {{ $idea->level }}
                    </span>
                    <span style="background:rgba(255,255,255,0.2);color:#fff;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">
                        ⏱ {{ $idea->duration }}
                    </span>
                    <span style="background:rgba(255,255,255,0.2);color:#fff;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">
                        🔬 {{ $idea->methodology }}
                    </span>
                </div>
            </div>
        </div>
    </x-slot>

    <style>
        .idea-layout { max-width:900px;margin:32px auto;padding:0 24px;display:grid;grid-template-columns:1fr 300px;gap:24px; }
        .idea-main { display:flex;flex-direction:column;gap:20px; }
        .idea-section { background:#fff;border-radius:16px;padding:24px;border:1px solid #d1fae5;box-shadow:0 2px 12px rgba(5,150,105,0.06); }
        .section-title { font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#059669;margin-bottom:12px;display:flex;align-items:center;gap:8px; }
        .section-content { font-size:14px;color:#374151;line-height:1.7; }
        .idea-sidebar { display:flex;flex-direction:column;gap:16px; }
        .sidebar-card { background:#fff;border-radius:16px;padding:20px;border:1px solid #d1fae5;box-shadow:0 2px 12px rgba(5,150,105,0.06); }
        .btn-usar-grande { display:block;text-align:center;padding:14px;background:linear-gradient(135deg,#059669,#047857);color:#fff;border-radius:12px;font-size:15px;font-weight:700;text-decoration:none;transition:all 0.2s;box-shadow:0 4px 12px rgba(5,150,105,0.3); }
        .btn-usar-grande:hover { transform:translateY(-1px);box-shadow:0 6px 20px rgba(5,150,105,0.4); }
        .area-pill { display:inline-block;margin:3px;padding:4px 12px;background:#f0fdf4;color:#059669;border:1px solid #d1fae5;border-radius:20px;font-size:12px;font-weight:600; }
        .steam-item { display:flex;gap:10px;margin-bottom:10px; }
        .steam-letter { width:28px;height:28px;border-radius:50%;background:#059669;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0; }
        @media(max-width:768px) { .idea-layout { grid-template-columns:1fr; } }
    </style>

    <div class="idea-layout">
        <div class="idea-main">

            {{-- Descripción --}}
            <div class="idea-section">
                <div class="section-title">📋 Descripción del proyecto</div>
                <div class="section-content">{{ $idea->description }}</div>
            </div>

            {{-- Conexión STEAM --}}
            <div class="idea-section">
                <div class="section-title">⚗️ Conexión STEAM</div>
                <div class="section-content">
                    @php
                        $steamParts = explode('.', $idea->steam_connection);
                        $steamLetters = ['S','T','E','A','M'];
                        $steamColors = ['#059669','#0284c7','#7c3aed','#d97706','#dc2626'];
                        $steamNames = ['Ciencia','Tecnología','Ingeniería','Arte','Matemática'];
                    @endphp
                    @foreach($steamParts as $i => $part)
                        @if(trim($part))
                        <div class="steam-item">
                            <div class="steam-letter" style="background:{{ $steamColors[$i % 5] ?? '#059669' }}">
                                {{ $steamLetters[$i % 5] }}
                            </div>
                            <div style="font-size:13px;color:#374151;line-height:1.5;padding-top:4px;">
                                {{ trim($part) }}
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Saberes ancestrales --}}
            @if($idea->ancestral_knowledge)
            <div class="idea-section" style="border-color:#fde68a;background:#fffbeb;">
                <div class="section-title" style="color:#92400e;">🏺 Saberes Ancestrales</div>
                <div class="section-content" style="color:#78350f;">{{ $idea->ancestral_knowledge }}</div>
            </div>
            @endif

            {{-- Prototipo --}}
            <div class="idea-section">
                <div class="section-title">🔧 Ideas para el Prototipo</div>
                <div class="section-content">{{ $idea->prototype_ideas }}</div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="idea-sidebar">

            <a href="{{ route('chat.index') }}?idea={{ $idea->id }}" class="btn-usar-grande">
                ✍️ Usar en planificador
            </a>

            <div class="sidebar-card">
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;margin-bottom:12px;">
                    Áreas curriculares
                </div>
                @foreach($idea->areas as $area)
                <span class="area-pill">{{ $area }}</span>
                @endforeach
            </div>

            <div class="sidebar-card">
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;margin-bottom:12px;">
                    Detalles
                </div>
                <div style="display:flex;flex-direction:column;gap:8px;font-size:13px;color:#374151;">
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#9ca3af;">Nivel</span>
                        <span style="font-weight:600;">{{ $idea->level }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#9ca3af;">Dificultad</span>
                        <span style="font-weight:600;">{{ ucfirst($idea->difficulty) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#9ca3af;">Duración</span>
                        <span style="font-weight:600;">{{ $idea->duration }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#9ca3af;">Metodología</span>
                        <span style="font-weight:600;">{{ $idea->methodology }}</span>
                    </div>
                </div>
            </div>

            <div class="sidebar-card">
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;margin-bottom:10px;">
                    ¿Tienes una idea mejor?
                </div>
                <p style="font-size:12px;color:#6b7280;line-height:1.5;margin-bottom:12px;">
                    Comparte tu propia experiencia con otros docentes del Perú.
                </p>
                <a href="{{ route('ideas.create') }}"
                    style="display:block;text-align:center;padding:8px;background:#f0fdf4;color:#059669;border:1px solid #d1fae5;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">
                    + Compartir mi idea
                </a>
            </div>
        </div>
    </div>
</x-app-layout>