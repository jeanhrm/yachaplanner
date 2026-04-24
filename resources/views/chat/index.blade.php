<x-app-layout>
    <x-slot name="header" style="padding:0;">
        {{-- Barra de contexto pedagógico --}}
        <div style="background:#fff;border-bottom:1px solid #e5e7eb;padding:10px 24px;">
            <div style="max-width:1280px;margin:0 auto;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">

                {{-- Nivel --}}
                <div style="display:flex;align-items:center;gap:6px;">
                    <label style="font-size:11px;font-weight:600;color:#6b7280;white-space:nowrap;">Nivel</label>
                    <select id="ctx-nivel" onchange="updateGrados()" style="border:1px solid #e5e7eb;border-radius:6px;padding:5px 8px;font-size:13px;color:#374151;background:#fff;">
                        <option value="">— Seleccionar —</option>
                        <option value="Primaria">Primaria</option>
                        <option value="Secundaria">Secundaria</option>
                    </select>
                </div>

                <div style="width:1px;height:24px;background:#e5e7eb;"></div>

                {{-- Grado --}}
                <div style="display:flex;align-items:center;gap:6px;">
                    <label style="font-size:11px;font-weight:600;color:#6b7280;white-space:nowrap;">Grado</label>
                    <select id="ctx-grado" style="border:1px solid #e5e7eb;border-radius:6px;padding:5px 8px;font-size:13px;color:#374151;background:#fff;">
                        <option value="">— Primero nivel —</option>
                    </select>
                </div>

                <div style="width:1px;height:24px;background:#e5e7eb;"></div>

                {{-- Área del docente --}}
                <div style="display:flex;align-items:center;gap:6px;">
                    <label style="font-size:11px;font-weight:600;color:#6b7280;white-space:nowrap;">Tu área</label>
                    <select id="ctx-area-docente" onchange="updateDocumentos(); checkAreaDocente()" style="border:1px solid #e5e7eb;border-radius:6px;padding:5px 8px;font-size:13px;color:#374151;background:#fff;">
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

                <div style="width:1px;height:24px;background:#e5e7eb;"></div>

                {{-- ¿Con quién trabajas? --}}
                <div style="display:flex;align-items:center;gap:6px;">
                    <label style="font-size:11px;font-weight:600;color:#6b7280;white-space:nowrap;">Trabajo</label>
                    <div style="display:flex;gap:4px;">
                        <button onclick="selectEquipo(this,'solo')" id="eq-solo"
                            style="padding:5px 10px;border:1px solid #e5e7eb;border-radius:6px;font-size:12px;color:#374151;background:#fff;cursor:pointer;">
                            👤 Solo
                        </button>
                        <button onclick="selectEquipo(this,'con-otro')" id="eq-con-otro"
                            style="padding:5px 10px;border:1px solid #e5e7eb;border-radius:6px;font-size:12px;color:#374151;background:#fff;cursor:pointer;">
                            👥 Con colega
                        </button>
                        <button onclick="selectEquipo(this,'equipo')" id="eq-equipo"
                            style="padding:5px 10px;border:1px solid #e5e7eb;border-radius:6px;font-size:12px;color:#374151;background:#fff;cursor:pointer;">
                            🏫 Equipo
                        </button>
                    </div>
                    <input type="hidden" id="ctx-equipo" value="">
                </div>

                <div style="width:1px;height:24px;background:#e5e7eb;"></div>

                {{-- Metodología --}}
                <div style="display:flex;align-items:center;gap:6px;">
                    <label style="font-size:11px;font-weight:600;color:#6b7280;white-space:nowrap;">Metodología</label>
                    <select id="ctx-metodologia" onchange="checkMetodologia()" style="border:1px solid #e5e7eb;border-radius:6px;padding:5px 8px;font-size:13px;color:#374151;background:#fff;max-width:200px;">
                        <option value="">— Claude sugiere —</option>
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
                <div style="margin-left:auto;font-size:12px;color:#6b7280;white-space:nowrap;">
                    Créditos: <strong id="credits-count" style="color:#059669;">{{ auth()->user()->remainingCredits() }}</strong>
                </div>

            </div>

            {{-- Alertas contexto --}}
            <div id="ctx-alertas" style="max-width:1280px;margin:0 auto;margin-top:6px;display:flex;gap:8px;flex-wrap:wrap;">
                <div id="info-area-docente" style="display:none;background:#f0fdf4;border:1px solid #a7f3d0;border-radius:6px;padding:5px 10px;font-size:11px;color:#065f46;"></div>
                <div id="info-metodologia" style="display:none;background:#fffbeb;border:1px solid #fde68a;border-radius:6px;padding:5px 10px;font-size:11px;color:#92400e;"></div>
                <div id="alerta-equipo" style="display:none;background:#eff6ff;border:1px solid #bfdbfe;border-radius:6px;padding:5px 10px;font-size:11px;color:#1e40af;"></div>
            </div>
        </div>
    </x-slot>

    <style>
        * { box-sizing: border-box; }
        .yp-layout { display: flex; gap: 0; height: calc(100vh - 130px); max-width: 1280px; margin: 0 auto; }
        .yp-sidebar { width: 240px; flex-shrink: 0; background: #f9fafb; border-right: 1px solid #e5e7eb; overflow-y: auto; padding: 16px 12px; display: flex; flex-direction: column; gap: 8px; }
        .sidebar-title { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 4px; padding: 0 4px; }
        .doc-btn { width: 100%; text-align: left; padding: 10px 12px; border-radius: 8px; font-size: 13px; color: #374151; background: transparent; border: none; cursor: pointer; transition: all 0.15s; display: flex; flex-direction: column; gap: 2px; }
        .doc-btn:hover { background: #f0fdf4; color: #059669; }
        .doc-btn.active { background: #059669; color: #fff; }
        .doc-btn.active .doc-desc { color: #d1fae5; }
        .doc-btn-header { display: flex; align-items: center; gap: 8px; font-weight: 500; }
        .doc-desc { font-size: 11px; color: #9ca3af; padding-left: 22px; }
        .doc-tag { display: inline-block; margin-top: 4px; margin-left: 22px; font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 10px; }
        .tag-steam { background: #d1fae5; color: #065f46; }
        .tag-inter { background: #ede9fe; color: #5b21b6; }
        .tag-basica { background: #f3f4f6; color: #6b7280; }
        .yp-main { flex: 1; display: flex; flex-direction: column; background: #fff; overflow: hidden; }
        .yp-chat-header { padding: 10px 16px; border-bottom: 1px solid #f3f4f6; background: #fafafa; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
        .yp-module-label { font-size: 13px; font-weight: 500; color: #374151; }
        #messages { flex: 1; overflow-y: auto; padding: 20px 16px; display: flex; flex-direction: column; gap: 16px; scroll-behavior: smooth; }
        .msg-user { display: flex; justify-content: flex-end; }
        .msg-bot { display: flex; justify-content: flex-start; align-items: flex-start; gap: 10px; }
        .bot-avatar { width: 32px; height: 32px; border-radius: 50%; background: #d1fae5; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
        .bubble-user { background: #059669; color: #fff; padding: 10px 14px; border-radius: 18px 18px 4px 18px; font-size: 14px; line-height: 1.6; max-width: 70%; }
        .bubble-bot { background: #f3f4f6; color: #111827; padding: 12px 16px; border-radius: 4px 18px 18px 18px; font-size: 14px; line-height: 1.7; max-width: 82%; }
        .bubble-bot h2,.bubble-bot h3 { font-size: 14px; font-weight: 600; margin: 12px 0 6px; color: #059669; }
        .bubble-bot h4 { font-size: 13px; font-weight: 600; margin: 10px 0 4px; color: #374151; }
        .bubble-bot ul,.bubble-bot ol { padding-left: 18px; margin: 6px 0; }
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
        .welcome-msg { text-align: center; color: #9ca3af; margin: auto; padding: 40px 20px; }
        .welcome-msg .w-icon { font-size: 48px; margin-bottom: 12px; }
        .welcome-msg h3 { font-size: 16px; font-weight: 600; color: #374151; margin-bottom: 8px; }
        .welcome-msg p { font-size: 14px; line-height: 1.6; }
        .welcome-msg em { color: #059669; font-style: normal; font-weight: 500; }
        .section-divider { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #d1d5db; padding: 8px 4px 4px; }
    </style>

    <div class="yp-layout">

        {{-- Sidebar: Documentos --}}
        <div class="yp-sidebar">
            <div class="sidebar-title">¿Qué documento necesitas?</div>
            <div id="doc-list">
                {{-- Se genera dinámicamente por JS según área --}}
                <div style="font-size:12px;color:#9ca3af;padding:8px 4px;line-height:1.5;">
                    Selecciona tu área en la barra superior para ver los documentos disponibles.
                </div>
            </div>
        </div>

        {{-- Chat principal --}}
        <div class="yp-main">
            <div class="yp-chat-header">
                <span id="current-module-name" class="yp-module-label">Selecciona un documento para comenzar</span>
                <div style="display:flex;gap:8px;align-items:center;">
                    <button id="export-btn" onclick="exportWord()"
                        style="display:none;font-size:12px;color:#1a7a4a;background:#f0fdf4;border:1px solid #a7f3d0;border-radius:6px;padding:5px 12px;cursor:pointer;">
                        ⬇ Exportar Word
                    </button>
                    <button onclick="newChat()"
                        style="font-size:12px;color:#9ca3af;background:none;border:1px solid #e5e7eb;border-radius:6px;padding:4px 10px;cursor:pointer;">
                        + Nueva sesión
                    </button>
                </div>
            </div>

            <div id="messages">
                <div class="welcome-msg">
                    <div class="w-icon">🌱</div>
                    <h3>Bienvenido a YachaPlanner</h3>
                    <p>
                        1. Configura tu contexto en la barra superior<br>
                        2. Elige el documento que necesitas en el panel izquierdo<br>
                        3. Claude generará una propuesta personalizada para ti
                    </p>
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
                    Documento: <span id="module-label" style="color:#059669;font-weight:500;">ninguno seleccionado</span> ·
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
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',     desc:'Secuencia didáctica completa',         tag:'basica' },
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',    desc:'Planificación de todo el bimestre',    tag:'basica' },
                { slug:'abp',       icon:'🔬', name:'Proyecto STEAM / ABP',      desc:'Proyecto interdisciplinario',          tag:'inter'  },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',     desc:'Criterios AD/A/B/C',                  tag:'basica' },
            ],
            'Matemática': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',     desc:'Resolución de problemas contextualizados', tag:'basica' },
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',    desc:'Secuencia de competencias matemáticas',    tag:'basica' },
                { slug:'abp',       icon:'🔬', name:'Proyecto Matemático',       desc:'Estadística comunitaria, diseño, economía',tag:'inter'  },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',     desc:'Criterios por competencia matemática',     tag:'basica' },
            ],
            'Comunicación': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',     desc:'Comprensión y producción de textos',       tag:'basica' },
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',    desc:'Secuencia de competencias comunicativas',   tag:'basica' },
                { slug:'abp',       icon:'📰', name:'Proyecto Comunicativo',     desc:'Periódico escolar, podcast, feria del libro',tag:'inter' },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',     desc:'Criterios de producción y comprensión',     tag:'basica' },
            ],
            'Ciencia y Tecnología': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Indagación',      desc:'Ciclo 5E contextualizado al entorno andino', tag:'steam' },
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',    desc:'Secuencia de competencias científicas',      tag:'basica' },
                { slug:'abp',       icon:'🔬', name:'Proyecto STEAM',            desc:'Agua, biodiversidad, salud, energía',        tag:'steam' },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Indagación',     desc:'Criterios del proceso científico',           tag:'steam' },
            ],
            'Personal Social': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',     desc:'Identidad, ciudadanía, convivencia',         tag:'basica' },
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',    desc:'Secuencia de competencias sociales',         tag:'basica' },
                { slug:'abp',       icon:'🏘️', name:'Proyecto Ciudadano',        desc:'Participación, derechos, historia local',    tag:'inter'  },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',     desc:'Criterios de convivencia y ciudadanía',      tag:'basica' },
            ],
            'Historia, Geografía y Economía': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',     desc:'Historia local, territorio, economía andina',tag:'basica' },
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',    desc:'Secuencia de competencias histórico-sociales',tag:'basica'},
                { slug:'abp',       icon:'🗺️', name:'Proyecto Territorial',      desc:'Mapeo comunitario, economía local, historia',tag:'inter' },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',     desc:'Criterios de pensamiento histórico',         tag:'basica' },
            ],
            'Desarrollo Personal y Cívica': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',     desc:'Identidad, ética, participación cívica',     tag:'basica' },
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',    desc:'Secuencia de competencias ciudadanas',       tag:'basica' },
                { slug:'abp',       icon:'🤝', name:'Proyecto de Liderazgo',     desc:'Mediación, derechos, gobierno estudiantil',  tag:'inter'  },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',     desc:'Criterios de ciudadanía activa',             tag:'basica' },
            ],
            'Arte y Cultura': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',     desc:'Expresión artística con identidad andina',   tag:'basica' },
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',    desc:'Secuencia de competencias artísticas',       tag:'basica' },
                { slug:'abp',       icon:'🎨', name:'Proyecto Arte-STEAM',       desc:'Diseño, danza, música como puente STEAM',   tag:'steam'  },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',     desc:'Criterios de expresión y apreciación',      tag:'basica' },
            ],
            'Educación Física': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',     desc:'Movimiento, salud y bienestar andino',      tag:'basica' },
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',    desc:'Secuencia de competencias motrices',        tag:'basica' },
                { slug:'abp',       icon:'🏃', name:'Proyecto Salud Comunitaria',desc:'Biomecánica, nutrición, bienestar rural',   tag:'steam'  },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Desempeño',      desc:'Criterios de competencia motriz y actitud',  tag:'basica' },
            ],
            'Inglés': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',     desc:'Comunicación en inglés contextualizada',     tag:'basica' },
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',    desc:'Secuencia de competencias comunicativas',    tag:'basica' },
                { slug:'abp',       icon:'🌐', name:'Proyecto Global',           desc:'Conexión con ciencia y tecnología global',   tag:'inter'  },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',     desc:'Criterios de comunicación oral y escrita',   tag:'basica' },
            ],
            'Educación para el Trabajo': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',     desc:'Competencias técnicas y emprendimiento',     tag:'basica' },
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',    desc:'Secuencia de competencias laborales',        tag:'basica' },
                { slug:'abp',       icon:'💼', name:'Proyecto de Emprendimiento',desc:'Microempresa, economía local, tecnología',   tag:'steam'  },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',     desc:'Criterios de desempeño técnico',             tag:'basica' },
            ],
            'Educación Religiosa': [
                { slug:'sesion',    icon:'📝', name:'Sesión de Aprendizaje',     desc:'Valores, ética, espiritualidad andina',      tag:'basica' },
                { slug:'bimestral', icon:'📅', name:'Programación Bimestral',    desc:'Secuencia de competencias espirituales',     tag:'basica' },
                { slug:'abp',       icon:'🕊️', name:'Proyecto de Valores',       desc:'Ética comunitaria, interculturalidad',       tag:'inter'  },
                { slug:'rubrica',   icon:'✅', name:'Rúbrica de Evaluación',     desc:'Criterios de reflexión ética',               tag:'basica' },
            ],
        };

        const tagLabels = {
            steam: { label: 'STEAM', class: 'tag-steam' },
            inter: { label: 'Interdisciplinar', class: 'tag-inter' },
            basica: { label: 'Básico', class: 'tag-basica' },
        };

        function updateDocumentos() {
            const area = document.getElementById('ctx-area-docente').value;
            const docList = document.getElementById('doc-list');

            if (!area) {
                docList.innerHTML = '<div style="font-size:12px;color:#9ca3af;padding:8px 4px;line-height:1.5;">Selecciona tu área para ver los documentos disponibles.</div>';
                return;
            }

            const docs = documentosPorArea[area] || documentosPorArea['Todas las áreas (primaria)'];
            docList.innerHTML = docs.map(d => {
                const tag = tagLabels[d.tag];
                return `
                    <button onclick="selectModulo('${d.slug}','${d.name}')"
                        id="btn-${d.slug}"
                        class="doc-btn">
                        <div class="doc-btn-header">
                            <span>${d.icon}</span>
                            <span>${d.name}</span>
                        </div>
                        <div class="doc-desc">${d.desc}</div>
                        <span class="doc-tag ${tag.class}">${tag.label}</span>
                    </button>`;
            }).join('');
        }

        // ===== CONTEXTO PEDAGÓGICO =====
        const gradosPrimaria = ['1° Primaria','2° Primaria','3° Primaria','4° Primaria','5° Primaria','6° Primaria'];
        const gradosSecundaria = ['1° Secundaria','2° Secundaria','3° Secundaria','4° Secundaria','5° Secundaria'];

        const fortalezasPorArea = {
            'Todas las áreas (primaria)': '✅ Como tutor integras todas las áreas. Tienes ventaja — no necesitas coordinar con nadie para hacer proyectos.',
            'Matemática': '📐 Matemática puede ser el núcleo de proyectos de medición, estadística comunitaria y economía local.',
            'Comunicación': '📖 Comunicación articula todo proyecto: investigación, presentación, debate y escritura del informe.',
            'Ciencia y Tecnología': '🔬 Ciencia es el motor del ABP y la indagación. El entorno andino es tu laboratorio natural.',
            'Personal Social': '🤝 Tu área conecta proyectos con identidad, ciudadanía e historia local huancavelicana.',
            'Historia, Geografía y Economía': '🗺️ Conectas el territorio, la economía local y la historia andina con situaciones reales.',
            'Desarrollo Personal y Cívica': '🏛️ Puedes liderar proyectos de participación ciudadana, derechos y liderazgo estudiantil.',
            'Arte y Cultura': '🎨 Arte es el puente entre la identidad andina y el STEAM. Danza, música y diseño son herramientas poderosas.',
            'Educación Física': '🏃 Ed. Física puede ser el núcleo de proyectos sobre salud andina, biomecánica y bienestar comunitario.',
            'Inglés': '🌐 Inglés conecta con proyectos de comunicación global, tecnología y acceso a información científica.',
            'Educación para el Trabajo': '💼 Tu área conecta directamente con emprendimiento y proyectos de economía productiva local.',
            'Educación Religiosa': '🕊️ Puedes liderar proyectos sobre valores, ética comunitaria e interculturalidad andina.',
        };

        const infoMetodologias = {
            'ABP': 'ABP: Parte de una pregunta esencial y un producto final. Los estudiantes investigan y proponen soluciones reales.',
            'ABR': 'ABR: Plantea un reto concreto. Conecta ciencia y tecnología con problemas reales de la comunidad.',
            'Design Thinking': 'Design Thinking: Empatizar → Definir → Idear → Prototipar → Evaluar. Centrado en las personas.',
            'STEAM integrado': 'STEAM: Integra Ciencia, Tecnología, Ingeniería, Arte y Matemática en un proyecto real.',
            'Indagación científica 5E': 'Indagación 5E: Enganche → Exploración → Explicación → Elaboración → Evaluación.',
            'Aprendizaje por descubrimiento': 'Por descubrimiento: El estudiante construye conocimiento explorando y cuestionando.',
            'Estudio de casos': 'Casos: Análisis de situaciones reales para desarrollar pensamiento crítico.',
            'Aprendizaje cooperativo': 'Cooperativo: Trabajo en equipo con roles definidos e interdependencia positiva.',
            'Aula invertida': 'Flipped: Contenidos en casa, el aula para práctica y debate.',
            'Tutoría entre pares': 'Tutoría: Estudiantes avanzados apoyan a sus compañeros.',
            'Aprendizaje-Servicio': 'Servicio: Aprendizaje curricular combinado con servicio real a la comunidad andina.',
            'Etnociencia': 'Etnociencia: Saberes andinos integrados con el currículo científico occidental.',
            'Aprendizaje situado': 'Situado: El aprendizaje ocurre en contextos reales: chacra, río, feria, municipio.',
        };

        const alertasEquipo = {
            solo: { primaria: '✅ En primaria integras varias áreas tú solo. Claude articulará las competencias.', secundaria: '💡 En secundaria trabajas tu área. Claude sugerirá cómo conectar con otros colegas.' },
            'con-otro': { primaria: '👥 Dos docentes pueden diseñar un proyecto más rico.', secundaria: '👥 Coordinación entre 2 áreas — perfecto para ABP o Design Thinking.' },
            equipo: { primaria: '🏫 Un equipo docente genera proyectos escolares transformadores.', secundaria: '🏫 Proyecto interdisciplinario — el modelo más potente para STEAM.' },
        };

        function updateGrados() {
            const nivel = document.getElementById('ctx-nivel').value;
            const gradoSelect = document.getElementById('ctx-grado');
            gradoSelect.innerHTML = '<option value="">— Seleccionar —</option>';
            const grados = nivel === 'Primaria' ? gradosPrimaria : nivel === 'Secundaria' ? gradosSecundaria : [];
            grados.forEach(g => {
                const opt = document.createElement('option');
                opt.value = g; opt.textContent = g;
                gradoSelect.appendChild(opt);
            });
            if (equipoSeleccionado) updateAlertaEquipo(equipoSeleccionado);
        }

        function selectEquipo(el, valor) {
            equipoSeleccionado = valor;
            document.getElementById('ctx-equipo').value = valor;
            ['eq-solo','eq-con-otro','eq-equipo'].forEach(id => {
                const btn = document.getElementById(id);
                btn.style.borderColor = '#e5e7eb';
                btn.style.background = '#fff';
                btn.style.color = '#374151';
            });
            el.style.borderColor = '#059669';
            el.style.background = '#f0fdf4';
            el.style.color = '#059669';
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
            const nivel      = document.getElementById('ctx-nivel').value;
            const grado      = document.getElementById('ctx-grado').value;
            const area       = document.getElementById('ctx-area-docente').value;
            const equipo     = document.getElementById('ctx-equipo').value;
            const met        = document.getElementById('ctx-metodologia').value;
            let ctx = '';
            if (nivel)  ctx += `Nivel: ${nivel}. `;
            if (grado)  ctx += `Grado: ${grado}. `;
            if (area)   ctx += `Área del docente: ${area}. `;
            if (equipo === 'solo')     ctx += 'El docente trabaja solo. ';
            if (equipo === 'con-otro') ctx += 'Coordina con otro docente (2 áreas). ';
            if (equipo === 'equipo')   ctx += 'Proyecto con equipo docente interdisciplinario. ';
            if (met)    ctx += `Metodología: ${met}. `;
            if (!met)   ctx += 'Sugiere la metodología más apropiada. ';
            if (ctx)    ctx += 'Sugiere qué áreas se articulan naturalmente. ';
            return ctx;
        }

        // ===== MÓDULOS =====
        function selectModulo(slug, name) {
            currentModule = slug;
            currentSessionId = null;
            document.querySelectorAll('.doc-btn').forEach(b => b.classList.remove('active'));
            const btn = document.getElementById('btn-' + slug);
            if (btn) btn.classList.add('active');
            document.getElementById('export-btn').style.display = 'none';
            document.getElementById('module-label').textContent = name;
            document.getElementById('current-module-name').textContent = name;

            const nivel     = document.getElementById('ctx-nivel').value;
            const grado     = document.getElementById('ctx-grado').value;
            const area      = document.getElementById('ctx-area-docente').value;
            const equipo    = document.getElementById('ctx-equipo').value;
            const met       = document.getElementById('ctx-metodologia').value;
            const tieneContexto = nivel || area || equipo;

            if (!tieneContexto) {
                document.getElementById('messages').innerHTML = `
                    <div class="welcome-msg">
                        <p>Seleccionaste <em>${name}</em>.</p>
                        <p style="margin-top:8px;">Configura tu contexto en la barra superior para una propuesta personalizada, o describe directamente la situación que quieres trabajar.</p>
                    </div>`;
            } else {
                generarSugerencia(slug, name, nivel, grado, area, equipo, met);
            }
            document.getElementById('user-input').focus();
        }

        async function generarSugerencia(slug, name, nivel, grado, area, equipo, met) {
            document.getElementById('messages').innerHTML = '';
            showTyping();

            const promptSugerencia = `Eres YachaPlanner, asistente pedagógico STEAM para docentes de Huancavelica, Perú.

Un docente configuró este contexto:
- Nivel: ${nivel || 'no especificado'}
- Grado: ${grado || 'no especificado'}
- Área del docente: ${area || 'no especificada'}
- Forma de trabajo: ${equipo === 'solo' ? 'trabaja solo' : equipo === 'con-otro' ? 'coordina con otro docente' : equipo === 'equipo' ? 'equipo docente interdisciplinario' : 'no especificado'}
- Metodología preferida: ${met || 'no especificada — sugiere la más apropiada'}
- Documento que necesita: ${name}

En 3-4 líneas conversacionales:
1. Valida el contexto con entusiasmo y menciona el potencial del área para hacer proyectos
2. Sugiere qué situación o problema local de Huancavelica podría ser el punto de partida
3. Si trabaja en equipo o con colega, sugiere qué otras áreas se articulan bien
4. Termina con una pregunta concreta para arrancar

Tono cálido, motivador, en español. Sin markdown ni listas — párrafo conversacional.`;

            try {
                const res = await fetch('{{ route("chat.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ module: slug, message: promptSugerencia, session_id: null })
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
            document.getElementById('messages').innerHTML = '<div class="welcome-msg"><p>Nueva sesión. Describe la situación que quieres trabajar.</p></div>';
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