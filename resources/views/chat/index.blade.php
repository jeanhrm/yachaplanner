<x-app-layout>
    <x-slot name="header" style="padding:0;">
        <div style="background:linear-gradient(135deg,#059669 0%,#047857 100%);padding:16px 24px;">
            <div style="max-width:1200px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                <div>
                    <h1 style="font-size:20px;font-weight:800;color:#fff;margin:0;">✍️ Planificador</h1>
                    <p style="font-size:14px;color:rgba(255,255,255,0.85);margin:2px 0 0;">
                        Responde unas preguntas sencillas y Yachaycito prepara tu documento contigo.
                    </p>
                </div>
                <a href="{{ route('credits.index') }}" title="Obtener más créditos"
                   style="background:rgba(255,255,255,0.15);border-radius:20px;padding:8px 16px;font-size:14px;color:#fff;white-space:nowrap;border:1px solid rgba(255,255,255,0.35);text-decoration:none;">
                    ⚡ <strong id="credits-count">{{ auth()->user()->remainingCredits() }}</strong> créditos · <u>Recargar</u>
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .yp-layout { max-width:1200px; margin:24px auto; padding:0 20px; display:grid; grid-template-columns:250px 1fr; gap:20px; align-items:start; font-size:15px; }
        @media (max-width: 860px) {
            .yp-layout { grid-template-columns:1fr; margin:16px auto; padding:0 12px; }
            .yp-sidebar { order:2; }
        }

        /* Sidebar */
        .sidebar-card { background:#fff; border-radius:16px; padding:16px; border:1px solid #d1fae5; box-shadow:0 2px 10px rgba(5,150,105,0.06); }
        .sidebar-title { font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#059669; margin:14px 0 8px; }
        .btn-new { width:100%; padding:12px; border:none; border-radius:12px; background:#059669; color:#fff; font-size:15px; font-weight:700; cursor:pointer; font-family:inherit; }
        .btn-new:hover { background:#047857; }
        .recent-btn { width:100%; text-align:left; padding:10px; border-radius:10px; font-size:14px; color:#4b5563; background:transparent; border:none; cursor:pointer; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; font-family:inherit; }
        .recent-btn:hover { background:#f0fdf4; color:#059669; }
        .recent-btn.active { background:#d1fae5; color:#047857; font-weight:600; }

        /* Tarjeta principal */
        .yp-main { background:#fff; border-radius:20px; border:1px solid #d1fae5; box-shadow:0 4px 20px rgba(5,150,105,0.08); overflow:hidden; min-height:560px; display:flex; flex-direction:column; }

        /* Asistente paso a paso */
        .wz-progress { padding:18px 28px 0; }
        .wz-progress-label { display:flex; justify-content:space-between; font-size:13px; color:#6b7280; margin-bottom:8px; font-weight:600; }
        .wz-bar { height:10px; background:#f3f4f6; border-radius:10px; overflow:hidden; }
        .wz-bar-fill { height:100%; background:linear-gradient(to right,#059669,#34d399); border-radius:10px; transition:width 0.3s; }
        .wz-body { padding:24px 28px; flex:1; }
        .wz-title { font-size:22px; font-weight:800; color:#111827; margin:0 0 6px; line-height:1.3; }
        .wz-help { font-size:15px; color:#6b7280; margin:0 0 20px; line-height:1.5; }
        .wz-q { font-size:16px; font-weight:700; color:#374151; margin:22px 0 4px; }
        .wz-q small { font-weight:500; color:#9ca3af; font-size:13px; }
        .wz-hint { font-size:13px; color:#6b7280; margin:0 0 10px; }
        .wz-footer { display:flex; justify-content:space-between; gap:12px; padding:16px 28px; border-top:1px solid #f3f4f6; background:#fafafa; }
        .wz-error { color:#b91c1c; background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:10px 14px; font-size:14px; margin-top:16px; display:none; }
        .wz-banner { background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; border-radius:12px; padding:10px 14px; font-size:14px; margin-bottom:16px; }

        .btn-back { padding:14px 22px; border:2px solid #e5e7eb; border-radius:12px; background:#fff; color:#4b5563; font-size:16px; font-weight:600; cursor:pointer; font-family:inherit; }
        .btn-next { padding:14px 28px; border:none; border-radius:12px; background:#059669; color:#fff; font-size:16px; font-weight:700; cursor:pointer; font-family:inherit; margin-left:auto; }
        .btn-next:hover { background:#047857; }
        .btn-next:disabled { background:#9ca3af; cursor:wait; }

        /* Opciones tocables */
        .chips { display:flex; flex-wrap:wrap; gap:10px; }
        .chip { min-height:48px; padding:10px 16px; border:2px solid #e5e7eb; border-radius:12px; background:#fff; color:#374151; font-size:15px; font-weight:600; cursor:pointer; font-family:inherit; transition:all 0.15s; display:inline-flex; align-items:center; gap:6px; }
        .chip:hover { border-color:#6ee7b7; background:#f0fdf4; }
        .chip.on { border-color:#059669; background:#d1fae5; color:#065f46; }
        .chip.on::after { content:'✓'; font-weight:800; }

        .cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:14px; }
        .card-opt { text-align:left; padding:20px; border:2px solid #e5e7eb; border-radius:16px; background:#fff; cursor:pointer; font-family:inherit; transition:all 0.15s; }
        .card-opt:hover { border-color:#6ee7b7; background:#f0fdf4; }
        .card-opt.on { border-color:#059669; background:#ecfdf5; box-shadow:0 0 0 3px rgba(5,150,105,0.15); }
        .card-opt .ic { font-size:32px; display:block; margin-bottom:8px; }
        .card-opt .nm { font-size:17px; font-weight:800; color:#111827; display:block; margin-bottom:4px; }
        .card-opt .ds { font-size:14px; color:#6b7280; line-height:1.45; display:block; }

        .wz-input, .wz-textarea { width:100%; border:2px solid #e5e7eb; border-radius:12px; padding:12px 14px; font-size:16px; font-family:inherit; color:#111827; box-sizing:border-box; }
        .wz-input:focus, .wz-textarea:focus { outline:none; border-color:#059669; box-shadow:0 0 0 3px rgba(5,150,105,0.12); }
        .wz-textarea { min-height:110px; resize:vertical; line-height:1.5; }
        .examples { display:flex; flex-wrap:wrap; gap:8px; margin-top:10px; }
        .example { font-size:13px; padding:6px 12px; border-radius:20px; border:1px dashed #6ee7b7; background:#f0fdf4; color:#047857; cursor:pointer; font-family:inherit; }

        .summary { background:#f9fafb; border:1px solid #e5e7eb; border-radius:14px; padding:14px 18px; margin-top:22px; }
        .summary-row { display:flex; gap:8px; font-size:14px; padding:4px 0; color:#374151; }
        .summary-row b { min-width:110px; color:#6b7280; font-weight:600; }

        /* Vista del documento */
        .res-header { display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; padding:14px 22px; border-bottom:1px solid #f3f4f6; }
        .res-title { font-size:17px; font-weight:800; color:#111827; display:flex; align-items:center; gap:8px; }
        .btn-export { padding:10px 18px; border:none; border-radius:10px; background:#059669; color:#fff; font-size:15px; font-weight:700; cursor:pointer; font-family:inherit; }
        .btn-export:hover { background:#047857; }
        #messages { flex:1; padding:20px 22px; overflow-y:auto; max-height:65vh; display:flex; flex-direction:column; gap:16px; }
        .msg-user { display:flex; justify-content:flex-end; }
        .bubble-user { max-width:80%; background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; border-radius:16px 16px 4px 16px; padding:12px 16px; font-size:14px; line-height:1.55; }
        .msg-bot { display:flex; gap:10px; align-items:flex-start; }
        .bot-avatar { width:36px; height:36px; border-radius:50%; background:#d1fae5; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
        .bubble-bot { flex:1; min-width:0; background:#fff; border:1px solid #e5e7eb; border-radius:4px 16px 16px 16px; padding:16px 20px; font-size:15px; line-height:1.6; color:#1f2937; overflow-x:auto; }
        .bubble-bot h2 { font-size:17px; color:#047857; margin:18px 0 8px; border-bottom:2px solid #d1fae5; padding-bottom:4px; }
        .bubble-bot h3 { font-size:15px; color:#065f46; margin:14px 0 6px; }
        .bubble-bot table { border-collapse:collapse; width:100%; margin:10px 0; font-size:14px; }
        .bubble-bot th { background:#059669; color:#fff; padding:8px; text-align:left; }
        .bubble-bot td { border:1px solid #e5e7eb; padding:8px; vertical-align:top; }
        .bubble-bot tr:nth-child(even) td { background:#f9fafb; }
        .bubble-bot ul, .bubble-bot ol { padding-left:22px; }
        .typing-bubble { display:flex; gap:5px; padding:14px 18px; background:#fff; border:1px solid #e5e7eb; border-radius:4px 16px 16px 16px; }
        .typing-dot { width:8px; height:8px; border-radius:50%; background:#34d399; animation:bounce 1.2s infinite; }
        .typing-dot:nth-child(2) { animation-delay:0.2s; } .typing-dot:nth-child(3) { animation-delay:0.4s; }
        @keyframes bounce { 0%,60%,100% { transform:translateY(0); } 30% { transform:translateY(-6px); } }
        .typing-text { font-size:14px; color:#6b7280; margin-left:8px; align-self:center; }

        .adjust { border-top:1px solid #f3f4f6; padding:16px 22px; background:#fafafa; }
        .adjust-title { font-size:15px; font-weight:700; color:#374151; margin-bottom:10px; }
        .adjust .chip { min-height:42px; font-size:14px; padding:8px 14px; }
        .adjust .chip::after { content:none; }
        .adjust-row { display:flex; gap:8px; margin-top:12px; }
        .adjust-row .wz-input { padding:10px 14px; font-size:15px; }
        .adjust-note { font-size:12px; color:#9ca3af; margin-top:8px; }
    </style>

    @php
        $recentes = $sessions->map(fn($s) => ['id' => $s->id, 'module' => $s->module, 'title' => $s->display_title]);
    @endphp

    <div class="yp-layout">

        {{-- Sidebar --}}
        <div class="yp-sidebar">
            <div class="sidebar-card">
                <button type="button" class="btn-new" id="btn-new">➕ Nueva planificación</button>

                <div id="recent-card" style="{{ $sessions->count() ? '' : 'display:none;' }}">
                    <div class="sidebar-title">🕐 Mis planificaciones</div>
                    <div id="recent-list">
                        @foreach($sessions as $session)
                        <button type="button" class="recent-btn"
                            data-session-id="{{ $session->id }}"
                            data-module="{{ $session->module }}"
                            title="{{ $session->display_title }}">
                            📄 {{ Str::limit($session->display_title, 30) }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <div class="sidebar-title">💡 ¿Sin ideas?</div>
                <a href="{{ route('ideas.index') }}" style="font-size:14px;color:#059669;font-weight:600;text-decoration:none;">
                    Explora el banco de ideas STEAM →
                </a>
            </div>
        </div>

        {{-- Principal --}}
        <div class="yp-main">

            {{-- ===== ASISTENTE PASO A PASO ===== --}}
            <div id="wizard" style="display:flex;flex-direction:column;flex:1;">
                <div class="wz-progress">
                    <div class="wz-progress-label">
                        <span id="wz-step-label">Paso 1</span>
                        <span id="wz-step-name"></span>
                    </div>
                    <div class="wz-bar"><div class="wz-bar-fill" id="wz-bar" style="width:0%"></div></div>
                </div>
                <div class="wz-body" id="wz-body"></div>
                <div class="wz-footer">
                    <button type="button" class="btn-back" id="wz-back">← Atrás</button>
                    <button type="button" class="btn-next" id="wz-next">Siguiente →</button>
                </div>
            </div>

            {{-- ===== DOCUMENTO GENERADO ===== --}}
            <div id="result" style="display:none;flex-direction:column;flex:1;">
                <div class="res-header">
                    <div class="res-title"><span id="res-icon">📝</span><span id="res-name">Documento</span></div>
                    <button type="button" class="btn-export" id="export-btn" style="display:none;">⬇ Descargar en Word</button>
                </div>
                <div id="messages"></div>
                <div class="adjust" id="adjust" style="display:none;">
                    <div class="adjust-title">¿Quieres cambiar algo? Toca una opción:</div>
                    <div class="chips" id="adjust-chips"></div>
                    <div class="adjust-row">
                        <input type="text" class="wz-input" id="adjust-input" maxlength="1000"
                               placeholder="O escribe otro cambio, por ejemplo: cambia el tema a las heladas">
                        <button type="button" class="btn-export" id="adjust-send">Enviar</button>
                    </div>
                    <div class="adjust-note">Cada cambio usa 1 crédito. El documento se actualiza completo y puedes volver a descargarlo.</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/9.1.6/marked.min.js"></script>
    <script>
    (function () {
        marked.setOptions({ breaks: true, gfm: true });

        const CSRF        = @json(csrf_token());
        const STORE_URL   = @json(route('chat.store'));
        const CREDITS_URL = @json(route('credits.index'));
        const WHATSAPP    = @json(\App\Support\WhatsApp::creditsLink(auth()->user()));
        const DEFAULTS    = @json((object) $defaults);
        const IDEA        = @json($idea);

        // ===== CATÁLOGOS =====
        const MODULES = {
            sesion:    { icon:'📝', name:'Sesión de Aprendizaje',   short:'sesión',       desc:'Una clase completa: inicio, desarrollo y cierre, lista para aplicar.' },
            bimestral: { icon:'📅', name:'Programación Bimestral',  short:'programación', desc:'Lo que trabajarás en todo el bimestre, semana a semana.' },
            abp:       { icon:'🔬', name:'Proyecto STEAM / ABP',    short:'proyecto',     desc:'Un proyecto donde tus estudiantes resuelven un problema real de su comunidad.' },
            rubrica:   { icon:'✅', name:'Rúbrica de Evaluación',   short:'rúbrica',      desc:'Criterios claros para evaluar un producto o desempeño (AD, A, B, C).' },
        };
        // Créditos que cuesta generar cada documento (de module_configs); los ajustes cuestan 1
        const COSTS = @json($modules->pluck('credits_cost', 'slug'));
        const AREAS = {
            Primaria:   ['Matemática','Comunicación','Ciencia y Tecnología','Personal Social','Arte y Cultura','Educación Física','Educación Religiosa','Inglés'],
            Secundaria: ['Matemática','Comunicación','Ciencia y Tecnología','Historia, Geografía y Economía','Desarrollo Personal y Cívica','Arte y Cultura','Educación Física','Inglés','Educación para el Trabajo','Educación Religiosa'],
        };
        const GRADOS = {
            Primaria:   ['1° Primaria','2° Primaria','3° Primaria','4° Primaria','5° Primaria','6° Primaria'],
            Secundaria: ['1° Secundaria','2° Secundaria','3° Secundaria','4° Secundaria','5° Secundaria'],
        };
        const RECURSOS = [
            ['Electricidad','💡'], ['Internet','📶'], ['Celulares o tablets','📱'], ['Proyector o TV','📺'],
            ['Laptop o computadora','💻'], ['Laboratorio','🧪'], ['Impresora','🖨️'], ['Textos del MINEDU','📚'],
            ['Materiales reciclados','♻️'], ['Materiales del entorno (plantas, piedras, tierra)','🌿'],
            ['Biohuerto o chacra','🌱'], ['Patio o espacio libre','🏞️'],
        ];
        const ACTIVIDADES = ['Agricultura','Ganadería','Pesca','Comercio','Artesanía','Turismo','Minería','Actividad forestal','Construcción'];
        const EJEMPLOS = {
            sesion:    ['Fracciones con la cosecha de papa', 'Cuidado del agua en mi comunidad', 'Leemos un cuento andino'],
            bimestral: ['El agua y la salud en nuestra comunidad', 'Nuestra feria productiva'],
            abp:       ['Proteger los cultivos de las heladas', 'Reducir la basura en el río', 'Recuperar plantas medicinales'],
            rubrica:   ['Maqueta de un sistema de riego', 'Exposición oral sobre su proyecto'],
        };
        const GUIA_PROPUESTA = {
            sesion:    '¿Qué quieres que aprendan? ¿Cómo empezarías la clase? ¿Qué actividades harías? ¿Cómo sabrás si aprendieron?',
            bimestral: '¿Qué situación de la comunidad trabajarás? ¿Qué competencias? ¿Qué harían en cada semana? ¿Qué producto final?',
            abp:       '¿Qué problema resolverán? ¿Cuál es la pregunta del proyecto? ¿Qué fases tendría? ¿Qué producto presentarán a la comunidad?',
            rubrica:   '¿Qué producto evaluarás? ¿Qué criterios te parecen importantes? ¿Cómo sería un trabajo excelente?',
        };
        const AJUSTES = [
            ['✂️ Más corto',              'Hazlo más corto y concreto, sin perder lo esencial.'],
            ['🙌 Más actividades prácticas', 'Agrega más actividades prácticas y vivenciales para los estudiantes.'],
            ['🌿 Solo materiales locales', 'Cambia los recursos para usar solo materiales locales, reciclados o del entorno.'],
            ['🦙 Saberes ancestrales',    'Incorpora saberes ancestrales de la comunidad relacionados con el tema.'],
            ['👥 Adaptar a multigrado',    'Adapta el documento para un aula multigrado, diferenciando actividades por grado.'],
            ['🗣️ Lenguaje más sencillo',  'Usa un lenguaje más sencillo y cercano en las consignas para los estudiantes.'],
        ];

        // ===== ESTADO =====
        let P = Object.assign({ modo: 'generar' }, DEFAULTS);
        let stepIndex = 0;
        let currentModule = null;
        let currentSessionId = null;
        let busy = false;

        if (IDEA) {
            P.tipo = 'abp';
            P.tema = IDEA.tema;
            if (IDEA.areas && IDEA.areas.length) P.areas = IDEA.areas.slice();
            if (IDEA.nivel) P.nivel = IDEA.nivel;
        }
        if (!P.areas && P.area) P.areas = [P.area];

        // ===== UTILIDADES =====
        function esc(s) {
            return String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
        }
        function has(field, value) {
            const v = P[field];
            return Array.isArray(v) ? v.includes(value) : v === value;
        }
        function chip(field, value, label, multi) {
            return `<button type="button" class="chip ${has(field, value) ? 'on' : ''}" data-field="${esc(field)}" data-value="${esc(value)}" ${multi ? 'data-multi="1"' : ''}>${esc(label ?? value)}</button>`;
        }
        function chips(field, values, multi) {
            return `<div class="chips">${values.map(v => Array.isArray(v) ? chip(field, v[0], v[1] + ' ' + v[0], multi) : chip(field, v, v, multi)).join('')}</div>`;
        }
        function input(field, placeholder, max) {
            return `<input type="text" class="wz-input" data-input="${esc(field)}" value="${esc(P[field] ?? '')}" placeholder="${esc(placeholder)}" maxlength="${max || 200}">`;
        }
        function textarea(field, placeholder, max) {
            return `<textarea class="wz-textarea" data-input="${esc(field)}" placeholder="${esc(placeholder)}" maxlength="${max || 500}">${esc(P[field] ?? '')}</textarea>`;
        }
        function q(text, small) { return `<div class="wz-q">${text}${small ? ` <small>${small}</small>` : ''}</div>`; }
        function mod() { return MODULES[P.tipo] || MODULES.sesion; }

        // ===== PASOS =====
        const STEPS = [
            {
                name: 'Documento',
                render: () => `
                    ${IDEA ? `<div class="wz-banner">💡 Usando la idea del banco: <b>${esc(IDEA.title)}</b></div>` : ''}
                    <h2 class="wz-title">¿Qué quieres preparar hoy?</h2>
                    <p class="wz-help">Elige una opción. Te haremos algunas preguntas sencillas y luego Yachaycito arma el documento contigo.</p>
                    <div class="cards">
                        ${Object.entries(MODULES).map(([slug, m]) => `
                            <button type="button" class="card-opt ${P.tipo === slug ? 'on' : ''}" data-field="tipo" data-value="${slug}">
                                <span class="ic">${m.icon}</span><span class="nm">${m.name}</span><span class="ds">${m.desc}</span>
                                <span class="ds" style="margin-top:6px;font-weight:700;color:#059669;">⚡ ${(COSTS[slug] || 1) > 1 ? COSTS[slug] + ' créditos' : '1 crédito'}</span>
                            </button>`).join('')}
                    </div>`,
                valid: () => P.tipo ? null : 'Toca el documento que quieres preparar.',
            },
            {
                name: 'Mis estudiantes',
                render: () => `
                    <h2 class="wz-title">Cuéntanos sobre tus estudiantes</h2>
                    <p class="wz-help">Así las actividades se ajustan a tu aula real.</p>
                    ${q('¿En qué nivel enseñas?')}
                    ${chips('nivel', ['Primaria', 'Secundaria'])}
                    ${P.nivel ? `
                        ${q('¿Qué grado?', '— si es aula multigrado, toca todos tus grados')}
                        ${chips('grados', GRADOS[P.nivel], true)}` : ''}
                    ${q('¿Cuántos estudiantes tienes?', '(opcional)')}
                    ${chips('num_estudiantes', ['Menos de 10', '10 a 20', '21 a 30', 'Más de 30'])}
                    ${q('¿Qué lengua hablan en casa?', '(puedes tocar varias)')}
                    ${chips('lengua', ['Castellano', 'Quechua', 'Aimara', 'Lengua amazónica', 'Otra'], true)}
                    ${q('¿Tienes estudiantes con alguna necesidad especial?', '(opcional)')}
                    ${chips('nee_si', ['No', 'Sí'])}
                    ${P.nee_si === 'Sí' ? `<div style="margin-top:10px;">${input('nee', 'Por ejemplo: un estudiante con baja visión, dos con TDAH...', 200)}</div>` : ''}`,
                valid: () => !P.nivel ? 'Elige el nivel en el que enseñas.'
                         : !(P.grados && P.grados.length) ? 'Toca al menos un grado.' : null,
            },
            {
                name: 'Área y tema',
                render: () => {
                    const areas = AREAS[P.nivel] || AREAS.Primaria;
                    const m = mod();
                    let h = `<h2 class="wz-title">¿Qué vas a trabajar?</h2>
                        <p class="wz-help">Si no tienes un tema claro, déjalo en blanco: Yachaycito te propondrá uno según tu comunidad.</p>`;
                    if (P.tipo === 'abp') {
                        h += q('¿Qué áreas se juntan en el proyecto?', '— toca varias') + chips('areas', areas, true);
                    } else {
                        h += q('¿De qué área?') + chips('area', areas);
                    }
                    if (P.tipo === 'bimestral') {
                        h += q('¿Qué bimestre?') + chips('bimestre', ['I Bimestre', 'II Bimestre', 'III Bimestre', 'IV Bimestre']);
                    }
                    if (P.tipo === 'rubrica') {
                        h += q('¿Qué producto o trabajo vas a evaluar?', '(opcional)') + input('producto', 'Por ejemplo: una maqueta, una exposición, un texto...', 200);
                        h += q('¿Qué competencia quieres evaluar?', '(opcional — si no sabes, Yachaycito la elige)') + input('competencia', 'Por ejemplo: Indaga mediante métodos científicos', 300);
                    } else {
                        const label = P.tipo === 'abp' ? '¿Qué problema de tu comunidad quieren resolver?'
                                    : P.tipo === 'bimestral' ? '¿Qué situación de la comunidad quieres trabajar?'
                                    : '¿Cuál es el tema de la clase?';
                        h += q(label, '(opcional)') + textarea('tema', 'Escribe con tus palabras, como se lo contarías a un colega.', 500);
                        h += `<div class="examples">${(EJEMPLOS[P.tipo] || []).map(e => `<button type="button" class="example" data-example="${esc(e)}">${esc(e)}</button>`).join('')}</div>`;
                    }
                    return h;
                },
                valid: () => P.tipo === 'abp'
                    ? (P.areas && P.areas.length ? null : 'Toca al menos un área.')
                    : (P.area ? null : 'Toca el área que vas a trabajar.'),
            },
            {
                name: 'Mis recursos',
                render: () => `
                    <h2 class="wz-title">¿Con qué cuentas en tu escuela?</h2>
                    <p class="wz-help">Toca todo lo que <b>sí</b> tienes. Yachaycito solo propondrá actividades que puedas hacer con eso, ¡nada de pedir proyector si no hay luz!</p>
                    ${chips('recursos', RECURSOS, true)}
                    ${q('¿Algo más?', '(opcional)')}
                    ${input('recursos_otros', 'Por ejemplo: radio, botiquín, herramientas de carpintería...', 200)}`,
                valid: () => null,
            },
            {
                name: 'Tiempo',
                skip: () => P.tipo === 'rubrica',
                render: () => {
                    if (P.tipo === 'sesion') {
                        return `<h2 class="wz-title">¿Cuánto dura tu clase?</h2>
                            <p class="wz-help">Los tiempos de cada momento de la sesión sumarán exactamente esto.</p>
                            <div class="chips">
                                ${chip('duracion_min', '45', '⏱️ 45 min (1 hora)')}
                                ${chip('duracion_min', '90', '⏱️ 90 min (2 horas)')}
                                ${chip('duracion_min', '135', '⏱️ 135 min (3 horas)')}
                            </div>`;
                    }
                    const opts = P.tipo === 'abp' ? ['2', '3', '4', '6', '8'] : ['8', '9', '10'];
                    return `<h2 class="wz-title">¿Cuántas semanas dura?</h2>
                        <p class="wz-help">${P.tipo === 'abp' ? 'Un proyecto corto de 3 o 4 semanas es más fácil de terminar.' : 'Un bimestre suele tener entre 8 y 10 semanas.'}</p>
                        <div class="chips">${opts.map(o => chip('semanas', o, o + ' semanas')).join('')}</div>`;
                },
                valid: () => null,
            },
            {
                name: 'Mi comunidad',
                render: () => `
                    <h2 class="wz-title">¿Cómo es tu comunidad?</h2>
                    <p class="wz-help">Los mejores aprendizajes parten de la vida real de tus estudiantes.</p>
                    ${q('¿La escuela está en zona…')}
                    ${chips('zona', ['Rural', 'Urbana', 'Periurbana'])}
                    ${q('¿A qué se dedican las familias?', '(puedes tocar varias)')}
                    ${chips('actividades', ACTIVIDADES, true)}
                    ${q('¿Hay alguna fiesta o actividad comunal en estas semanas?', '(opcional)')}
                    ${input('festividad', 'Por ejemplo: siembra, Fiesta del Agua, aniversario del pueblo...', 200)}
                    ${q('¿Hay sabios o yachaq que puedan compartir con tus estudiantes?')}
                    ${chips('sabios', ['Sí', 'No', 'No sé'])}`,
                valid: () => null,
            },
            {
                name: '¿Cómo lo hacemos?',
                render: () => {
                    const m = mod();
                    return `
                    <h2 class="wz-title">¿Cómo quieres trabajar tu ${m.short}?</h2>
                    <p class="wz-help">Si escribes tu propia idea, Yachaycito la revisa, te explica qué mejorar y por qué. Así aprendes a planificar cada vez mejor.</p>
                    <div class="cards">
                        <button type="button" class="card-opt ${P.modo === 'generar' ? 'on' : ''}" data-field="modo" data-value="generar">
                            <span class="ic">✨</span><span class="nm">Yachaycito lo prepara</span>
                            <span class="ds">Recibes una propuesta completa y luego la ajustas a tu gusto.</span>
                        </button>
                        <button type="button" class="card-opt ${P.modo === 'revisar' ? 'on' : ''}" data-field="modo" data-value="revisar">
                            <span class="ic">✍️</span><span class="nm">Yo propongo y Yachaycito revisa</span>
                            <span class="ds">Escribes tu idea y recibes retroalimentación pedagógica + la versión mejorada.</span>
                        </button>
                    </div>
                    ${P.modo === 'revisar' ? `
                        ${q('Escribe tu propuesta', '— no tiene que estar perfecta')}
                        <p class="wz-hint">Te puede ayudar responder: ${esc(GUIA_PROPUESTA[P.tipo] || '')}</p>
                        ${textarea('propuesta', 'Escribe aquí tu idea con tus palabras...', 2500)}` : ''}
                    <div class="summary">${summaryRows().map(([k, v]) => `<div class="summary-row"><b>${esc(k)}</b><span>${esc(v)}</span></div>`).join('')}</div>`;
                },
                valid: () => P.modo === 'revisar' && !(P.propuesta && P.propuesta.trim().length >= 20)
                    ? 'Escribe tu propuesta (al menos una o dos frases) o elige "Yachaycito lo prepara".' : null,
            },
        ];

        function activeSteps() { return STEPS.filter(s => !(s.skip && s.skip())); }

        function summaryRows() {
            const rows = [['Documento', mod().name]];
            if (P.grados && P.grados.length) rows.push(['Grado', P.grados.join(', ')]);
            if (P.tipo === 'abp' && P.areas && P.areas.length) rows.push(['Áreas', P.areas.join(', ')]);
            else if (P.area) rows.push(['Área', P.area]);
            if (P.bimestre) rows.push(['Bimestre', P.bimestre]);
            if (P.tema && P.tipo !== 'rubrica') rows.push(['Tema', P.tema]);
            if (P.tipo === 'rubrica' && P.producto) rows.push(['Producto', P.producto]);
            if (P.tipo === 'rubrica' && P.competencia) rows.push(['Competencia', P.competencia]);
            const est = [P.num_estudiantes, (P.lengua || []).join(', ')].filter(Boolean).join(' · ');
            if (est) rows.push(['Estudiantes', est]);
            if (P.nee) rows.push(['Necesidades', P.nee]);
            const rec = (P.recursos || []).concat(P.recursos_otros ? [P.recursos_otros] : []);
            if (rec.length) rows.push(['Recursos', rec.join(', ')]);
            if (P.tipo === 'sesion' && P.duracion_min) rows.push(['Duración', P.duracion_min + ' min']);
            if ((P.tipo === 'abp' || P.tipo === 'bimestral') && P.semanas) rows.push(['Duración', P.semanas + ' semanas']);
            const com = [P.zona, (P.actividades || []).join(', '), P.festividad].filter(Boolean).join(' · ');
            if (com) rows.push(['Comunidad', com]);
            if (P.sabios === 'Sí') rows.push(['Sabios', 'Sí pueden participar']);
            return rows;
        }

        // ===== RENDER DEL ASISTENTE =====
        function renderStep() {
            const steps = activeSteps();
            if (stepIndex >= steps.length) stepIndex = steps.length - 1;
            const step = steps[stepIndex];
            document.getElementById('wz-body').innerHTML = step.render() + '<div class="wz-error" id="wz-error"></div>';
            document.getElementById('wz-step-label').textContent = `Paso ${stepIndex + 1} de ${steps.length}`;
            document.getElementById('wz-step-name').textContent = step.name;
            document.getElementById('wz-bar').style.width = ((stepIndex + 1) / steps.length * 100) + '%';
            document.getElementById('wz-back').style.visibility = stepIndex === 0 ? 'hidden' : 'visible';
            const last = stepIndex === steps.length - 1;
            const next = document.getElementById('wz-next');
            next.disabled = false;
            const cost = COSTS[P.tipo] || 1;
            next.textContent = last ? `✨ Preparar mi ${mod().short} (${cost} ${cost > 1 ? 'créditos' : 'crédito'})` : 'Siguiente →';
        }

        function showError(msg) {
            const el = document.getElementById('wz-error');
            el.textContent = '⚠️ ' + msg;
            el.style.display = 'block';
        }

        // Toque de opciones (una o varias)
        document.getElementById('wz-body').addEventListener('click', function (e) {
            const ex = e.target.closest('[data-example]');
            if (ex) {
                P.tema = ex.dataset.example;
                const ta = document.querySelector('[data-input="tema"]');
                if (ta) ta.value = P.tema;
                return;
            }
            const btn = e.target.closest('[data-field]');
            if (!btn) return;
            const field = btn.dataset.field, value = btn.dataset.value;

            if (btn.dataset.multi) {
                const list = Array.isArray(P[field]) ? P[field].slice() : [];
                const i = list.indexOf(value);
                i >= 0 ? list.splice(i, 1) : list.push(value);
                P[field] = list;
            } else {
                P[field] = P[field] === value && field !== 'tipo' && field !== 'modo' ? undefined : value;
            }

            // Reglas entre campos
            if (field === 'nivel') {
                P.grados = (P.grados || []).filter(g => g.endsWith(P.nivel || '-'));
                const areas = AREAS[P.nivel] || [];
                if (P.area && !areas.includes(P.area)) P.area = undefined;
                if (P.areas) P.areas = P.areas.filter(a => areas.includes(a));
            }
            if (field === 'nee_si') P.nee = P.nee_si === 'Sí' ? (P.nee && P.nee !== 'No' ? P.nee : '') : undefined;
            if (field === 'area' && P.area && !(P.areas && P.areas.length)) P.areas = [P.area];

            // Mantener el foco de lectura: re-render y conservar el scroll
            const y = window.scrollY;
            renderStep();
            window.scrollTo(0, y);

            // En el primer paso, avanzar solo al elegir
            if (field === 'tipo') setTimeout(goNext, 150);
        });

        document.getElementById('wz-body').addEventListener('input', function (e) {
            const f = e.target.dataset.input;
            if (f) P[f] = e.target.value;
        });

        function goNext() {
            const steps = activeSteps();
            const err = steps[stepIndex].valid();
            if (err) { showError(err); return; }
            if (stepIndex < steps.length - 1) {
                stepIndex++;
                renderStep();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                generate();
            }
        }

        document.getElementById('wz-next').addEventListener('click', goNext);
        document.getElementById('wz-back').addEventListener('click', function () {
            if (stepIndex > 0) { stepIndex--; renderStep(); }
        });

        function showWizard(fromStart) {
            if (fromStart) {
                // Se conservan las condiciones del aula; el documento y el tema se eligen de nuevo
                const keep = {};
                ['nivel','grados','num_estudiantes','lengua','nee','nee_si','area','recursos','recursos_otros','zona','actividades','sabios','duracion_min']
                    .forEach(k => { if (P[k] !== undefined) keep[k] = P[k]; });
                P = Object.assign({ modo: 'generar' }, keep);
                stepIndex = 0;
            }
            currentSessionId = null;
            markActiveRecent(null);
            document.getElementById('result').style.display = 'none';
            document.getElementById('wizard').style.display = 'flex';
            renderStep();
        }

        // ===== PLANNING QUE VIAJA AL SERVIDOR =====
        function buildPlanning() {
            const out = {};
            ['nivel','num_estudiantes','nee','area','tema','competencia','producto','bimestre','recursos_otros','zona','festividad','sabios','modo','propuesta']
                .forEach(k => { if (P[k]) out[k] = String(P[k]).trim(); });
            ['grados','lengua','areas','recursos','actividades'].forEach(k => { if (P[k] && P[k].length) out[k] = P[k]; });
            if (P.tipo === 'sesion' && P.duracion_min) out.duracion_min = P.duracion_min;
            if ((P.tipo === 'abp' || P.tipo === 'bimestral') && P.semanas) out.semanas = P.semanas;
            if (P.tipo === 'abp') delete out.area;
            if (P.tipo === 'rubrica') delete out.tema;
            if (P.nee_si !== 'Sí') delete out.nee;
            return out;
        }

        function buildSummaryMessage() {
            const m = mod();
            let txt = `${m.icon} ${m.name}\n` + summaryRows().slice(1).map(([k, v]) => `• ${k}: ${v}`).join('\n');
            if (P.modo === 'revisar') {
                txt += `\n\n✍️ Mi propuesta:\n${P.propuesta.trim()}\n\nRevisa mi propuesta, dime qué mejorar y por qué, y prepara la versión mejorada.`;
            } else {
                txt += `\n\nPrepara el documento con estos datos.`;
            }
            return txt;
        }

        async function generate() {
            if (busy) return;
            currentModule = P.tipo;
            currentSessionId = null;
            openResult(P.tipo);
            const summary = buildSummaryMessage();
            addMessage('user', summary);
            await send({ module: P.tipo, message: summary, planning: buildPlanning(), session_id: null }, summary);
        }

        // ===== VISTA DEL DOCUMENTO =====
        function openResult(slug) {
            const m = MODULES[slug] || { icon:'📄', name: slug };
            document.getElementById('res-icon').textContent = m.icon;
            document.getElementById('res-name').textContent = m.name;
            document.getElementById('messages').innerHTML = '';
            document.getElementById('export-btn').style.display = 'none';
            document.getElementById('adjust').style.display = 'none';
            document.getElementById('wizard').style.display = 'none';
            document.getElementById('result').style.display = 'flex';
            document.getElementById('adjust-chips').innerHTML = AJUSTES.map(([label, msg]) =>
                `<button type="button" class="chip" data-adjust="${esc(msg)}">${esc(label)}</button>`).join('');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function addMessage(role, content) {
            const msgs = document.getElementById('messages');
            const wrap = document.createElement('div');
            if (role === 'user') {
                wrap.className = 'msg-user';
                wrap.innerHTML = `<div class="bubble-user">${esc(content).replace(/\n/g, '<br>')}</div>`;
            } else {
                wrap.className = 'msg-bot';
                wrap.innerHTML = `<div class="bot-avatar">🌱</div><div class="bubble-bot">${marked.parse(content)}</div>`;
            }
            msgs.appendChild(wrap);
            msgs.scrollTop = msgs.scrollHeight;
            return wrap;
        }

        function showTyping() {
            const msgs = document.getElementById('messages');
            const wrap = document.createElement('div');
            wrap.id = 'typing'; wrap.className = 'msg-bot';
            wrap.innerHTML = `<div class="bot-avatar">🌱</div><div class="typing-bubble"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div><span class="typing-text">Yachaycito está preparando tu documento…</span>`;
            msgs.appendChild(wrap);
            msgs.scrollTop = msgs.scrollHeight;
        }
        function removeTyping() { document.getElementById('typing')?.remove(); }

        function setBusy(v) {
            busy = v;
            document.getElementById('adjust-send').disabled = v;
            document.querySelectorAll('#adjust-chips .chip').forEach(c => c.disabled = v);
        }

        function showNoCredits(data) {
            const wa = (data && data.whatsapp) || WHATSAPP;
            // Le quedan créditos, pero no alcanzan (p. ej. un proyecto ABP necesita 2)
            const left = (data && data.remaining) || 0;
            const title = left > 0
                ? `⚡ Este documento necesita ${data.needed} créditos y te queda ${left}`
                : '⚡ Te quedaste sin créditos';
            const msgs = document.getElementById('messages');
            const wrap = document.createElement('div');
            wrap.className = 'msg-bot';
            wrap.innerHTML = `<div class="bot-avatar">🌱</div>
                <div class="bubble-bot" style="border:2px solid #fde68a;background:#fffbeb;">
                    <p style="font-weight:800;color:#92400e;margin:0 0 6px;font-size:16px;">${esc(title)}</p>
                    <p style="margin:0 0 12px;">Tus respuestas están guardadas. Escríbenos por WhatsApp y te recargamos créditos para que sigas preparando tus sesiones, programaciones y proyectos.</p>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <a href="${esc(wa)}" target="_blank" rel="noopener" style="background:#25d366;color:#fff;padding:11px 18px;border-radius:10px;font-weight:700;font-size:15px;text-decoration:none;">💬 Pedir créditos por WhatsApp</a>
                        <a href="${esc(CREDITS_URL)}" style="border:2px solid #059669;color:#059669;padding:9px 16px;border-radius:10px;font-weight:700;font-size:15px;text-decoration:none;">Ver planes</a>
                    </div>
                </div>`;
            msgs.appendChild(wrap);
            msgs.scrollTop = msgs.scrollHeight;
            document.getElementById('credits-count').textContent = String(left);
        }

        // Consulta cada 5 s (hasta ~6 min) si el servidor ya guardó la respuesta de la IA.
        // La respuesta está guardada cuando el último mensaje de la sesión es del asistente.
        async function waitForSaved(id) {
            for (let i = 0; i < 72; i++) {
                await new Promise(r => setTimeout(r, 5000));
                try {
                    const res = await fetch(`/chat/session/${id}`, { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) continue;
                    const msgs = (await res.json()).messages || [];
                    const last = msgs[msgs.length - 1];
                    if (last && last.role === 'assistant' && last.content) return last.content;
                } catch (e) { /* sin internet un momento: seguir esperando */ }
            }
            return null;
        }

        // Envía al servidor y muestra la respuesta en vivo
        async function send(body, titleForRecent) {
            setBusy(true);
            showTyping();
            let bubble = null, full = '', apiError = false, finished = false, truncated = false;
            try {
                const res = await fetch(STORE_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify(body),
                });
                if (res.status === 429) {
                    removeTyping();
                    showNoCredits(await res.json().catch(() => null));
                    return false;
                }
                if (!res.ok) throw new Error('HTTP ' + res.status);

                const reader = res.body.getReader();
                const decoder = new TextDecoder();
                let buffer = '';
                while (true) {
                    let chunkRead;
                    // Si la conexión se corta a mitad, salir del bucle y recuperar lo guardado (abajo)
                    try { chunkRead = await reader.read(); } catch (e) { break; }
                    const { done, value } = chunkRead;
                    if (done) break;
                    buffer += decoder.decode(value, { stream: true });
                    const lines = buffer.split('\n');
                    buffer = lines.pop();
                    for (const line of lines) {
                        if (!line.startsWith('data: ')) continue;
                        const json = line.slice(6).trim();
                        if (!json) continue;
                        let data;
                        try { data = JSON.parse(json); } catch (e) { continue; }
                        if (data.session_id) currentSessionId = data.session_id;
                        if (data.chunk !== undefined) {
                            full += data.chunk;
                            if (!bubble) { removeTyping(); bubble = addMessage('assistant', '').querySelector('.bubble-bot'); }
                            bubble.innerHTML = marked.parse(full);
                            const msgs = document.getElementById('messages');
                            msgs.scrollTop = msgs.scrollHeight;
                        }
                        if (data.error) {
                            apiError = true;
                            if (data.session_id) currentSessionId = data.session_id;
                            if (data.credits_remaining !== undefined) document.getElementById('credits-count').textContent = data.credits_remaining;
                        }
                        if (data.done) {
                            finished = true;
                            truncated = !!data.truncated;
                            document.getElementById('credits-count').textContent = data.credits_remaining;
                            upsertRecent(data.session_id, body.module, titleForRecent);
                        }
                    }
                }
                removeTyping();
                if (!finished && !apiError && currentSessionId) {
                    // La conexión se cortó, pero el servidor sigue trabajando y guarda el documento al terminar
                    const wait = addMessage('assistant', '⏳ Estamos terminando de preparar tu documento. No cierres esta página...');
                    const saved = await waitForSaved(currentSessionId);
                    wait.remove();
                    if (saved) {
                        full = saved;
                        if (!bubble) bubble = addMessage('assistant', '').querySelector('.bubble-bot');
                        bubble.innerHTML = marked.parse(saved);
                        finished = true;
                        upsertRecent(currentSessionId, body.module, titleForRecent);
                    }
                }
                if (!full) throw new Error('empty');
                if (!finished) {
                    // Llegó texto pero el servidor no confirmó que lo guardó: no ofrecer una descarga que fallará
                    const w = addMessage('assistant', '⚠️ La respuesta se cortó antes de guardarse. Usa **Intentar de nuevo** para generarla completa.');
                    const retry = document.createElement('button');
                    retry.type = 'button';
                    retry.className = 'btn-export';
                    retry.style.marginTop = '10px';
                    retry.textContent = '🔄 Intentar de nuevo';
                    retry.addEventListener('click', () => {
                        if (busy) return;
                        w.remove();
                        if (bubble) bubble.closest('.msg-bot').remove();
                        send(Object.assign({}, body, { session_id: currentSessionId }), titleForRecent);
                    });
                    w.querySelector('.bubble-bot').appendChild(retry);
                    return false;
                }
                if (truncated) {
                    addMessage('assistant', '⚠️ El documento salió muy largo y se cortó al final. Puedes pedir **"Hazlo más corto"** o **"Continúa desde donde te quedaste"** en los ajustes.');
                }
                document.getElementById('export-btn').style.display = 'inline-block';
                document.getElementById('adjust').style.display = 'block';
                return true;
            } catch (e) {
                removeTyping();
                const w = addMessage('assistant', apiError
                    ? '❌ No pudimos preparar el documento esta vez. **No se descontó ningún crédito.** Inténtalo de nuevo en un momento.'
                    : '❌ Se cortó la conexión. Revisa tu internet e inténtalo de nuevo.');
                const retry = document.createElement('button');
                retry.type = 'button';
                retry.className = 'btn-export';
                retry.style.marginTop = '10px';
                retry.textContent = '🔄 Intentar de nuevo';
                retry.addEventListener('click', () => {
                    if (busy) return;
                    w.remove();
                    send(Object.assign({}, body, { session_id: currentSessionId }), titleForRecent);
                });
                w.querySelector('.bubble-bot').appendChild(retry);
                return false;
            } finally {
                setBusy(false);
            }
        }

        // Ajustes con botones o texto
        document.getElementById('adjust-chips').addEventListener('click', function (e) {
            const b = e.target.closest('[data-adjust]');
            if (b && !busy) adjust(b.dataset.adjust);
        });
        document.getElementById('adjust-send').addEventListener('click', function () {
            const inp = document.getElementById('adjust-input');
            if (inp.value.trim() && !busy) { adjust(inp.value.trim()); inp.value = ''; }
        });
        document.getElementById('adjust-input').addEventListener('keydown', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); document.getElementById('adjust-send').click(); }
        });

        function adjust(text) {
            if (!currentSessionId || !currentModule) return;
            addMessage('user', text);
            send({ module: currentModule, message: text, session_id: currentSessionId }, text);
        }

        document.getElementById('export-btn').addEventListener('click', function () {
            // Descarga directa: el servidor responde como adjunto y la página no cambia
            // (window.open a una pestaña nueva lo bloquean algunos celulares)
            if (currentSessionId) window.location.href = `/export/word/${currentSessionId}`;
        });
        document.getElementById('btn-new').addEventListener('click', () => showWizard(true));

        // ===== MIS PLANIFICACIONES (recientes) =====
        function markActiveRecent(id) {
            document.querySelectorAll('#recent-list .recent-btn').forEach(b => {
                b.classList.toggle('active', String(b.dataset.sessionId) === String(id));
            });
        }

        function upsertRecent(id, slug, title) {
            const list = document.getElementById('recent-list');
            let btn = list.querySelector(`[data-session-id="${id}"]`);
            if (!btn) {
                const first = String(title || '').split('\n')[0];
                const clean = first.replace(/\s+/g, ' ').trim().slice(0, 60) + (P.tema ? ': ' + P.tema.slice(0, 40) : '');
                btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'recent-btn';
                btn.dataset.sessionId = id;
                btn.dataset.module = slug;
                btn.title = clean;
                btn.textContent = '📄 ' + (clean.length > 30 ? clean.slice(0, 27) + '...' : clean);
            }
            list.prepend(btn);
            while (list.children.length > 6) list.lastElementChild.remove();
            document.getElementById('recent-card').style.display = '';
            markActiveRecent(id);
        }

        document.getElementById('recent-list').addEventListener('click', function (e) {
            const btn = e.target.closest('.recent-btn');
            if (btn && !busy) loadSession(parseInt(btn.dataset.sessionId, 10), btn.dataset.module);
        });

        async function loadSession(id, slug) {
            currentSessionId = id;
            currentModule = slug;
            openResult(slug);
            markActiveRecent(id);
            showTyping();
            try {
                const res = await fetch(`/chat/session/${id}`, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const data = await res.json();
                removeTyping();
                let hasAssistant = false;
                (data.messages || []).forEach(m => {
                    if (!m.content) return;
                    if (m.role === 'assistant') hasAssistant = true;
                    addMessage(m.role === 'user' ? 'user' : 'assistant', m.content);
                });
                if (!document.getElementById('messages').children.length) {
                    addMessage('assistant', 'Esta planificación no tiene contenido todavía.');
                }
                if (hasAssistant) {
                    document.getElementById('export-btn').style.display = 'inline-block';
                    document.getElementById('adjust').style.display = 'block';
                }
            } catch (e) {
                removeTyping();
                addMessage('assistant', 'No se pudo abrir esta planificación. Inténtalo de nuevo.');
            }
            if (window.innerWidth <= 860) window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // ===== INICIO =====
        if (P.nee && P.nee !== 'No') P.nee_si = 'Sí';
        if (IDEA) stepIndex = 1;   // la idea ya define el tipo (proyecto)
        renderStep();
    })();
    </script>
</x-app-layout>
