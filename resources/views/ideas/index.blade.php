<x-app-layout>
    <x-slot name="header" style="padding:0;">
        <div style="background:linear-gradient(135deg,#059669 0%,#047857 100%);padding:20px 24px;">
            <div style="max-width:1280px;margin:0 auto;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                    <div>
                        <h1 style="font-size:22px;font-weight:800;color:#fff;margin-bottom:4px;">
                            💡 Banco de Ideas STEAM
                        </h1>
                        <p style="font-size:13px;color:rgba(255,255,255,0.8);">
                            {{ $ideas->count() }} proyectos disponibles — filtra por tipo de problema
                        </p>
                    </div>
                    <a href="{{ route('ideas.create') }}"
                        style="background:rgba(255,255,255,0.15);border:2px solid rgba(255,255,255,0.4);color:#fff;padding:10px 20px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;transition:all 0.2s;">
                        + Compartir mi idea
                    </a>
                </div>

                {{-- Filtros --}}
                <form method="GET" action="{{ route('ideas.index') }}"
                    style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap;">
                    <select name="problem_type" onchange="this.form.submit()"
                        style="border:none;border-radius:8px;padding:7px 12px;font-size:13px;color:#065f46;background:#d1fae5;font-weight:500;cursor:pointer;">
                        <option value="">Todos los problemas</option>
                        <option value="ambiental"   {{ request('problem_type')=='ambiental'   ?'selected':'' }}>🌿 Ambiental</option>
                        <option value="social"      {{ request('problem_type')=='social'      ?'selected':'' }}>🤝 Social</option>
                        <option value="tecnologico" {{ request('problem_type')=='tecnologico' ?'selected':'' }}>💻 Tecnológico</option>
                        <option value="cultural"    {{ request('problem_type')=='cultural'    ?'selected':'' }}>🎨 Cultural</option>
                        <option value="salud"       {{ request('problem_type')=='salud'       ?'selected':'' }}>❤️ Salud</option>
                        <option value="economico"   {{ request('problem_type')=='economico'   ?'selected':'' }}>💼 Económico</option>
                    </select>

                    <select name="level" onchange="this.form.submit()"
                        style="border:none;border-radius:8px;padding:7px 12px;font-size:13px;color:#065f46;background:#d1fae5;font-weight:500;cursor:pointer;">
                        <option value="">Todos los niveles</option>
                        <option value="Primaria"   {{ request('level')=='Primaria'   ?'selected':'' }}>Primaria</option>
                        <option value="Secundaria" {{ request('level')=='Secundaria' ?'selected':'' }}>Secundaria</option>
                    </select>

                    <select name="difficulty" onchange="this.form.submit()"
                        style="border:none;border-radius:8px;padding:7px 12px;font-size:13px;color:#065f46;background:#d1fae5;font-weight:500;cursor:pointer;">
                        <option value="">Todas las dificultades</option>
                        <option value="basico"      {{ request('difficulty')=='basico'      ?'selected':'' }}>🟢 Básico</option>
                        <option value="intermedio"  {{ request('difficulty')=='intermedio'  ?'selected':'' }}>🟡 Intermedio</option>
                        <option value="avanzado"    {{ request('difficulty')=='avanzado'    ?'selected':'' }}>🔴 Avanzado</option>
                    </select>

                    @if(request('problem_type') || request('level') || request('difficulty'))
                    <a href="{{ route('ideas.index') }}"
                        style="background:rgba(255,255,255,0.2);color:#fff;padding:7px 14px;border-radius:8px;font-size:13px;text-decoration:none;">
                        ✕ Limpiar
                    </a>
                    @endif
                </form>
            </div>
        </div>
    </x-slot>

    <style>
        .ideas-layout { max-width:1280px;margin:24px auto;padding:0 24px; }
        .ideas-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px; }
        .idea-card { background:#fff;border-radius:16px;border:1px solid #d1fae5;box-shadow:0 2px 12px rgba(5,150,105,0.06);overflow:hidden;transition:all 0.2s;display:flex;flex-direction:column; }
        .idea-card:hover { transform:translateY(-4px);box-shadow:0 8px 32px rgba(5,150,105,0.15);border-color:#059669; }
        .idea-card-top { padding:16px 20px 12px;flex:1; }
        .idea-type-badge { display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:10px; }
        .type-ambiental   { background:#d1fae5;color:#065f46; }
        .type-social      { background:#dbeafe;color:#1e40af; }
        .type-tecnologico { background:#ede9fe;color:#5b21b6; }
        .type-cultural    { background:#fef3c7;color:#92400e; }
        .type-salud       { background:#fce7f3;color:#9d174d; }
        .type-economico   { background:#f3f4f6;color:#374151; }
        .idea-title { font-size:16px;font-weight:700;color:#111827;margin-bottom:8px;line-height:1.3; }
        .idea-desc { font-size:13px;color:#6b7280;line-height:1.6;margin-bottom:12px; }
        .idea-meta { display:flex;gap:8px;flex-wrap:wrap;margin-bottom:8px; }
        .idea-tag { font-size:11px;font-weight:600;padding:3px 8px;border-radius:6px;background:#f9fafb;color:#6b7280;border:1px solid #e5e7eb; }
        .idea-areas { display:flex;gap:6px;flex-wrap:wrap; }
        .idea-area-tag { font-size:10px;font-weight:600;padding:2px 8px;border-radius:10px;background:#f0fdf4;color:#059669;border:1px solid #d1fae5; }
        .idea-card-bottom { padding:12px 20px;border-top:1px solid #f0fdf4;display:flex;gap:8px; }
        .btn-ver { flex:1;text-align:center;padding:8px;background:#f0fdf4;color:#059669;border:1px solid #d1fae5;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;transition:all 0.15s; }
        .btn-ver:hover { background:#d1fae5; }
        .btn-usar { flex:1;text-align:center;padding:8px;background:#059669;color:#fff;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;transition:all 0.15s; }
        .btn-usar:hover { background:#047857; }
        .difficulty-dot { width:8px;height:8px;border-radius:50%;display:inline-block;margin-right:4px; }
        .dot-basico { background:#10b981; }
        .dot-intermedio { background:#f59e0b; }
        .dot-avanzado { background:#ef4444; }

        .type-icons { ambiental:'🌿', social:'🤝', tecnologico:'💻', cultural:'🎨', salud:'❤️', economico:'💼' }
    </style>

    @php
        $typeIcons = [
            'ambiental'   => '🌿',
            'social'      => '🤝',
            'tecnologico' => '💻',
            'cultural'    => '🎨',
            'salud'       => '❤️',
            'economico'   => '💼',
        ];
        $typeLabels = [
            'ambiental'   => 'Ambiental',
            'social'      => 'Social',
            'tecnologico' => 'Tecnológico',
            'cultural'    => 'Cultural',
            'salud'       => 'Salud',
            'economico'   => 'Económico',
        ];
        $diffIcons = [
            'basico'     => '🟢',
            'intermedio' => '🟡',
            'avanzado'   => '🔴',
        ];
    @endphp

    <div class="ideas-layout">
        @if(session('success'))
        <div style="background:#d1fae5;border:1px solid #a7f3d0;border-radius:10px;padding:12px 16px;font-size:13px;color:#065f46;margin-bottom:20px;">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if($ideas->isEmpty())
        <div style="text-align:center;padding:60px 20px;color:#9ca3af;">
            <div style="font-size:48px;margin-bottom:16px;">🔍</div>
            <h3 style="font-size:18px;font-weight:600;color:#374151;margin-bottom:8px;">No hay ideas con estos filtros</h3>
            <p>Prueba con otros filtros o <a href="{{ route('ideas.index') }}" style="color:#059669;">ve todas las ideas</a></p>
        </div>
        @else
        <div class="ideas-grid">
            @foreach($ideas as $idea)
            <div class="idea-card">
                <div class="idea-card-top">
                    <span class="idea-type-badge type-{{ $idea->problem_type }}">
                        {{ $typeIcons[$idea->problem_type] ?? '💡' }}
                        {{ $typeLabels[$idea->problem_type] ?? $idea->problem_type }}
                    </span>

                    <div class="idea-title">{{ $idea->title }}</div>
                    <div class="idea-desc">{{ Str::limit($idea->description, 120) }}</div>

                    <div class="idea-meta">
                        <span class="idea-tag">📚 {{ $idea->level }}</span>
                        <span class="idea-tag">{{ $diffIcons[$idea->difficulty] ?? '' }} {{ ucfirst($idea->difficulty) }}</span>
                        <span class="idea-tag">⏱ {{ $idea->duration }}</span>
                        <span class="idea-tag">🔬 {{ $idea->methodology }}</span>
                    </div>

                    <div class="idea-areas">
                        @foreach($idea->areas as $area)
                        <span class="idea-area-tag">{{ $area }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="idea-card-bottom">
                    <a href="{{ route('ideas.show', $idea) }}" class="btn-ver">Ver detalle</a>
                    <a href="{{ route('chat.index') }}?idea={{ $idea->id }}" class="btn-usar">
                        ✍️ Usar en planificador
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</x-app-layout>