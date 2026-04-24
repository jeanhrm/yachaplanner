<x-app-layout>
    <x-slot name="header" style="padding:0;">
        {{-- Barra de contexto pedagógico --}}
        <div style="background:linear-gradient(135deg,#059669 0%,#047857 100%);padding:12px 24px;box-shadow:0 2px 8px rgba(5,150,105,0.3);">
            <div style="max-width:1280px;margin:0 auto;display:flex;align-items:center;gap:10px;flex-wrap:wrap;">

                {{-- Logo --}}
                <div style="font-size:15px;font-weight:700;color:#fff;margin-right:8px;display:flex;align-items:center;gap:6px;">
                    🌱 <span>YachaPlanner</span>
                </div>

                <div style="width:1px;height:24px;background:rgba(255,255,255,0.3);"></div>

                {{-- Nivel --}}
                <div style="display:flex;align-items:center;gap:6px;">
                    <label style="font-size:11px;font-weight:600;color:rgba(255,255,255,0.8);white-space:nowrap;">Nivel</label>
                    <select id="ctx-nivel" onchange="updateGrados()" style="border:none;border-radius:8px;padding:5px 10px;font-size:13px;color:#065f46;background:#d1fae5;font-weight:500;cursor:pointer;">
                        <option value="">— Seleccionar —</option>
                        <option value="Primaria">Primaria</option>
                        <option value="Secundaria">Secundaria</option>
                    </select>
                </div>

                {{-- Grado --}}
                <div style="display:flex;align-items:center;gap:6px;">
                    <label style="font-size:11px;font-weight:600;color:rgba(255,255,255,0.8);white-space:nowrap;">Grado</label>
                    <select id="ctx-grado" style="border:none;border-radius:8px;padding:5px 10px;font-size:13px;color:#065f46;background:#d1fae5;font-weight:500;cursor:pointer;">
                        <option value="">— Primero nivel —</option>
                    </select>
                </div>

                {{-- Área --}}
                <div style="display:flex;align-items:center;gap:6px;">
                    <label style="font-size:11px;font-weight:600;color:rgba(255,255,255,0.8);white-space:nowrap;">Tu área</label>
                    <select id="ctx-area-docente" onchange="updateDocumentos(); checkAreaDocente()" style="border:none;border-radius:8px;padding:5px 10px;font-size:13px;color:#065f46;background:#d1fae5;font-weight:500;cursor:pointer;">
                        <option value="">— Seleccionar —</option>
                        <option value="Todas las áreas (primaria)">Todas las áreas (tutor)</option>
                        <optgroup label="Secundaria">
                            <option value="Matemática">Matemática</option>
                            <option value="Comunicación">Comunicación</option>
                            <option value="Ciencia y Tecnología">Ciencia y Tecnología</option>
                            <option value="Personal Social">Personal Social</option>
                            <option value="Historia, Geografía y Economía">Historia, Geografía y Economía</option>
                            <option value="Desarrollo Personal y Cívica">Desarrollo Personal y Cívica</option>
                            <option value="Arte y Cultura">Arte y Cultura</option>
                            <option value="Educación Física">Educación Física</option>
                            <option value="Inglés">Inglés</option>
                            <option value="Educación para el Trabajo">Educación para el Trabajo</option>
                            <option value="Educación Religiosa">Educación Religiosa</option>
                        </optgroup>
                    </select>
                </div>

                <div style="width:1px;height:24px;background:rgba(255,255,255,0.3);"></div>

                {{-- ¿Con quién trabajas? --}}
                <div style="display:flex;align-items:center;gap:6px;">
                    <label style="font-size:11px;font-weight:600;color:rgba(255,255,255,0.8);white-space:nowrap;">Trabajo</label>
                    <div style="display:flex;gap:4px;">
                        <button onclick="selectEquipo(this,'solo')" id="eq-solo"
                            style="padding:5px 10px;border:2px solid rgba(255,255,255,0.4);border-radius:8px;font-size:12px;color:#fff;background:transparent;cursor:pointer;font-weight:500;transition:all 0.2s;">
                            👤 Solo
                        </button>
                        <button onclick="selectEquipo(this,'con-otro')" id="eq-con-otro"
                            style="padding:5px 10px;border:2px solid rgba(255,255,255,0.4);border-radius:8px;font-size:12px;color:#fff;background:transparent;cursor:pointer;font-weight:500;transition:all 0.2s;">
                            👥 Con colega
                        </button>
                        <button onclick="selectEquipo(this,'equipo')" id="eq-equipo"
                            style="padding:5px 10px;border:2px solid rgba(255,255,255,0.4);border-radius:8px;font-size:12px;color:#fff;background:transparent;cursor:pointer;font-weight:500;transition:all 0.2s;">
                            🏫 Equipo
                        </button>
                    </div>
                    <input type="hidden" id="ctx-equipo" value="">
                </div>

                <div style="width:1px;height:24px;background:rgba(255,255,255,0.3);"></div>

                {{-- Metodología --}}
                <div style="display:flex;align-items:center;gap:6px;">
                    <label style="font-size:11px;font-weight:600;color:rgba(255,255,255,0.8);white-space:nowrap;">Metodología</label>
                    <select id="ctx-metodologia" onchange="checkMetodologia()" style="border:none;border-radius:8px;padding:5px 10px;font-size:13px;color:#065f46;background:#d1fae5;font-weight:500;cursor:pointer;max-width:180px;">
                        <option value="">— La IA sugiere —</option>
                        <optgroup label="Proyectos">
                            <option value="ABP">ABP</option>
                            <option value="ABR">ABR</option>
                            <option value="Design Thinking">Design Thinking</option>
                            <option value="STEAM integrado">STEAM Integrado</option>
                        </optgroup>
                        <optgroup label="Indagación">
                            <option value="Indagación científica 5E">Indagación 5E</option>
                            <option value="Aprendizaje por descubrimiento">Por descubrimiento</option>
                            <option value="Estudio de casos">Estudio de casos</option>
                        </optgroup>
                        <optgroup label="Colaborativas">
                            <option value="Aprendizaje cooperativo">Cooperativo</option>
                            <option value="Aula invertida">Aula invertida</option>
                            <option value="Tutoría entre pares">Tutoría entre pares</option>
                        </optgroup>
                        <optgroup label="Contexto andino">
                            <option value="Aprendizaje-Servicio">Aprendizaje-Servicio</option>
                            <option value="Etnociencia">Etnociencia</option>
                            <option value="Aprendizaje situado">Situado en comunidad</option>
                        </optgroup>
                    </select>
                </div>

                {{-- Créditos --}}
                <div style="margin-left:auto;background:rgba(255,255,255,0.15);border-radius:20px;padding:5px 14px;font-size:12px;color:#fff;white-space:nowrap;border:1px solid rgba(255,255,255,0.3);">
                    ⚡ <strong id="credits-count">{{ auth()->user()->remainingCredits() }}</strong> créditos
                </div>
            </div>

            {{-- Alertas --}}
            <div id="ctx-alertas" style="max-width:1280px;margin:6px auto 0;display:flex;gap:8px;flex-wrap:wrap;">
                <div id="info-area-docente" style="display:none;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);border-radius:6px;padding:4px 12px;font-size:11px;color:#fff;"></div>
                <div id="info-metodologia" style="display:none;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);border-radius:6px;padding:4px 12px;font-size:11px;color:#fff;"></div>
                <div id="alerta-equipo" style="display:none;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);border-radius:6px;padding:4px 12px;font-size:11px;color:#fff;"></div>
            </div>
        </div>
    </x-slot>

    <style>
        * { box-sizing: border-box; }

        body { background: #f0fdf4; }

        .yp-layout {
            display: flex;
            gap: 0;
            height: calc(100vh - 145px);
            max-width: 1280px;
            margin: 0 auto;
            padding: 16px;
            gap: 16px;
        }

        /* SIDEBAR */
        .yp-sidebar {
            width: 260px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
            overflow-y: auto;
        }

        .sidebar-card {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 2px 12px rgba(5,150,105,0.08);
            border: 1px solid #d1fae5;
        }

        .sidebar-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #059669;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .doc-btn {
            width: 100%;
            text-align: left;
            padding: 12px;
            border-radius: 12px;
            font-size: 13px;
            color: #374151;
            background: #f9fafb;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            gap: 3px;
            margin-bottom: 6px;
        }

        .doc-btn:hover {
            background: #f0fdf4;
            border-color: #a7f3d0;
            transform: translateX(3px);
        }

        .doc-btn.active {
            background: linear-gradient(135deg, #059669, #047857);
            border-color: #059669;
            color: #fff;
            box-shadow: 0 4px 12px rgba(5,150,105,0.3);
            transform: translateX(3px);
        }

        .doc-btn.active .doc-desc { color: #d1fae5; }
        .doc-btn.active .doc-tag { background: rgba(255,255,255,0.2); color: #fff; }

        .doc-btn-header {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 13px;
        }

        .doc-desc {
            font-size: 11px;
            color: #9ca3af;
            padding-left: 24px;
            line-height: 1.4;
        }

        .doc-tag {
            display: inline-block;
            margin-top: 2px;
            margin-left: 24px;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 10px;
            letter-spacing: 0.05em;
        }

        .tag-steam { background: #d1fae5; color: #065f46; }
        .tag-inter { background: #ede9fe; color: #5b21b6; }
        .tag-basica { background: #f3f4f6; color: #6b7280; }

        .recent-btn {
            width: 100%;
            text-align: left;
            padding: 8px 10px;
            border-radius: 8px;
            font-size: 12px;
            color: #6b7280;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all 0.15s;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }

        .recent-btn:hover { background: #f0fdf4; color: #059669; }

        /* CHAT MAIN */
        .yp-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(5,150,105,0.1);
            border: 1px solid #d1fae5;
            overflow: hidden;
        }

        .yp-chat-header {
            padding: 14px 20px;
            border-bottom: 1px solid #f0fdf4;
            background: linear-gradient(to right, #f0fdf4, #fff);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .module-name-display {
            font-size: 14px;
            font-weight: 600;
            color: #059669;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #messages {
            flex: 1;
            overflow-y: auto;
            padding: 24px 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            scroll-behavior: smooth;
            background: #fafffe;
        }

        .msg-user { display: flex; justify-content: flex-end; }
        .msg-bot { display: flex; justify-content: flex-start; align-items: flex-start; gap: 12px; }

        .bot-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #059669, #34d399);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(5,150,105,0.3);
        }

        .bubble-user {
            background: linear-gradient(135deg, #059669, #047857);
            color: #fff;
            padding: 12px 16px;
            border-radius: 20px 20px 4px 20px;
            font-size: 14px;
            line-height: 1.6;
            max-width: 68%;
            box-shadow: 0 4px 12px rgba(5,150,105,0.25);
        }

        .bubble-bot {
            background: #fff;
            color: #111827;
            padding: 14px 18px;
            border-radius: 4px 20px 20px 20px;
            font-size: 14px;
            line-height: 1.75;
            max-width: 80%;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
        }

        .bubble-bot h1 { font-size: 16px; font-weight: 700; margin: 14px 0 8px; color: #059669; }
        .bubble-bot h2 { font-size: 15px; font-weight: 700; margin: 14px 0 6px; color: #059669; border-bottom: 2px solid #d1fae5; padding-bottom: 4px; }
        .bubble-bot h3 { font-size: 14px; font-weight: 600; margin: 12px 0 6px; color: #047857; }
        .bubble-bot h4 { font-size: 13px; font-weight: 600; margin: 10px 0 4px; color: #374151; }
        .bubble-bot ul, .bubble-bot ol { padding-left: 20px; margin: 8px 0; }
        .bubble-bot li { margin-bottom: 4px; }
        .bubble-bot table { width: 100%; border-collapse: collapse; font-size: 12px; margin: 12px 0; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
        .bubble-bot th { background: linear-gradient(135deg,#059669,#047857); color: #fff; padding: 8px 12px; text-align: left; font-weight: 600; font-size: 12px; }
        .bubble-bot td { padding: 7px 12px; border-bottom: 1px solid #f0fdf4; }
        .bubble-bot tr:nth-child(even) td { background: #f0fdf4; }
        .bubble-bot tr:last-child td { border-bottom: none; }
        .bubble-bot strong { font-weight: 700; color: #065f46; }
        .bubble-bot p { margin-bottom: 8px; }
        .bubble-bot p:last-child { margin-bottom: 0; }
        .bubble-bot blockquote { border-left: 3px solid #059669; padding-left: 12px; color: #6b7280; margin: 8px 0; font-style: italic; }
        .bubble-bot code { background: #f0fdf4; padding: 2px 6px; border-radius: 4px; font-size: 12px; color: #065f46; }
        .bubble-bot hr { border: none; border-top: 2px solid #d1fae5; margin: 12px 0; }

        .typing-bubble {
            background: #fff;
            padding: 14px 18px;
            border-radius: 4px 20px 20px 20px;
            display: flex;
            gap: 6px;
            align-items: center;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
        }

        .typing-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #059669;
            animation: bounce 1.2s infinite;
        }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes bounce { 0%,80%,100%{transform:scale(0.6);opacity:0.4} 40%{transform:scale(1);opacity:1} }

        .yp-input-area {
            padding: 14px 20px;
            border-top: 1px solid #f0fdf4;
            background: #fff;
            flex-shrink: 0;
        }

        .yp-input-row { display: flex; gap: 10px; align-items: flex-end; }

        #user-input {
            flex: 1;
            resize: none;
            border: 2px solid #d1fae5;
            border-radius: 14px;
            padding: 12px 16px;
            font-size: 14px;
            font-family: inherit;
            line-height: 1.5;
            outline: none;
            transition: all 0.2s;
            max-height: 120px;
            background: #fafffe;
            color: #111827;
        }

        #user-input:focus {
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5,150,105,0.1);
            background: #fff;
        }

        #user-input::placeholder { color: #9ca3af; }

        #send-btn {
            padding: 12px 22px;
            background: linear-gradient(135deg, #059669, #047857);
            color: #fff;
            border: none;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(5,150,105,0.3);
        }

        #send-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(5,150,105,0.4);
        }

        #send-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        .yp-hint { font-size: 11px; color: #9ca3af; margin-top: 8px; }

        .welcome-msg {
            text-align: center;
            color: #9ca3af;
            margin: auto;
            padding: 40px 20px;
        }

        .welcome-msg .w-icon { font-size: 56px; margin-bottom: 16px; }

        .welcome-msg h3 {
            font-size: 20px;
            font-weight: 700;
            color: #059669;
            margin-bottom: 10px;
        }

        .welcome-msg p { font-size: 14px; line-height: 1.7; color: #6b7280; }
        .welcome-msg em { color: #059669; font-style: normal; font-weight: 600; }

        .welcome-steps {
            display: flex;
            gap: 16px;
            justify-content: center;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .welcome-step {
            background: #f0fdf4;
            border: 1px solid #d1fae5;
            border-radius: 12px;
            padding: 14px 18px;
            text-align: center;
            max-width: 160px;
        }

        .welcome-step .step-num {
            width: 28px; height: 28px;
            background: #059669;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            margin: 0 auto 8px;
        }

        .welcome-step p { font-size: 12px; color: #374151; line-height: 1.4; margin: 0; }

        .section-divider {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #a7f3d0;
            padding: 8px 4px 4px;
        }

        .export-btn-style {
            font-size: 12px;
            color: #059669;
            background: #f0fdf4;
            border: 2px solid #a7f3d0;
            border-radius: 10px;
            padding: 6px 14px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }

        .export-btn-style:hover {
            background: #d1fae5;
            transform: translateY(-1px);
        }

        .new-chat-btn-style {
            font-size: 12px;
            color: #6b7280;
            background: none;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 6px 14px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }

        .new-chat-btn-style:hover { border-color: #d1d5db; color: #374151; }

        /* Scrollbar personalizado */
        #messages::-webkit-scrollbar { width: 4px; }
        #messages::-webkit-scrollbar-track { background: transparent; }
        #messages::-webkit-scrollbar-thumb { background: #a7f3d0; border-radius: 4px; }
        .yp-sidebar::-webkit-scrollbar { width: 4px; }
        .yp-sidebar::-webkit-scrollbar-thumb { background: #a7f3d0; border-radius: 4px; }
    </style>

    <div class="yp-layout">

        {{-- Sidebar --}}
        <div class="yp-sidebar">
            <div class="sidebar-card">
                <div class="sidebar-title">📄 ¿Qué documento necesitas?</div>
                <div id="doc-list">
                    <div style="font-size:12px;color:#9ca3af;line-height:1.6;padding:4px;">
                        Selecciona tu área en la barra superior para ver los documentos disponibles.
                    </div>
                </div>
            </div>

            @if($sessions->count())
            <div class="sidebar-card">
                <div class="sidebar-title">🕐 Recientes</div>
                @foreach($sessions as $session)
                <button onclick="loadSession({{ $session->id }}, '{{ $session->module }}')"
                    class="recent-btn" title="{{ $session->title }}">
                    📄 {{ Str::limit($session->title ?? 'Sin título', 30) }}
                </button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Chat principal --}}
        <div class="yp-main">
            <div class="yp-chat-header">
                <div class="module-name-display">
                    <span id="module-icon-display">🌱</span>
                    <span id="current-module-name">Selecciona un documento para comenzar</span>
                </div>
                <div style="display:flex;gap:8px;align-items:center;">
                    <button id="export-btn" onclick="exportWord()"
                        class="export-btn-style" style="display:none;">
                        ⬇ Exportar Word
                    </button>
                    <button onclick="newChat()" class="new-chat-btn-style">
                        ✨ Nueva sesión
                    </button>
                </div>
            </div>

            <div id="messages">
                <div class="welcome-msg">
                    <div class="w-icon">🌱</div>
                    <h3>¡Bienvenido a YachaPlanner!</h3>
                    <p>Tu asistente pedagógico STEAM para Huancavelica</p>
                    <div class="welcome-steps">
                        <div class="welcome-step">
                            <div class="step-num">1</div>
                            <p>Configura tu contexto en la barra verde superior</p>
                        </div>
                        <div class="welcome-step">
                            <div class="step-num">2</div>
                            <p>Elige el documento que necesitas en el panel izquierdo</p>
                        </div>
                        <div class="welcome-step">
                            <div class="step-num">3</div>
                            <p>La IA generará una propuesta personalizada para ti</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="yp-input-area">
                <div class="yp-input-row">
                    <textarea id="user-input" rows="2"
                        placeholder="Describe la situación o problema que quieres trabajar con tus estudiantes..."
                        onkeydown="handleKey(event)"></textarea>
                    <button id="send-btn" onclick="sendMessage()">Enviar ➤</button>
                </div>
                <div class="yp-hint">
                    Documento activo: <span id="module-label" style="color:#059669;font-weight:600;">ninguno</span> ·
                    Enter para enviar · Shift+Enter para nueva línea
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/9.1.6/marked.min.js"></script>
    <script>
        let currentModule = null;
        let currentSessionId = null;
        let equipoSeleccionado = '';

        marked.setOptions({ breaks: true, gfm: true });

        // ===== DOCUMENTOS POR ÁREA =====
        const documentosPorArea = {
            'Todas las áreas (primaria)': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',      desc:'Secuencia didáctica completa',          tag:'basica' },
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',     desc:'Planificación de todo el bimestre',     tag:'basica' },
                { slug:'abp',       icon:'🔬', name:'Proyecto STEAM / ABP',       desc:'Proyecto interdisciplinario',           tag:'inter'  },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',      desc:'Criterios AD/A/B/C',                   tag:'basica' },
            ],
            'Matemática': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',      desc:'Resolución de problemas contextualizados', tag:'basica' },
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',     desc:'Secuencia de competencias matemáticas',    tag:'basica' },
                { slug:'abp',       icon:'📐', name:'Proyecto Matemático',        desc:'Estadística comunitaria, diseño, economía',tag:'inter'  },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',      desc:'Criterios por competencia matemática',     tag:'basica' },
            ],
            'Comunicación': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',      desc:'Comprensión y producción de textos',       tag:'basica' },
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',     desc:'Secuencia de competencias comunicativas',  tag:'basica' },
                { slug:'abp',       icon:'📰', name:'Proyecto Comunicativo',      desc:'Periódico, podcast, feria del libro',      tag:'inter'  },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',      desc:'Criterios de producción y comprensión',    tag:'basica' },
            ],
            'Ciencia y Tecnología': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Indagación',       desc:'Ciclo 5E contextualizado al entorno andino',tag:'steam' },
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',     desc:'Secuencia de competencias científicas',     tag:'basica'},
                { slug:'abp',       icon:'🔬', name:'Proyecto STEAM',             desc:'Agua, biodiversidad, salud, energía',       tag:'steam' },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Indagación',      desc:'Criterios del proceso científico',          tag:'steam' },
            ],
            'Personal Social': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',      desc:'Identidad, ciudadanía, convivencia',        tag:'basica'},
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',     desc:'Secuencia de competencias sociales',        tag:'basica'},
                { slug:'abp',       icon:'🏘️', name:'Proyecto Ciudadano',         desc:'Participación, derechos, historia local',   tag:'inter' },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',      desc:'Criterios de convivencia y ciudadanía',     tag:'basica'},
            ],
            'Historia, Geografía y Economía': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',      desc:'Historia local, territorio, economía andina',tag:'basica'},
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',     desc:'Secuencia histórico-social',                 tag:'basica'},
                { slug:'abp',       icon:'🗺️', name:'Proyecto Territorial',       desc:'Mapeo comunitario, economía local',          tag:'inter' },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',      desc:'Criterios de pensamiento histórico',         tag:'basica'},
            ],
            'Desarrollo Personal y Cívica': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',      desc:'Identidad, ética, participación cívica',    tag:'basica'},
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',     desc:'Secuencia de competencias ciudadanas',      tag:'basica'},
                { slug:'abp',       icon:'🤝', name:'Proyecto de Liderazgo',      desc:'Mediación, derechos, gobierno estudiantil', tag:'inter' },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',      desc:'Criterios de ciudadanía activa',            tag:'basica'},
            ],
            'Arte y Cultura': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',      desc:'Expresión artística con identidad andina',  tag:'basica'},
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',     desc:'Secuencia de competencias artísticas',      tag:'basica'},
                { slug:'abp',       icon:'🎨', name:'Proyecto Arte-STEAM',        desc:'Diseño, danza, música como puente STEAM',   tag:'steam' },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',      desc:'Criterios de expresión y apreciación',      tag:'basica'},
            ],
            'Educación Física': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',      desc:'Movimiento, salud y bienestar andino',      tag:'basica'},
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',     desc:'Secuencia de competencias motrices',        tag:'basica'},
                { slug:'abp',       icon:'🏃', name:'Proyecto Salud Comunitaria', desc:'Biomecánica, nutrición, bienestar rural',   tag:'steam' },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Desempeño',       desc:'Criterios de competencia motriz y actitud', tag:'basica'},
            ],
            'Inglés': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',      desc:'Comunicación en inglés contextualizada',    tag:'basica'},
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',     desc:'Secuencia de competencias comunicativas',   tag:'basica'},
                { slug:'abp',       icon:'🌐', name:'Proyecto Global',            desc:'Ciencia, tecnología y comunicación global', tag:'inter' },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',      desc:'Criterios de comunicación oral y escrita',  tag:'basica'},
            ],
            'Educación para el Trabajo': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',      desc:'Competencias técnicas y emprendimiento',    tag:'basica'},
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',     desc:'Secuencia de competencias laborales',       tag:'basica'},
                { slug:'abp',       icon:'💼', name:'Proyecto de Emprendimiento', desc:'Microempresa, economía local, tecnología',  tag:'steam' },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',      desc:'Criterios de desempeño técnico',            tag:'basica'},
            ],
            'Educación Religiosa': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',      desc:'Valores, ética, espiritualidad andina',     tag:'basica'},
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',     desc:'Secuencia de competencias espirituales',    tag:'basica'},
                { slug:'abp',       icon:'🕊️', name:'Proyecto de Valores',        desc:'Ética comunitaria, interculturalidad',      tag:'inter' },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',      desc:'Criterios de reflexión ética',              tag:'basica'},
            ],
        };

        const tagLabels = {
            steam:  { label: '⚗️ STEAM',           class: 'tag-steam' },
            inter:  { label: '🔗 Interdisciplinar', class: 'tag-inter' },
            basica: { label: '📋 Básico',           class: 'tag-basica' },
        };

        function updateDocumentos() {
            const area = document.getElementById('ctx-area-docente').value;
            const docList = document.getElementById('doc-list');
            if (!area) {
                docList.innerHTML = '<div style="font-size:12px;color:#9ca3af;line-height:1.6;padding:4px;">Selecciona tu área para ver los documentos disponibles.</div>';
                return;
            }
            const docs = documentosPorArea[area] || documentosPorArea['Todas las áreas (primaria)'];
            docList.innerHTML = docs.map(d => {
                const tag = tagLabels[d.tag];
                return `<button onclick="selectModulo('${d.slug}','${d.name}','${d.icon}')" id="btn-${d.slug}" class="doc-btn">
                    <div class="doc-btn-header"><span>${d.icon}</span><span>${d.name}</span></div>
                    <div class="doc-desc">${d.desc}</div>
                    <span class="doc-tag ${tag.class}">${tag.label}</span>
                </button>`;
            }).join('');
        }

        // ===== CONTEXTO =====
        const gradosPrimaria = ['1° Primaria','2° Primaria','3° Primaria','4° Primaria','5° Primaria','6° Primaria'];
        const gradosSecundaria = ['1° Secundaria','2° Secundaria','3° Secundaria','4° Secundaria','5° Secundaria'];

        const fortalezasPorArea = {
            'Todas las áreas (primaria)': '✅ Como tutor integras todas las áreas. Ventaja enorme para proyectos.',
            'Matemática': '📐 Estadística comunitaria, diseño y economía local son tu laboratorio natural.',
            'Comunicación': '📖 Articulas todo proyecto: investigación, debate, producción y presentación.',
            'Ciencia y Tecnología': '🔬 El entorno andino es tu laboratorio. ABP e indagación son tu metodología natural.',
            'Personal Social': '🤝 Conectas proyectos con identidad, ciudadanía e historia local.',
            'Historia, Geografía y Economía': '🗺️ El territorio, la economía y la historia andina son tu contenido vivo.',
            'Desarrollo Personal y Cívica': '🏛️ Liderazgo, derechos y participación ciudadana son tu fortaleza.',
            'Arte y Cultura': '🎨 Danza, música y diseño andino como puente hacia el STEAM.',
            'Educación Física': '🏃 Salud andina, biomecánica y bienestar comunitario — tu área puede ser el núcleo.',
            'Inglés': '🌐 Comunicación global, tecnología y acceso a ciencia internacional.',
            'Educación para el Trabajo': '💼 Emprendimiento y economía productiva local son tu motor.',
            'Educación Religiosa': '🕊️ Valores, ética comunitaria e interculturalidad andina.',
        };

        const infoMetodologias = {
            'ABP': 'ABP: Pregunta esencial → investigación → producto final real.',
            'ABR': 'ABR: Reto concreto → solución tecnológica o comunitaria.',
            'Design Thinking': 'DT: Empatizar → Definir → Idear → Prototipar → Evaluar.',
            'STEAM integrado': 'STEAM: Ciencia + Tecnología + Ingeniería + Arte + Matemática.',
            'Indagación científica 5E': '5E: Enganche → Exploración → Explicación → Elaboración → Evaluación.',
            'Aprendizaje por descubrimiento': 'Descubrimiento: El estudiante construye conocimiento explorando.',
            'Estudio de casos': 'Casos: Análisis de situaciones reales para pensamiento crítico.',
            'Aprendizaje cooperativo': 'Cooperativo: Roles definidos + interdependencia positiva.',
            'Aula invertida': 'Flipped: Contenidos en casa → práctica y debate en aula.',
            'Tutoría entre pares': 'Pares: Estudiantes avanzados apoyan a sus compañeros.',
            'Aprendizaje-Servicio': 'Servicio: Aprendizaje + impacto real en la comunidad andina.',
            'Etnociencia': 'Etnociencia: Saberes quechuas + ciencia occidental.',
            'Aprendizaje situado': 'Situado: Chacra, río, feria, municipio como aula.',
        };

        const alertasEquipo = {
            solo:      { primaria: '✅ En primaria integras todo tú solo.', secundaria: '💡 La IA diseñará para tu área y sugerirá cómo conectar con colegas.' },
            'con-otro':{ primaria: '👥 Dos docentes → proyecto más rico.',   secundaria: '👥 Perfecto para ABP o Design Thinking entre 2 áreas.' },
            equipo:    { primaria: '🏫 Proyecto escolar transformador.',       secundaria: '🏫 STEAM interdisciplinario — el modelo más potente.' },
        };

        function updateGrados() {
            const nivel = document.getElementById('ctx-nivel').value;
            const sel = document.getElementById('ctx-grado');
            sel.innerHTML = '<option value="">— Seleccionar —</option>';
            (nivel === 'Primaria' ? gradosPrimaria : nivel === 'Secundaria' ? gradosSecundaria : [])
                .forEach(g => { const o = document.createElement('option'); o.value = o.textContent = g; sel.appendChild(o); });
            if (equipoSeleccionado) updateAlertaEquipo(equipoSeleccionado);
        }

        function selectEquipo(el, valor) {
            equipoSeleccionado = valor;
            document.getElementById('ctx-equipo').value = valor;
            ['eq-solo','eq-con-otro','eq-equipo'].forEach(id => {
                const b = document.getElementById(id);
                b.style.borderColor = 'rgba(255,255,255,0.4)';
                b.style.background = 'transparent';
                b.style.color = '#fff';
            });
            el.style.borderColor = '#fff';
            el.style.background = 'rgba(255,255,255,0.25)';
            el.style.color = '#fff';
            updateAlertaEquipo(valor);
        }

        function updateAlertaEquipo(valor) {
            const nivel = document.getElementById('ctx-nivel').value;
            const div = document.getElementById('alerta-equipo');
            const key = nivel === 'Secundaria' ? 'secundaria' : 'primaria';
            const texto = alertasEquipo[valor]?.[key] || '';
            if (texto) { div.textContent = texto; div.style.display = 'block'; }
            else div.style.display = 'none';
        }

        function checkAreaDocente() {
            const area = document.getElementById('ctx-area-docente').value;
            const div = document.getElementById('info-area-docente');
            if (area && fortalezasPorArea[area]) { div.textContent = fortalezasPorArea[area]; div.style.display = 'block'; }
            else div.style.display = 'none';
        }

        function checkMetodologia() {
            const met = document.getElementById('ctx-metodologia').value;
            const div = document.getElementById('info-metodologia');
            if (met && infoMetodologias[met]) { div.textContent = infoMetodologias[met]; div.style.display = 'block'; }
            else div.style.display = 'none';
        }

        function getContextoPedagogico() {
            const nivel  = document.getElementById('ctx-nivel').value;
            const grado  = document.getElementById('ctx-grado').value;
            const area   = document.getElementById('ctx-area-docente').value;
            const equipo = document.getElementById('ctx-equipo').value;
            const met    = document.getElementById('ctx-metodologia').value;
            let ctx = '';
            if (nivel)  ctx += `Nivel: ${nivel}. `;
            if (grado)  ctx += `Grado: ${grado}. `;
            if (area)   ctx += `Área del docente: ${area}. `;
            if (equipo === 'solo')     ctx += 'Docente trabaja solo. ';
            if (equipo === 'con-otro') ctx += 'Coordina con otro docente. ';
            if (equipo === 'equipo')   ctx += 'Equipo docente interdisciplinario. ';
            if (met)    ctx += `Metodología: ${met}. `;
            if (!met)   ctx += 'Sugiere la metodología más apropiada. ';
            if (ctx)    ctx += 'Sugiere áreas que se articulan naturalmente. ';
            return ctx;
        }

        // ===== MÓDULOS =====
        function selectModulo(slug, name, icon = '📄') {
            currentModule = slug;
            currentSessionId = null;
            document.querySelectorAll('.doc-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('btn-' + slug)?.classList.add('active');
            document.getElementById('export-btn').style.display = 'none';
            document.getElementById('module-label').textContent = name;
            document.getElementById('current-module-name').textContent = name;
            document.getElementById('module-icon-display').textContent = icon;

            const nivel  = document.getElementById('ctx-nivel').value;
            const grado  = document.getElementById('ctx-grado').value;
            const area   = document.getElementById('ctx-area-docente').value;
            const equipo = document.getElementById('ctx-equipo').value;
            const met    = document.getElementById('ctx-metodologia').value;

            if (nivel || area || equipo) {
                generarSugerencia(slug, name, nivel, grado, area, equipo, met);
            } else {
                document.getElementById('messages').innerHTML = `
                    <div class="welcome-msg">
                        <div class="w-icon">${icon}</div>
                        <h3>${name}</h3>
                        <p>Configura tu contexto en la barra superior para una propuesta personalizada,<br>o describe directamente la situación que quieres trabajar.</p>
                    </div>`;
            }
            document.getElementById('user-input').focus();
        }

        async function generarSugerencia(slug, name, nivel, grado, area, equipo, met) {
            document.getElementById('messages').innerHTML = '';
            showTyping();

            const prompt = `Eres YachaPlanner, asistente pedagógico STEAM para docentes de Huancavelica, Perú.

Un docente configuró este contexto:
- Nivel: ${nivel || 'no especificado'}
- Grado: ${grado || 'no especificado'}
- Área del docente: ${area || 'no especificada'}
- Forma de trabajo: ${equipo === 'solo' ? 'trabaja solo' : equipo === 'con-otro' ? 'coordina con otro docente' : equipo === 'equipo' ? 'equipo docente interdisciplinario' : 'no especificado'}
- Metodología preferida: ${met || 'no especificada — sugiere la más apropiada'}
- Documento que necesita: ${name}

En 3-4 líneas conversacionales y cálidas:
1. Saluda reconociendo el área y su potencial en Huancavelica
2. Sugiere una situación o problema local concreto de Huancavelica como punto de partida
3. Si trabaja en equipo, sugiere qué otras áreas se articulan
4. Termina con una pregunta concreta para arrancar

Tono cálido, motivador. Sin markdown — párrafo conversacional en español.`;

            try {
                const res = await fetch('{{ route("chat.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ module: slug, message: prompt, session_id: null })
                });

                const reader = res.body.getReader();
                const decoder = new TextDecoder();
                let buffer = '', fullReply = '', botBubble = null;
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
                document.getElementById('messages').innerHTML = '<div class="welcome-msg"><p>Describe la situación que quieres trabajar.</p></div>';
            }
            document.getElementById('user-input').focus();
        }

        // ===== CHAT =====
        function newChat() {
            currentSessionId = null;
            document.getElementById('export-btn').style.display = 'none';
            document.getElementById('messages').innerHTML = `
                <div class="welcome-msg">
                    <div class="w-icon">✨</div>
                    <h3>Nueva sesión</h3>
                    <p>Describe la situación o problema que quieres trabajar con tus estudiantes.</p>
                </div>`;
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
                wrap.innerHTML = `<div class="bot-avatar">🌱</div><div class="bubble-bot">${marked.parse(content)}</div>`;
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
            if (!currentSessionId) { alert('Primero genera una respuesta para exportar.'); return; }
            window.open(`/export/word/${currentSessionId}`, '_blank');
        }

        async function sendMessage() {
            const input = document.getElementById('user-input');
            const text = input.value.trim();
            if (!text) return;
            if (!currentModule) { alert('Selecciona un documento primero'); return; }
            input.value = '';
            input.style.height = 'auto';
            document.getElementById('send-btn').disabled = true;
            addMessage('user', text);
            showTyping();

            try {
                const res = await fetch('{{ route("chat.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
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
                let buffer = '', fullReply = '', botBubble = null;
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
    </script>
</x-app-layout>