<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yachaycito STEAM — Sesiones y proyectos STEAM para tu aula real, alineados al CNEB</title>
    <meta name="description" content="Responde preguntas sencillas sobre tus estudiantes, tus recursos y tu comunidad, y recibe tu sesión de aprendizaje, proyecto ABP o rúbrica alineada al CNEB, lista en Word. Gratis para empezar.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; color: #111827; background: #fff; -webkit-font-smoothing: antialiased; }
        a { color: inherit; }
        .wrap { max-width: 1120px; margin: 0 auto; padding: 0 20px; }

        /* NAV */
        nav { position: sticky; top: 0; z-index: 50; background: rgba(255,255,255,.94); backdrop-filter: blur(8px); border-bottom: 1px solid #f1f5f9; }
        .nav-in { height: 60px; display: flex; align-items: center; justify-content: space-between; }
        .logo { font-size: 18px; font-weight: 800; color: #059669; text-decoration: none; }
        .logo span { color: #111827; }
        .nav-r { display: flex; align-items: center; gap: 18px; font-size: 14px; font-weight: 600; }
        .nav-r a { text-decoration: none; color: #4b5563; }
        .nav-r a:hover { color: #059669; }

        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; border-radius: 12px; font-weight: 700; text-decoration: none; transition: transform .15s, box-shadow .15s, background .15s; }
        .btn-main { background: #059669; color: #fff !important; padding: 15px 26px; font-size: 17px; box-shadow: 0 6px 18px rgba(5,150,105,.28); }
        .btn-main:hover { background: #047857; transform: translateY(-1px); }
        .btn-sm { padding: 9px 16px; font-size: 14px; border-radius: 10px; box-shadow: none; }
        .btn-ghost { color: #065f46 !important; padding: 15px 20px; font-size: 16px; }
        .btn-ghost:hover { text-decoration: underline; }

        /* HERO: todo lo esencial cabe en la primera pantalla */
        .hero { background: linear-gradient(160deg, #ecfdf5 0%, #f0fdf4 45%, #fff 100%); padding: 40px 0 44px; }
        .hero-grid { display: grid; grid-template-columns: 1.05fr .95fr; gap: 44px; align-items: center; }
        .pill { display: inline-block; background: #d1fae5; color: #065f46; font-size: 13px; font-weight: 700; padding: 6px 12px; border-radius: 999px; margin-bottom: 18px; }
        h1 { font-size: clamp(30px, 4.4vw, 50px); line-height: 1.1; font-weight: 800; letter-spacing: -.02em; margin-bottom: 18px; }
        h1 em { font-style: normal; color: #059669; }
        .lead { font-size: 18px; line-height: 1.6; color: #4b5563; margin-bottom: 26px; max-width: 540px; }
        .cta-row { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; }
        .reassure { display: flex; flex-wrap: wrap; gap: 6px 18px; margin-top: 16px; font-size: 14px; color: #374151; font-weight: 500; }
        .reassure span::before { content: '✓ '; color: #059669; font-weight: 800; }
        .proof { margin-top: 22px; font-size: 14px; color: #6b7280; }
        .proof b { color: #111827; }

        /* Vista previa del producto */
        .demo { background: #fff; border: 1px solid #e5e7eb; border-radius: 20px; box-shadow: 0 24px 60px rgba(6,95,70,.12); padding: 20px; position: relative; }
        .demo-step { font-size: 12px; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: .06em; }
        .demo-q { font-size: 17px; font-weight: 700; margin: 6px 0 12px; }
        .chips { display: flex; flex-wrap: wrap; gap: 8px; }
        .chip { font-size: 13px; font-weight: 600; padding: 7px 11px; border-radius: 999px; border: 1.5px solid #e5e7eb; color: #6b7280; background: #fff; }
        .chip.on { border-color: #059669; background: #ecfdf5; color: #065f46; }
        .chip.off { text-decoration: line-through; opacity: .6; }
        .demo-arrow { text-align: center; font-size: 13px; color: #6b7280; margin: 14px 0; font-weight: 600; }
        .doc { border: 1px solid #d1fae5; border-radius: 14px; overflow: hidden; }
        .doc-h { background: #1a7a4a; color: #fff; padding: 10px 14px; font-size: 13px; font-weight: 700; display: flex; justify-content: space-between; }
        .doc-b { padding: 12px 14px; font-size: 13px; color: #374151; line-height: 1.55; }
        .doc-b b { color: #065f46; }
        .doc-row { display: grid; grid-template-columns: 74px 1fr; gap: 8px; padding: 6px 0; border-bottom: 1px dashed #e5e7eb; }
        .doc-row:last-child { border-bottom: none; }
        .doc-row span:first-child { font-weight: 700; color: #059669; }
        .word-tag { position: absolute; right: -10px; bottom: -14px; background: #1d4ed8; color: #fff; font-size: 13px; font-weight: 700; padding: 8px 12px; border-radius: 10px; box-shadow: 0 8px 20px rgba(29,78,216,.3); }

        /* SECCIONES compactas */
        section { padding: 56px 0; }
        .kicker { font-size: 13px; font-weight: 800; color: #059669; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 8px; }
        h2 { font-size: clamp(24px, 3vw, 34px); font-weight: 800; letter-spacing: -.01em; line-height: 1.2; margin-bottom: 10px; }
        .sub { color: #4b5563; font-size: 17px; line-height: 1.6; max-width: 640px; }

        /* Datos del Perú */
        .facts { background: #064e3b; color: #fff; }
        .facts .kicker { color: #6ee7b7; }
        .facts .sub { color: #d1fae5; }
        .fact-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-top: 28px; }
        .fact { background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); border-radius: 16px; padding: 20px; }
        .fact-n { font-size: 38px; font-weight: 800; color: #6ee7b7; line-height: 1; }
        .fact-t { font-size: 15px; font-weight: 700; margin: 8px 0 10px; }
        .fact-y { font-size: 14px; color: #d1fae5; line-height: 1.5; padding-top: 10px; border-top: 1px solid rgba(255,255,255,.12); }
        .fact-y b { color: #fff; }
        .sources { font-size: 12px; color: #a7f3d0; margin-top: 16px; opacity: .8; }

        /* Qué puedes crear */
        .make-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-top: 26px; }
        .make { border: 1px solid #e5e7eb; border-radius: 16px; padding: 18px; }
        .make-i { font-size: 26px; }
        .make h3 { font-size: 16px; font-weight: 700; margin: 8px 0 4px; }
        .make p { font-size: 14px; color: #6b7280; line-height: 1.5; }

        /* Diferencial: aprender a planificar */
        .learn { background: #f9fafb; }
        .learn-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; }
        .ticks { list-style: none; margin-top: 18px; display: grid; gap: 12px; }
        .ticks li { font-size: 16px; color: #374151; line-height: 1.5; padding-left: 30px; position: relative; }
        .ticks li::before { content: '✓'; position: absolute; left: 0; top: 0; width: 22px; height: 22px; border-radius: 50%; background: #d1fae5; color: #065f46; font-weight: 800; font-size: 13px; display: flex; align-items: center; justify-content: center; }
        .fb { background: #fff; border: 1px solid #e5e7eb; border-radius: 18px; padding: 20px; box-shadow: 0 12px 32px rgba(0,0,0,.06); }
        .fb-t { font-size: 13px; font-weight: 800; color: #059669; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 12px; }
        .fb-i { font-size: 14px; line-height: 1.55; color: #374151; padding: 10px 12px; border-radius: 10px; margin-bottom: 8px; }
        .fb-i.ok  { background: #ecfdf5; }
        .fb-i.fix { background: #fffbeb; }
        .fb-i.tip { background: #eff6ff; margin-bottom: 0; }

        /* Preguntas frecuentes plegadas: no suman scroll */
        .faq { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 20px; margin-top: 22px; }
        details { border: 1px solid #e5e7eb; border-radius: 14px; padding: 14px 16px; background: #fff; }
        summary { cursor: pointer; font-weight: 700; font-size: 15px; list-style: none; display: flex; justify-content: space-between; gap: 10px; }
        summary::-webkit-details-marker { display: none; }
        summary::after { content: '+'; color: #059669; font-size: 20px; line-height: 1; }
        details[open] summary::after { content: '–'; }
        details p { font-size: 14px; color: #4b5563; line-height: 1.6; margin-top: 10px; }

        /* CTA final */
        .final { background: linear-gradient(135deg, #059669, #047857); color: #fff; text-align: center; padding: 52px 0; }
        .final h2 { color: #fff; }
        .final .sub { color: #d1fae5; margin: 0 auto 24px; }
        .final .btn-main { background: #fff; color: #065f46 !important; box-shadow: none; }
        .final .btn-ghost { color: #fff !important; }

        footer { background: #111827; color: #9ca3af; font-size: 13px; padding: 22px 0; }
        .foot { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 10px; }
        footer a { color: #d1d5db; text-decoration: none; }

        /* Ayuda por WhatsApp siempre a la mano */
        .wa { position: fixed; right: 16px; bottom: 16px; z-index: 60; background: #25d366; color: #fff; text-decoration: none; font-weight: 700; font-size: 14px; padding: 12px 16px; border-radius: 999px; box-shadow: 0 8px 24px rgba(0,0,0,.18); display: flex; align-items: center; gap: 8px; }

        @media (max-width: 900px) {
            .hero-grid, .learn-grid { grid-template-columns: 1fr; gap: 28px; }
            .fact-grid, .make-grid { grid-template-columns: repeat(2, 1fr); }
            .faq { grid-template-columns: 1fr; }
        }
        @media (max-width: 560px) {
            .hero { padding: 26px 0 34px; }
            .lead { font-size: 16px; }
            .btn-main { width: 100%; }
            .nav-r .hide-sm { display: none; }
            .logo { font-size: 16px; white-space: nowrap; }
            .nav-r { gap: 12px; white-space: nowrap; }
            .nav-r .btn-sm { padding: 8px 12px; }
            section { padding: 40px 0; }
            .fact-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
            .fact { padding: 14px; }
            .fact-n { font-size: 30px; }
            .fact-y { display: none; }           /* en celular: solo el dato, sin texto extra */
            .make-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
            .make p { display: none; }
            .demo .doc-row:nth-child(n+3) { display: none; }
            .word-tag { right: 10px; }
            .wa span { display: none; }
        }
        @media (max-width: 400px) {
            /* "Ya tengo cuenta" ya está en la primera pantalla */
            .nav-r .hide-xs, .logo span { display: none; }
        }
    </style>
</head>
<body>

@php
    $ctaUrl   = auth()->check() ? route('chat.index') : route('register');
    $ctaLabel = auth()->check() ? 'Ir a mi planificador →' : 'Crear mi primera sesión gratis →';
    // La prueba social solo aparece cuando la cifra ya convence
    $showProof = ($stats['docentes'] ?? 0) >= 50 || ($stats['documentos'] ?? 0) >= 200;
@endphp

<nav>
    <div class="wrap nav-in">
        <a href="/" class="logo">🌱 Yachaycito <span>STEAM</span></a>
        <div class="nav-r">
            <a href="#preguntas" class="hide-sm">Preguntas</a>
            @auth
                <a href="{{ route('chat.index') }}" class="btn btn-main btn-sm">Ir al planificador</a>
            @else
                <a href="{{ route('login') }}" class="hide-xs">Ingresar</a>
                <a href="{{ route('register') }}" class="btn btn-main btn-sm">Empezar gratis</a>
            @endauth
        </div>
    </div>
</nav>

<!-- 1. HERO: promesa + acción + prueba visual -->
<header class="hero">
    <div class="wrap hero-grid">
        <div>
            <div class="pill">🇵🇪 Hecho en Huancavelica para docentes de todo el Perú</div>
            <h1>Tu sesión de aprendizaje lista en minutos, <em>pensada para tu aula real</em></h1>
            <p class="lead">Respondes preguntas sencillas sobre tus estudiantes, tus recursos y tu comunidad. Recibes un documento alineado al CNEB, listo para imprimir en Word.</p>
            <div class="cta-row">
                <a href="{{ $ctaUrl }}" class="btn btn-main">{{ $ctaLabel }}</a>
                @guest <a href="{{ route('login') }}" class="btn btn-ghost">Ya tengo cuenta</a> @endguest
            </div>
            <div class="reassure">
                <span>5 créditos gratis</span>
                <span>Sin tarjeta</span>
                <span>Funciona en el celular</span>
            </div>
            @if ($showProof)
                <p class="proof">
                    Ya planifican con nosotros <b>{{ number_format($stats['docentes']) }} docentes</b>
                    @if (($stats['documentos'] ?? 0) >= 200) · <b>{{ number_format($stats['documentos']) }} documentos</b> generados @endif
                </p>
            @endif
        </div>

        <div class="demo" aria-hidden="true">
            <div class="demo-step">Paso 4 de 7 · Mis recursos</div>
            <div class="demo-q">¿Con qué cuentas en tu aula?</div>
            <div class="chips">
                <span class="chip on">Textos del MINEDU</span>
                <span class="chip on">Materiales reciclados</span>
                <span class="chip on">Biohuerto o chacra</span>
                <span class="chip off">Internet</span>
                <span class="chip off">Proyector o TV</span>
            </div>
            <div class="demo-arrow">↓ La IA solo propone actividades que puedes hacer ↓</div>
            <div class="doc">
                <div class="doc-h"><span>Sesión de Aprendizaje</span><span>90 min</span></div>
                <div class="doc-b">
                    <div class="doc-row"><span>Tema</span><span>El agua en mi comunidad</span></div>
                    <div class="doc-row"><span>Aula</span><span>3° y 4° grado · <b>multigrado</b> · quechua</span></div>
                    <div class="doc-row"><span>Inicio</span><span>Visita al puquio con el sabio de la comunidad</span></div>
                    <div class="doc-row"><span>Evalúa</span><span>Lista de cotejo con criterios del CNEB</span></div>
                </div>
            </div>
            <div class="word-tag">⬇ Descarga en Word</div>
        </div>
    </div>
</header>

<!-- 2. DATOS: por qué una plantilla genérica no sirve en el Perú -->
<section class="facts">
    <div class="wrap">
        <div class="kicker">El Perú no cabe en una plantilla</div>
        <h2>Cada aula es distinta. Tu planificación también debería serlo.</h2>
        <p class="sub">Las herramientas genéricas planifican para un aula que no existe. Yachaycito parte de la tuya.</p>
        <div class="fact-grid">
            <div class="fact">
                <div class="fact-n">48</div>
                <div class="fact-t">lenguas originarias</div>
                <div class="fact-y">Si tus estudiantes hablan quechua, aimara o asháninka, <b>incluimos palabras y textos en su lengua</b> (enfoque EIB).</div>
            </div>
            <div class="fact">
                <div class="fact-n">55</div>
                <div class="fact-t">pueblos indígenas</div>
                <div class="fact-y">Tu festividad, tus sabios y las actividades de tu comunidad <b>se vuelven el contexto de la clase</b>.</div>
            </div>
            <div class="fact">
                <div class="fact-n">31</div>
                <div class="fact-t">competencias del CNEB</div>
                <div class="fact-y">Elegimos la competencia, capacidades y desempeños <b>que corresponden a tu grado y tema</b>.</div>
            </div>
            <div class="fact">
                <div class="fact-n">1</div>
                <div class="fact-t">docente, varios grados</div>
                <div class="fact-y">En miles de escuelas rurales un solo docente enseña a varios grados. <b>Diferenciamos actividades por grado</b> en la misma sesión.</div>
            </div>
        </div>
        <p class="sources">Fuentes: Ministerio de Educación (lenguas originarias, CNEB 2016) y Base de Datos de Pueblos Indígenas u Originarios del Ministerio de Cultura.</p>
    </div>
</section>

<!-- 3. QUÉ PUEDES CREAR -->
<section>
    <div class="wrap">
        <div class="kicker">Qué puedes crear</div>
        <h2>Todo lo que te piden, en un solo lugar</h2>
        <div class="make-grid">
            <div class="make"><div class="make-i">📝</div><h3>Sesión de aprendizaje</h3><p>Inicio, desarrollo y cierre con los minutos exactos que tienes.</p></div>
            <div class="make"><div class="make-i">🔬</div><h3>Proyecto STEAM / ABP</h3><p>Varias áreas articuladas alrededor de un problema real de tu comunidad.</p></div>
            <div class="make"><div class="make-i">📅</div><h3>Programación bimestral</h3><p>Competencias, evidencias y cronograma de sesiones del bimestre.</p></div>
            <div class="make"><div class="make-i">✅</div><h3>Rúbrica de evaluación</h3><p>Niveles AD, A, B y C, con autoevaluación y coevaluación.</p></div>
        </div>
    </div>
</section>

<!-- 4. DIFERENCIAL: aprender a planificar -->
<section class="learn">
    <div class="wrap learn-grid">
        <div>
            <div class="kicker">No te reemplaza</div>
            <h2>Te ayuda a ser mejor planificador</h2>
            <p class="sub">Escribe tu propia idea de sesión y la IA la revisa como lo haría un buen acompañante pedagógico.</p>
            <ul class="ticks">
                <li>Te dice <b>qué hiciste bien</b> y <b>qué mejorar</b>, explicando el porqué.</li>
                <li>Respeta tu idea: la mejora, no la cambia por otra.</li>
                <li>Avanzas en la <b>Ruta STEAM</b> y ganas insignias a medida que completas proyectos.</li>
            </ul>
        </div>
        <div class="fb" aria-hidden="true">
            <div class="fb-t">Retroalimentación a tu propuesta</div>
            <div class="fb-i ok">✅ <b>Bien:</b> partes de un problema real de la comunidad (la escasez de agua en el puquio).</div>
            <div class="fb-i fix">🔧 <b>Mejora:</b> tu evidencia no muestra el desempeño. Pide que los estudiantes <i>expliquen</i> por qué baja el agua, no solo que la dibujen.</div>
            <div class="fb-i tip">💡 <b>Para la próxima:</b> define primero la evidencia y luego diseña las actividades que llevan a ella.</div>
        </div>
    </div>
</section>

<!-- 5. PREGUNTAS FRECUENTES (plegadas) -->
<section id="preguntas">
    <div class="wrap">
        <div class="kicker">Preguntas frecuentes</div>
        <h2>Lo que nos preguntan los docentes</h2>
        <div class="faq">
            <details>
                <summary>¿Necesito saber usar inteligencia artificial?</summary>
                <p>No. No escribes instrucciones ni "prompts": eliges opciones con botones, paso a paso, como llenar una ficha. Si te trabas, escríbenos por WhatsApp.</p>
            </details>
            <details>
                <summary>¿Cuánto cuesta?</summary>
                <p>Empiezas gratis con 5 créditos. Una sesión, programación o rúbrica usa 1 crédito; un proyecto STEAM/ABP usa 2, y cada ajuste posterior usa 1. Si necesitas más, recargas por WhatsApp. También hay Plan Pro (S/ 25 al mes) y Plan Institución (S/ 350 al año) para toda tu IE.</p>
            </details>
            <details>
                <summary>¿Sirve si mi escuela no tiene internet ni proyector?</summary>
                <p>Sí, está pensado para eso. Marcas los recursos que sí tienes y la IA no propone actividades que necesiten lo que te falta. Solo necesitas internet en el momento de generar el documento.</p>
            </details>
            <details>
                <summary>¿Está alineado al Currículo Nacional?</summary>
                <p>Sí. Cada documento usa las competencias, capacidades y desempeños del CNEB, y la evaluación formativa por niveles de logro. Siempre puedes revisarlo y ajustarlo antes de usarlo.</p>
            </details>
            <details>
                <summary>¿Puedo cambiar el documento después?</summary>
                <p>Sí. Con un botón pides "más corto", "más actividades prácticas" o lo que necesites, y lo descargas de nuevo en Word para editarlo a tu gusto.</p>
            </details>
            <details>
                <summary>¿Funciona para aulas multigrado y EIB?</summary>
                <p>Sí. Eliges varios grados y la sesión diferencia actividades por grado. Si tus estudiantes hablan una lengua originaria, se incluyen palabras y textos en esa lengua.</p>
            </details>
        </div>
    </div>
</section>

<!-- CTA FINAL -->
<section class="final">
    <div class="wrap">
        <h2>Tu próxima sesión, lista antes de que se enfríe tu café</h2>
        <p class="sub">Crea tu cuenta con tu correo y prueba gratis con tus propios estudiantes en mente.</p>
        <div class="cta-row" style="justify-content:center;">
            <a href="{{ $ctaUrl }}" class="btn btn-main">{{ $ctaLabel }}</a>
            <a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="btn btn-ghost">¿Dudas? Escríbenos</a>
        </div>
    </div>
</section>

<footer>
    <div class="wrap foot">
        <span>🌱 Yachaycito STEAM · Desarrollado por Jean C. Ordoñez · Huancavelica, Perú</span>
        <span>
            <a href="{{ route('login') }}">Ingresar</a> ·
            <a href="{{ route('register') }}">Crear cuenta</a> ·
            <a href="{{ $whatsapp }}" target="_blank" rel="noopener">WhatsApp</a>
        </span>
    </div>
</footer>

<a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="wa" aria-label="Ayuda por WhatsApp">💬 <span>¿Necesitas ayuda?</span></a>

</body>
</html>
