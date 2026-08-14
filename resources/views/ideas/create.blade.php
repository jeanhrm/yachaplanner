<x-app-layout>
    <x-slot name="header" style="padding:0;">
        <div style="background:linear-gradient(135deg,#059669 0%,#047857 100%);padding:20px 24px;">
            <div style="max-width:700px;margin:0 auto;">
                <a href="{{ route('ideas.index') }}"
                    style="color:rgba(255,255,255,0.8);text-decoration:none;font-size:13px;margin-bottom:12px;display:inline-block;">
                    ← Volver al banco
                </a>
                <h1 style="font-size:22px;font-weight:800;color:#fff;">💡 Compartir mi idea de proyecto</h1>
                <p style="font-size:13px;color:rgba(255,255,255,0.8);margin-top:4px;">
                    Tu experiencia puede inspirar a docentes de todo el Perú
                </p>
            </div>
        </div>
    </x-slot>

    <style>
        .create-layout { max-width:700px;margin:32px auto;padding:0 24px; }
        .form-card { background:#fff;border-radius:16px;padding:28px;border:1px solid #d1fae5;box-shadow:0 2px 12px rgba(5,150,105,0.06);margin-bottom:20px; }
        .form-section-title { font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#059669;margin-bottom:16px;padding-bottom:8px;border-bottom:1px solid #f0fdf4; }
        .form-group { margin-bottom:16px; }
        .form-label { display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;text-transform:uppercase;letter-spacing:0.05em; }
        .form-input { width:100%;padding:10px 14px;border:2px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111827;font-family:inherit;transition:all 0.2s;background:#fff;outline:none; }
        .form-input:focus { border-color:#059669;box-shadow:0 0 0 3px rgba(5,150,105,0.1); }
        .form-textarea { min-height:100px;resize:vertical; }
        .form-grid { display:grid;grid-template-columns:1fr 1fr;gap:16px; }
        .btn-submit { width:100%;padding:14px;background:linear-gradient(135deg,#059669,#047857);color:#fff;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;font-family:inherit;box-shadow:0 4px 12px rgba(5,150,105,0.3); }
        .btn-submit:hover { transform:translateY(-1px); }
        .error-msg { background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:10px 14px;font-size:13px;color:#dc2626;margin-bottom:16px; }
    </style>

    <div class="create-layout">

        @if($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('ideas.store') }}">
            @csrf

            <div class="form-card">
                <div class="form-section-title">📋 Información básica</div>

                <div class="form-group">
                    <label class="form-label">Título del proyecto *</label>
                    <input type="text" name="title" class="form-input"
                        placeholder="Ej: Guardianes del Río Ichu" value="{{ old('title') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción *</label>
                    <textarea name="description" class="form-input form-textarea"
                        placeholder="¿De qué trata el proyecto? ¿Qué problema resuelve?" required>{{ old('description') }}</textarea>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Tipo de problema *</label>
                        <select name="problem_type" class="form-input" required>
                            <option value="">— Seleccionar —</option>
                            <option value="ambiental"   {{ old('problem_type')=='ambiental'   ?'selected':'' }}>🌿 Ambiental</option>
                            <option value="social"      {{ old('problem_type')=='social'      ?'selected':'' }}>🤝 Social</option>
                            <option value="tecnologico" {{ old('problem_type')=='tecnologico' ?'selected':'' }}>💻 Tecnológico</option>
                            <option value="cultural"    {{ old('problem_type')=='cultural'    ?'selected':'' }}>🎨 Cultural</option>
                            <option value="salud"       {{ old('problem_type')=='salud'       ?'selected':'' }}>❤️ Salud</option>
                            <option value="economico"   {{ old('problem_type')=='economico'   ?'selected':'' }}>💼 Económico</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nivel educativo *</label>
                        <select name="level" class="form-input" required>
                            <option value="">— Seleccionar —</option>
                            <option value="Primaria"   {{ old('level')=='Primaria'   ?'selected':'' }}>Primaria</option>
                            <option value="Secundaria" {{ old('level')=='Secundaria' ?'selected':'' }}>Secundaria</option>
                            <option value="Ambos"      {{ old('level')=='Ambos'      ?'selected':'' }}>Ambos</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Dificultad *</label>
                        <select name="difficulty" class="form-input" required>
                            <option value="">— Seleccionar —</option>
                            <option value="basico"     {{ old('difficulty')=='basico'     ?'selected':'' }}>🟢 Básico</option>
                            <option value="intermedio" {{ old('difficulty')=='intermedio' ?'selected':'' }}>🟡 Intermedio</option>
                            <option value="avanzado"   {{ old('difficulty')=='avanzado'   ?'selected':'' }}>🔴 Avanzado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Duración aproximada *</label>
                        <select name="duration" class="form-input" required>
                            <option value="">— Seleccionar —</option>
                            <option value="1 semana">1 semana</option>
                            <option value="2 semanas">2 semanas</option>
                            <option value="3 semanas">3 semanas</option>
                            <option value="4 semanas">4 semanas</option>
                            <option value="5 semanas">5 semanas</option>
                            <option value="6 semanas">6 semanas</option>
                            <option value="8 semanas">8 semanas</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Metodología principal *</label>
                    <select name="methodology" class="form-input" required>
                        <option value="">— Seleccionar —</option>
                        <option value="ABP">ABP — Aprendizaje Basado en Proyectos</option>
                        <option value="Indagación científica 5E">Indagación científica 5E</option>
                        <option value="Design Thinking">Design Thinking</option>
                        <option value="Aprendizaje-Servicio">Aprendizaje-Servicio</option>
                        <option value="Etnociencia">Etnociencia</option>
                        <option value="Aprendizaje situado">Aprendizaje situado en comunidad</option>
                    </select>
                </div>
            </div>

            <div class="form-card">
                <div class="form-section-title">⚗️ Contenido pedagógico</div>

                <div class="form-group">
                    <label class="form-label">Conexión STEAM *</label>
                    <textarea name="steam_connection" class="form-input form-textarea"
                        placeholder="Describe cómo se conecta con Ciencia, Tecnología, Ingeniería, Arte y Matemática" required>{{ old('steam_connection') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Ideas para el prototipo *</label>
                    <textarea name="prototype_ideas" class="form-input form-textarea"
                        placeholder="¿Qué pueden construir los estudiantes? ¿Con qué materiales?" required>{{ old('prototype_ideas') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Saberes ancestrales (opcional)</label>
                    <textarea name="ancestral_knowledge" class="form-input form-textarea"
                        placeholder="¿Qué saberes ancestrales o conocimientos locales se conectan con este proyecto?">{{ old('ancestral_knowledge') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Compartir mi idea con docentes del Perú →
            </button>
        </form>
    </div>
</x-app-layout>