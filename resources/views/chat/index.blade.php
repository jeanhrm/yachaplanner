<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:18px;font-weight:600;color:#111827;">
                🌱 YachaPlanner — Asistente STEAM
            </h2>
            <span style="font-size:13px;color:#6b7280;">
                Créditos disponibles esta semana:
                <strong id="credits-count" style="color:#059669;">{{ auth()->user()->remainingCredits() }}</strong>
            </span>
        </div>
    </x-slot>

    <style>
        * { box-sizing: border-box; }
        .yp-layout { display: flex; gap: 16px; height: calc(100vh - 130px); padding: 16px 24px; max-width: 1280px; margin: 0 auto; }
        .yp-sidebar { width: 220px; flex-shrink: 0; display: flex; flex-direction: column; gap: 12px; }
        .yp-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.08); padding: 14px; }
        .yp-card-title { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 10px; }
        .mod-btn { width: 100%; text-align: left; padding: 9px 12px; border-radius: 8px; font-size: 13px; color: #374151; background: transparent; border: none; cursor: pointer; transition: all 0.15s; display: flex; align-items: center; gap: 8px; }
        .mod-btn:hover { background: #f0fdf4; color: #059669; }
        .mod-btn.active { background: #059669; color: #fff; font-weight: 500; }
        .mod-btn .mod-icon { font-size: 14px; }
        .recent-btn { width: 100%; text-align: left; padding: 7px 10px; border-radius: 6px; font-size: 12px; color: #6b7280; background: transparent; border: none; cursor: pointer; transition: background 0.12s; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .recent-btn:hover { background: #f9fafb; color: #111827; }
        .yp-main { flex: 1; display: flex; flex-direction: column; background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.08); overflow: hidden; }
        .yp-chat-header { padding: 12px 16px; border-bottom: 1px solid #f3f4f6; background: #fafafa; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
        .yp-module-label { font-size: 13px; font-weight: 500; color: #374151; display: flex; align-items: center; gap: 8px; }
        .yp-module-badge { background: #d1fae5; color: #065f46; font-size: 11px; padding: 2px 8px; border-radius: 20px; font-weight: 500; }
        .new-chat-btn { font-size: 12px; color: #9ca3af; background: none; border: 1px solid #e5e7eb; border-radius: 6px; padding: 4px 10px; cursor: pointer; transition: all 0.12s; }
        .new-chat-btn:hover { color: #374151; border-color: #d1d5db; }
        #messages { flex: 1; overflow-y: auto; padding: 20px 16px; display: flex; flex-direction: column; gap: 16px; scroll-behavior: smooth; }
        .msg-user { display: flex; justify-content: flex-end; }
        .msg-bot  { display: flex; justify-content: flex-start; align-items: flex-start; gap: 10px; }
        .bot-avatar { width: 32px; height: 32px; border-radius: 50%; background: #d1fae5; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
        .bubble-user { background: #059669; color: #fff; padding: 10px 14px; border-radius: 18px 18px 4px 18px; font-size: 14px; line-height: 1.6; max-width: 70%; }
        .bubble-bot  { background: #f3f4f6; color: #111827; padding: 12px 16px; border-radius: 4px 18px 18px 18px; font-size: 14px; line-height: 1.7; max-width: 80%; }
        .bubble-bot h2, .bubble-bot h3 { font-size: 14px; font-weight: 600; margin: 12px 0 6px; color: #059669; }
        .bubble-bot h4 { font-size: 13px; font-weight: 600; margin: 10px 0 4px; color: #374151; }
        .bubble-bot ul, .bubble-bot ol { padding-left: 18px; margin: 6px 0; }
        .bubble-bot li { margin-bottom: 3px; }
        .bubble-bot table { width: 100%; border-collapse: collapse; font-size: 12px; margin: 8px 0; }
        .bubble-bot th { background: #059669; color: #fff; padding: 6px 10px; text-align: left; font-weight: 500; }
        .bubble-bot td { padding: 5px 10px; border-bottom: 1px solid #e5e7eb; }
        .bubble-bot tr:nth-child(even) td { background: #f9fafb; }
        .bubble-bot strong { font-weight: 600; }
        .bubble-bot p { margin-bottom: 6px; }
        .bubble-bot p:last-child { margin-bottom: 0; }
        .typing-bubble { background: #f3f4f6; padding: 12px 16px; border-radius: 4px 18px 18px 18px; display: flex; gap: 5px; align-items: center; }
        .typing-dot { width: 7px; height: 7px; border-radius: 50%; background: #9ca3af; animation: bounce 1.2s infinite; }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes bounce { 0%,80%,100%{transform:scale(0.7);opacity:0.4} 40%{transform:scale(1);opacity:1} }
        .yp-input-area { padding: 12px 16px; border-top: 1px solid #f3f4f6; flex-shrink: 0; }
        .yp-input-row { display: flex; gap: 10px; align-items: flex-end; }
        #user-input { flex: 1; resize: none; border: 1.5px solid #e5e7eb; border-radius: 10px; padding: 10px 14px; font-size: 14px; font-family: inherit; line-height: 1.5; outline: none; transition: border-color 0.15s; max-height: 120px; }
        #user-input:focus { border-color: #059669; }
        #send-btn { padding: 10px 18px; background: #059669; color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.15s; white-space: nowrap; }
        #send-btn:hover { background: #047857; }
        #send-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .yp-hint { font-size: 11px; color: #9ca3af; margin-top: 6px; }
        .welcome-msg { text-align: center; color: #9ca3af; margin: auto; }
        .welcome-msg .w-icon { font-size: 48px; margin-bottom: 12px; }
        .welcome-msg p { font-size: 14px; line-height: 1.6; }
        .welcome-msg em { color: #059669; font-style: normal; font-weight: 500; }
    </style>

    <div class="yp-layout">

        {{-- Sidebar --}}
        <div class="yp-sidebar" style="width:260px;">

            {{-- Módulos --}}
            <div class="yp-card">
                <div class="yp-card-title">Módulos</div>
                @php
                    $icons = ['bimestral'=>'📅','sesion'=>'📝','abp'=>'🔬','rubrica'=>'✅'];
                @endphp
                @foreach($modules as $module)
                <button onclick="selectModule('{{ $module->slug }}', '{{ $module->name }}')"
                    id="btn-{{ $module->slug }}"
                    class="mod-btn">
                    <span class="mod-icon">{{ $icons[$module->slug] ?? '📄' }}</span>
                    {{ $module->name }}
                </button>
                @endforeach
            </div>

            {{-- Contexto pedagógico --}}
            <div class="yp-card" style="display:flex;flex-direction:column;gap:10px;">
                <div class="yp-card-title">Contexto pedagógico</div>

                {{-- Nivel --}}
                <div>
                    <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:4px;">Nivel</label>
                    <select id="ctx-nivel" onchange="updateGrados()" style="width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:6px 8px;font-size:13px;color:#374151;background:#fff;">
                        <option value="">— Seleccionar —</option>
                        <option value="Primaria">Primaria</option>
                        <option value="Secundaria">Secundaria</option>
                    </select>
                </div>

                {{-- Grado --}}
                <div>
                    <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:4px;">Grado</label>
                    <select id="ctx-grado" style="width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:6px 8px;font-size:13px;color:#374151;background:#fff;">
                        <option value="">— Primero nivel —</option>
                    </select>
                </div>

                {{-- Área --}}
                <div>
                    <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:4px;">Área curricular</label>
                    <select id="ctx-area" style="width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:6px 8px;font-size:13px;color:#374151;background:#fff;">
                        <option value="">— Seleccionar —</option>
                        <option>Matemática</option>
                        <option>Comunicación</option>
                        <option>Ciencia y Tecnología</option>
                        <option>Personal Social</option>
                        <option>Arte y Cultura</option>
                        <option>Educación Física</option>
                        <option>Inglés</option>
                        <option>Educación Religiosa</option>
                        <option>Desarrollo Personal, Ciudadanía y Cívica</option>
                        <option>Historia, Geografía y Economía</option>
                        <option>Educación para el Trabajo</option>
                    </select>
                </div>

                {{-- Metodología --}}
                <div>
                    <label style="font-size:11px;font-weight:600;color:#6b7280;display:block;margin-bottom:4px;">Metodología activa</label>
                    <select id="ctx-metodologia" onchange="checkMetodologia()" style="width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:6px 8px;font-size:13px;color:#374151;background:#fff;">
                        <option value="">— Seleccionar —</option>
                        <optgroup label="Proyectos">
                            <option value="ABP">ABP — Aprendizaje Basado en Proyectos</option>
                            <option value="ABR">ABR — Aprendizaje Basado en Retos</option>
                            <option value="Design Thinking">Design Thinking</option>
                            <option value="STEAM integrado">STEAM Integrado</option>
                        </optgroup>
                        <optgroup label="Indagación">
                            <option value="Indagación científica 5E">Indagación científica (5E)</option>
                            <option value="Aprendizaje por descubrimiento">Aprendizaje por descubrimiento</option>
                            <option value="Estudio de casos">Estudio de casos</option>
                        </optgroup>
                        <optgroup label="Colaborativas">
                            <option value="Aprendizaje cooperativo">Aprendizaje cooperativo</option>
                            <option value="Aula invertida">Aula invertida (Flipped)</option>
                            <option value="Tutoría entre pares">Tutoría entre pares</option>
                        </optgroup>
                        <optgroup label="Contexto andino">
                            <option value="Aprendizaje-Servicio">Aprendizaje-Servicio</option>
                            <option value="Etnociencia">Etnociencia (saberes andinos)</option>
                            <option value="Aprendizaje situado en la comunidad">Aprendizaje situado en comunidad</option>
                        </optgroup>
                    </select>
                </div>

                {{-- Alerta interdisciplinaria --}}
                <div id="alerta-interdisciplinar" style="display:none;background:#f0fdf4;border:1px solid #a7f3d0;border-radius:8px;padding:10px;font-size:12px;color:#065f46;line-height:1.5;">
                    <strong>💡 Metodología interdisciplinaria</strong><br>
                    <span id="alerta-texto"></span>
                </div>

                {{-- Info metodología --}}
                <div id="info-metodologia" style="display:none;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:10px;font-size:12px;color:#92400e;line-height:1.5;">
                    <span id="info-texto"></span>
                </div>

            </div>

            {{-- Recientes --}}
            @if($sessions->count())
            <div class="yp-card">
                <div class="yp-card-title">Recientes</div>
                @foreach($sessions as $session)
                <button onclick="loadSession({{ $session->id }}, '{{ $session->module }}')"
                    class="recent-btn" title="{{ $session->title }}">
                    {{ $session->title ?? 'Sin título' }}
                </button>
                @endforeach
            </div>
            @endif
        </div>


        {{-- Chat principal --}}
        <div class="yp-main">
            <div class="yp-chat-header">
                <div class="yp-module-label">
                    <span id="current-module-name">Selecciona un módulo</span>
                    <span id="module-badge" class="yp-module-badge" style="display:none"></span>
                </div>
                <div style="display:flex;gap:8px;align-items:center;">
                    <button id="export-btn" onclick="exportWord()"
                        style="display:none;font-size:12px;color:#1a7a4a;background:#f0fdf4;border:1px solid #a7f3d0;border-radius:6px;padding:5px 12px;cursor:pointer;">
                        ⬇ Exportar Word
                    </button>
                    <button onclick="newChat()" class="new-chat-btn">+ Nueva sesión</button>
                </div>
            </div>

            <div id="messages">
                <div class="welcome-msg">
                    <div class="w-icon">🌱</div>
                    <p>Selecciona un módulo y escribe tu solicitud.<br>
                    Ejemplo: <em>"Prog. bimestral de CyT para 4to primaria"</em></p>
                </div>
            </div>

            <div class="yp-input-area">
                <div class="yp-input-row">
                    <textarea id="user-input" rows="2"
                        placeholder="Escribe tu solicitud pedagógica..."
                        onkeydown="handleKey(event)"></textarea>
                    <button id="send-btn" onclick="sendMessage()">Enviar ➤</button>
                </div>
                <div class="yp-hint">
                    Módulo: <span id="module-label" style="color:#059669;font-weight:500;">ninguno</span> ·
                    Enter para enviar · Shift+Enter para nueva línea
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/9.1.6/marked.min.js"></script>
    <script>
        let currentModule = null;
        let currentSessionId = null;

        marked.setOptions({ breaks: true, gfm: true });

        function selectModule(slug, name) {
            currentModule = slug;
            currentSessionId = null;
            document.getElementById('current-module-name').textContent = name;
            document.getElementById('module-label').textContent = name;
            const badge = document.getElementById('module-badge');
            badge.textContent = 'activo';
            badge.style.display = 'inline';
            document.querySelectorAll('.mod-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('btn-' + slug).classList.add('active');
            document.getElementById('messages').innerHTML =
                `<div class="welcome-msg"><p>Módulo <em>${name}</em> activado.<br>¿Qué necesitas crear?</p></div>`;
            document.getElementById('export-btn').style.display = 'none';
            document.getElementById('user-input').focus();
        }

        function newChat() {
            currentSessionId = null;
            document.getElementById('export-btn').style.display = 'none';
            document.getElementById('messages').innerHTML =
                '<div class="welcome-msg"><p>Nueva sesión iniciada.<br>Escribe tu solicitud.</p></div>';
        }

        function loadSession(id, mod) {
            currentSessionId = id;
            currentModule = mod;
            document.getElementById('current-module-name').textContent = mod;
            document.getElementById('module-label').textContent = mod;
            document.getElementById('export-btn').style.display = 'inline';
        }

        function handleKey(e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
        }

        function addMessage(role, content) {
            document.querySelector('.welcome-msg')?.remove();
            const msgs = document.getElementById('messages');
            const wrap = document.createElement('div');
            if (role === 'user') {
                wrap.className = 'msg-user';
                wrap.innerHTML = `<div class="bubble-user">${content.replace(/\n/g,'<br>')}</div>`;
            } else {
                wrap.className = 'msg-bot';
                const html = marked.parse(content);
                wrap.innerHTML = `<div class="bot-avatar">🌱</div><div class="bubble-bot">${html}</div>`;
            }
            msgs.appendChild(wrap);
            msgs.scrollTop = msgs.scrollHeight;
        }

        function showTyping() {
            document.querySelector('.welcome-msg')?.remove();
            const msgs = document.getElementById('messages');
            const wrap = document.createElement('div');
            wrap.id = 'typing'; wrap.className = 'msg-bot';
            wrap.innerHTML = `<div class="bot-avatar">🌱</div><div class="typing-bubble"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div>`;
            msgs.appendChild(wrap);
            msgs.scrollTop = msgs.scrollHeight;
        }

        function removeTyping() { document.getElementById('typing')?.remove(); }

        function exportWord() {
            if (!currentSessionId) {
                alert('Primero genera una respuesta para exportar.');
                return;
            }
            window.open(`/export/word/${currentSessionId}`, '_blank');
        }

        async function sendMessage() {
            const input = document.getElementById('user-input');
            const text = input.value.trim();
            if (!text) return;
            if (!currentModule) { alert('Selecciona un módulo primero'); return; }
            input.value = '';
            input.style.height = 'auto';
            document.getElementById('send-btn').disabled = true;
            addMessage('user', text);
            showTyping();

            try {
                const res = await fetch('{{ route("chat.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        module: currentModule,
                        message: getContextoPedagogico() + text,
                        session_id: currentSessionId,
                    })
                });

                if (!res.ok) {
                    removeTyping();
                    addMessage('assistant', '❌ Error al conectar con el servidor.');
                    document.getElementById('send-btn').disabled = false;
                    input.focus();
                    return;
                }

                const reader = res.body.getReader();
                const decoder = new TextDecoder();
                let buffer = '';
                let fullReply = '';
                let botBubble = null;

                removeTyping();

                while (true) {
                    const { done, value } = await reader.read();
                    if (done) break;

                    buffer += decoder.decode(value, { stream: true });
                    const lines = buffer.split('\n');
                    buffer = lines.pop();

                    for (const line of lines) {
                        if (!line.startsWith('data: ')) continue;
                        const json = line.slice(6).trim();
                        if (!json) continue;

                        try {
                            const data = JSON.parse(json);

                            if (data.chunk !== undefined) {
                                fullReply += data.chunk;
                                if (!botBubble) {
                                    document.querySelector('.welcome-msg')?.remove();
                                    const msgs = document.getElementById('messages');
                                    const wrap = document.createElement('div');
                                    wrap.className = 'msg-bot';
                                    wrap.innerHTML = `<div class="bot-avatar">🌱</div><div class="bubble-bot" id="streaming-bubble"></div>`;
                                    msgs.appendChild(wrap);
                                    botBubble = document.getElementById('streaming-bubble');
                                }
                                botBubble.innerHTML = marked.parse(fullReply);
                                document.getElementById('messages').scrollTop = 99999;
                            }

                            if (data.done) {
                                currentSessionId = data.session_id;
                                document.getElementById('credits-count').textContent = data.credits_remaining;
                                document.getElementById('export-btn').style.display = 'inline';
                                if (botBubble) botBubble.id = '';
                            }
                        } catch(e) {}
                    }
                }

            } catch(e) {
                removeTyping();
                addMessage('assistant', '❌ Error de conexión. Intenta de nuevo.');
            }

            document.getElementById('send-btn').disabled = false;
            input.focus();
        }

        document.getElementById('user-input').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    
            // ===== CONTEXTO PEDAGÓGICO =====

        const gradosPrimaria = [
            '1° de Primaria','2° de Primaria','3° de Primaria',
            '4° de Primaria','5° de Primaria','6° de Primaria'
        ];

        const gradosSecundaria = [
            '1° de Secundaria','2° de Secundaria','3° de Secundaria',
            '4° de Secundaria','5° de Secundaria'
        ];

        const metodologiasInterdisciplinarias = [
            'ABP','ABR','Design Thinking','STEAM integrado',
            'Aprendizaje-Servicio','Etnociencia','Aprendizaje situado en la comunidad'
        ];

        const infoMetodologias = {
            'ABP': 'El ABP parte de una pregunta esencial y un producto final. Los estudiantes investigan, colaboran y presentan soluciones reales.',
            'ABR': 'El ABR plantea un reto concreto a resolver. Ideal para conectar ciencia y tecnología con problemas de la comunidad.',
            'Design Thinking': 'Metodología centrada en el usuario con 5 fases: Empatizar, Definir, Idear, Prototipar y Evaluar.',
            'STEAM integrado': 'Integra Ciencia, Tecnología, Ingeniería, Arte y Matemática en torno a un proyecto común.',
            'Indagación científica 5E': 'Ciclo de 5 fases: Enganche, Exploración, Explicación, Elaboración y Evaluación. Ideal para Ciencia y Tecnología.',
            'Aprendizaje por descubrimiento': 'El estudiante construye su conocimiento a través de la exploración activa y el cuestionamiento.',
            'Estudio de casos': 'Análisis profundo de situaciones reales o simuladas para desarrollar pensamiento crítico.',
            'Aprendizaje cooperativo': 'Trabajo en equipo con roles definidos, interdependencia positiva y responsabilidad individual.',
            'Aula invertida': 'El estudiante revisa contenidos en casa y el aula se usa para práctica, debate y proyectos.',
            'Tutoría entre pares': 'Estudiantes más avanzados apoyan a sus compañeros, reforzando el aprendizaje de ambos.',
            'Aprendizaje-Servicio': 'Combina aprendizaje curricular con servicio a la comunidad. Muy potente en contextos rurales andinos.',
            'Etnociencia': 'Integra saberes andinos (medicina, astronomía, agricultura quechua) con el currículo científico occidental.',
            'Aprendizaje situado en la comunidad': 'El aprendizaje ocurre en contextos reales de la comunidad: chacra, río, feria, municipio.'
        };

        const alertasInterdisciplinares = {
            'ABP': 'Esta metodología funciona mejor articulando 2 o más áreas. En secundaria, coordina con docentes de otras áreas para un proyecto integrador.',
            'ABR': 'El reto puede involucrar Ciencia, Matemática y Comunicación. Considera trabajar con otro docente para mayor impacto.',
            'Design Thinking': 'Design Thinking se potencia al cruzar Arte, Ciencia y Comunicación. Ideal para trabajo interdocente en secundaria.',
            'STEAM integrado': 'STEAM requiere coordinar al menos 3 áreas. En primaria lo puede hacer un solo docente; en secundaria involucra a varios.',
            'Aprendizaje-Servicio': 'Este enfoque conecta varias áreas con la comunidad. Coordina con Personal Social, Comunicación y el área técnica.',
            'Etnociencia': 'La etnociencia articula Ciencia y Tecnología con Comunicación (quechua) y Personal Social. Involucra a la comunidad.',
            'Aprendizaje situado en la comunidad': 'El aprendizaje situado puede cruzar múltiples áreas según el contexto elegido. Coordina con otros docentes.'
        };

        function updateGrados() {
            const nivel = document.getElementById('ctx-nivel').value;
            const gradoSelect = document.getElementById('ctx-grado');
            gradoSelect.innerHTML = '<option value="">— Seleccionar —</option>';

            const grados = nivel === 'Primaria' ? gradosPrimaria :
                        nivel === 'Secundaria' ? gradosSecundaria : [];

            grados.forEach(g => {
                const opt = document.createElement('option');
                opt.value = g;
                opt.textContent = g;
                gradoSelect.appendChild(opt);
            });

            // Filtrar áreas según nivel
            const areaSelect = document.getElementById('ctx-area');
            const areasSecundaria = [
                'Desarrollo Personal, Ciudadanía y Cívica',
                'Historia, Geografía y Economía',
                'Educación para el Trabajo',
                'Inglés'
            ];
            Array.from(areaSelect.options).forEach(opt => {
                if (areasSecundaria.includes(opt.value)) {
                    opt.style.display = nivel === 'Secundaria' ? '' : 'none';
                }
            });
        }

        function checkMetodologia() {
            const met = document.getElementById('ctx-metodologia').value;
            const nivel = document.getElementById('ctx-nivel').value;
            const alertaDiv = document.getElementById('alerta-interdisciplinar');
            const alertaTexto = document.getElementById('alerta-texto');
            const infoDiv = document.getElementById('info-metodologia');
            const infoTexto = document.getElementById('info-texto');

            // Info de la metodología
            if (met && infoMetodologias[met]) {
                infoTexto.textContent = '📘 ' + infoMetodologias[met];
                infoDiv.style.display = 'block';
            } else {
                infoDiv.style.display = 'none';
            }

            // Alerta interdisciplinaria
            if (met && metodologiasInterdisciplinarias.includes(met)) {
                let texto = alertasInterdisciplinares[met] || '';
                if (nivel === 'Secundaria') {
                    texto += ' En secundaria cada área tiene un docente distinto — la coordinación es clave.';
                }
                alertaTexto.textContent = texto;
                alertaDiv.style.display = 'block';
            } else {
                alertaDiv.style.display = 'none';
            }
        }

        function getContextoPedagogico() {
            const nivel = document.getElementById('ctx-nivel').value;
            const grado = document.getElementById('ctx-grado').value;
            const area  = document.getElementById('ctx-area').value;
            const met   = document.getElementById('ctx-metodologia').value;

            let ctx = '';
            if (nivel)  ctx += `Nivel: ${nivel}. `;
            if (grado)  ctx += `Grado: ${grado}. `;
            if (area)   ctx += `Área: ${area}. `;
            if (met)    ctx += `Metodología: ${met}. `;
            return ctx;
        }



    </script>
</x-app-layout>