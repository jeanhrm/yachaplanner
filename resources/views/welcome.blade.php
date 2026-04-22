<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YachaPlanner — Planificación Curricular STEAM para Huancavelica</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; color: #111827; background: #fff; }

        /* NAV */
        nav { position: fixed; top: 0; left: 0; right: 0; z-index: 100; background: rgba(255,255,255,0.95); backdrop-filter: blur(8px); border-bottom: 1px solid #f3f4f6; }
        .nav-inner { max-width: 1200px; margin: 0 auto; padding: 0 24px; height: 64px; display: flex; align-items: center; justify-content: space-between; }
        .nav-logo { display: flex; align-items: center; gap: 10px; font-size: 18px; font-weight: 700; color: #059669; text-decoration: none; }
        .nav-logo span { color: #111827; }
        .nav-links { display: flex; align-items: center; gap: 32px; }
        .nav-links a { font-size: 14px; color: #6b7280; text-decoration: none; font-weight: 500; transition: color 0.15s; }
        .nav-links a:hover { color: #059669; }
        .nav-cta { background: #059669; color: #fff !important; padding: 8px 20px; border-radius: 8px; font-weight: 600 !important; }
        .nav-cta:hover { background: #047857; color: #fff !important; }

        /* HERO */
        .hero { padding: 140px 24px 100px; background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #fff 100%); text-align: center; position: relative; overflow: hidden; }
        .hero::before { content: ''; position: absolute; top: -100px; right: -100px; width: 500px; height: 500px; background: radial-gradient(circle, rgba(5,150,105,0.08) 0%, transparent 70%); border-radius: 50%; }
        .hero-badge { display: inline-flex; align-items: center; gap: 6px; background: #d1fae5; color: #065f46; font-size: 12px; font-weight: 600; padding: 6px 14px; border-radius: 20px; margin-bottom: 24px; letter-spacing: 0.05em; text-transform: uppercase; }
        .hero h1 { font-size: clamp(32px, 5vw, 56px); font-weight: 800; line-height: 1.15; color: #111827; max-width: 800px; margin: 0 auto 24px; }
        .hero h1 em { color: #059669; font-style: normal; }
        .hero p { font-size: 18px; color: #6b7280; max-width: 600px; margin: 0 auto 40px; line-height: 1.7; }
        .hero-btns { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
        .btn-primary { background: #059669; color: #fff; padding: 14px 32px; border-radius: 10px; font-size: 16px; font-weight: 600; text-decoration: none; transition: all 0.15s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary:hover { background: #047857; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(5,150,105,0.3); }
        .btn-secondary { background: #fff; color: #374151; padding: 14px 32px; border-radius: 10px; font-size: 16px; font-weight: 600; text-decoration: none; border: 2px solid #e5e7eb; transition: all 0.15s; }
        .btn-secondary:hover { border-color: #059669; color: #059669; }
        .hero-stats { display: flex; gap: 48px; justify-content: center; margin-top: 64px; flex-wrap: wrap; }
        .stat { text-align: center; }
        .stat-num { font-size: 32px; font-weight: 800; color: #059669; }
        .stat-label { font-size: 13px; color: #9ca3af; margin-top: 4px; }

        /* PROBLEMA */
        .problem { padding: 80px 24px; background: #fff; }
        .section-inner { max-width: 1200px; margin: 0 auto; }
        .section-label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #059669; margin-bottom: 12px; }
        .section-title { font-size: clamp(24px, 3vw, 36px); font-weight: 700; color: #111827; margin-bottom: 16px; }
        .section-sub { font-size: 16px; color: #6b7280; max-width: 600px; line-height: 1.7; }
        .problem-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-top: 48px; }
        .problem-card { background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 24px; }
        .problem-card .icon { font-size: 28px; margin-bottom: 12px; }
        .problem-card h3 { font-size: 15px; font-weight: 600; color: #991b1b; margin-bottom: 8px; }
        .problem-card p { font-size: 14px; color: #6b7280; line-height: 1.6; }

        /* SOLUCIÓN / MÓDULOS */
        .modules { padding: 80px 24px; background: #f9fafb; }
        .modules-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px; margin-top: 48px; }
        .module-card { background: #fff; border-radius: 16px; padding: 28px; border: 1px solid #e5e7eb; transition: all 0.2s; position: relative; overflow: hidden; }
        .module-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: #059669; }
        .module-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,0.08); border-color: #a7f3d0; }
        .module-icon { font-size: 36px; margin-bottom: 16px; }
        .module-card h3 { font-size: 17px; font-weight: 700; color: #111827; margin-bottom: 8px; }
        .module-card p { font-size: 14px; color: #6b7280; line-height: 1.6; }
        .module-tag { display: inline-block; margin-top: 16px; background: #d1fae5; color: #065f46; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px; }

        /* CÓMO FUNCIONA */
        .how { padding: 80px 24px; background: #fff; }
        .steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 32px; margin-top: 48px; position: relative; }
        .step { text-align: center; }
        .step-num { width: 48px; height: 48px; background: #059669; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 700; margin: 0 auto 16px; }
        .step h3 { font-size: 15px; font-weight: 600; color: #111827; margin-bottom: 8px; }
        .step p { font-size: 14px; color: #6b7280; line-height: 1.6; }

        /* CONTEXTO HUANCAVELICA */
        .context { padding: 80px 24px; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff; }
        .context .section-label { color: #a7f3d0; }
        .context .section-title { color: #fff; }
        .context .section-sub { color: #d1fae5; }
        .context-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px; margin-top: 48px; }
        .context-item { background: rgba(255,255,255,0.1); border-radius: 12px; padding: 24px; border: 1px solid rgba(255,255,255,0.15); }
        .context-item .emoji { font-size: 32px; margin-bottom: 12px; }
        .context-item h3 { font-size: 15px; font-weight: 600; margin-bottom: 6px; }
        .context-item p { font-size: 13px; color: #d1fae5; line-height: 1.5; }

        /* PRECIOS */
        .pricing { padding: 80px 24px; background: #f9fafb; }
        .pricing-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-top: 48px; max-width: 900px; margin-left: auto; margin-right: auto; }
        .pricing-card { background: #fff; border-radius: 16px; padding: 32px; border: 2px solid #e5e7eb; position: relative; }
        .pricing-card.featured { border-color: #059669; }
        .pricing-card.featured::before { content: 'MÁS POPULAR'; position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: #059669; color: #fff; font-size: 11px; font-weight: 700; padding: 4px 16px; border-radius: 20px; letter-spacing: 0.08em; }
        .pricing-name { font-size: 14px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px; }
        .pricing-price { font-size: 40px; font-weight: 800; color: #111827; margin-bottom: 4px; }
        .pricing-price span { font-size: 16px; font-weight: 500; color: #9ca3af; }
        .pricing-desc { font-size: 14px; color: #6b7280; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid #f3f4f6; }
        .pricing-features { list-style: none; display: flex; flex-direction: column; gap: 12px; margin-bottom: 32px; }
        .pricing-features li { font-size: 14px; color: #374151; display: flex; align-items: center; gap: 10px; }
        .pricing-features li::before { content: '✓'; color: #059669; font-weight: 700; flex-shrink: 0; }
        .pricing-features li.off { color: #9ca3af; }
        .pricing-features li.off::before { content: '–'; color: #d1d5db; }
        .btn-plan { display: block; text-align: center; padding: 12px; border-radius: 10px; font-size: 15px; font-weight: 600; text-decoration: none; transition: all 0.15s; }
        .btn-plan-green { background: #059669; color: #fff; }
        .btn-plan-green:hover { background: #047857; }
        .btn-plan-outline { background: #fff; color: #059669; border: 2px solid #059669; }
        .btn-plan-outline:hover { background: #f0fdf4; }

        /* CTA FINAL */
        .cta { padding: 100px 24px; background: #fff; text-align: center; }
        .cta h2 { font-size: clamp(28px, 4vw, 44px); font-weight: 800; color: #111827; margin-bottom: 16px; }
        .cta p { font-size: 18px; color: #6b7280; margin-bottom: 40px; }

        /* FOOTER */
        footer { background: #111827; color: #9ca3af; padding: 40px 24px; text-align: center; }
        footer .footer-logo { font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 8px; }
        footer p { font-size: 13px; line-height: 1.6; }
        footer a { color: #6b7280; text-decoration: none; }
        footer a:hover { color: #fff; }
    </style>
</head>
<body>

<!-- NAV -->
<nav>
    <div class="nav-inner">
        <a href="/" class="nav-logo">🌱 Yacha<span>Planner</span></a>
        <div class="nav-links">
            <a href="#modulos">Módulos</a>
            <a href="#como">Cómo funciona</a>
            <a href="#precios">Precios</a>
            @auth
                <a href="{{ route('chat.index') }}" class="nav-cta">Ir al chat →</a>
            @else
                <a href="{{ route('login') }}">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="nav-cta">Empezar gratis →</a>
            @endauth
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-badge">🏔️ Hecho para Huancavelica</div>
    <h1>Planificaciones STEAM en <em>minutos</em>,<br>no en horas</h1>
    <p>YachaPlanner usa inteligencia artificial para generar sesiones de aprendizaje, proyectos ABP y rúbricas alineadas al CNEB, con contexto andino real.</p>
    <div class="hero-btns">
        @auth
            <a href="{{ route('chat.index') }}" class="btn-primary">Ir a mi planificador →</a>
        @else
            <a href="{{ route('register') }}" class="btn-primary">Empezar gratis →</a>
            <a href="{{ route('login') }}" class="btn-secondary">Iniciar sesión</a>
        @endauth
    </div>
    <div class="hero-stats">
        <div class="stat"><div class="stat-num">7</div><div class="stat-label">UGELs de Huancavelica</div></div>
        <div class="stat"><div class="stat-num">4</div><div class="stat-label">Módulos pedagógicos</div></div>
        <div class="stat"><div class="stat-num">∞</div><div class="stat-label">Contextos andinos</div></div>
        <div class="stat"><div class="stat-num">Word</div><div class="stat-label">Exportación directa</div></div>
    </div>
</section>

<!-- PROBLEMA -->
<section class="problem">
    <div class="section-inner">
        <div class="section-label">El problema</div>
        <h2 class="section-title">Los docentes pierden horas en planificación</h2>
        <p class="section-sub">En Huancavelica, los docentes enfrentan condiciones únicas que las herramientas genéricas no consideran.</p>
        <div class="problem-grid">
            <div class="problem-card">
                <div class="icon">⏰</div>
                <h3>Tiempo perdido</h3>
                <p>Un docente promedio dedica 4-6 horas semanales a elaborar documentos pedagógicos desde cero.</p>
            </div>
            <div class="problem-card">
                <div class="icon">📋</div>
                <h3>Formatos complejos</h3>
                <p>Las plantillas del MINEDU son extensas y difíciles de adaptar al contexto local andino.</p>
            </div>
            <div class="problem-card">
                <div class="icon">🌐</div>
                <h3>Sin contexto local</h3>
                <p>Las herramientas existentes ignoran la realidad de Huancavelica: papa nativa, quechua, heladas, río Ichu.</p>
            </div>
        </div>
    </div>
</section>

<!-- MÓDULOS -->
<section class="modules" id="modulos">
    <div class="section-inner">
        <div class="section-label">La solución</div>
        <h2 class="section-title">4 módulos para toda tu planificación</h2>
        <p class="section-sub">Cada módulo genera documentos completos alineados al CNEB con enfoque STEAM y contexto huancavelicano.</p>
        <div class="modules-grid">
            <div class="module-card">
                <div class="module-icon">📅</div>
                <h3>Programación Bimestral</h3>
                <p>Genera tu programación completa por bimestre con competencias, capacidades, desempeños y cronograma de sesiones.</p>
                <span class="module-tag">Primaria y Secundaria</span>
            </div>
            <div class="module-card">
                <div class="module-icon">📝</div>
                <h3>Sesión de Aprendizaje</h3>
                <p>Crea sesiones detalladas con secuencia didáctica (inicio, desarrollo, cierre), metodología activa y evaluación formativa.</p>
                <span class="module-tag">ABP · Indagación · Design Thinking</span>
            </div>
            <div class="module-card">
                <div class="module-icon">🔬</div>
                <h3>Proyecto STEAM / ABP</h3>
                <p>Diseña proyectos interdisciplinarios con pregunta esencial, fases, conexión STEAM y producto final evaluable.</p>
                <span class="module-tag">Aprendizaje Basado en Proyectos</span>
            </div>
            <div class="module-card">
                <div class="module-icon">✅</div>
                <h3>Rúbrica de Evaluación</h3>
                <p>Genera rúbricas por competencia con criterios AD/A/B/C, autoevaluación y coevaluación incluidas.</p>
                <span class="module-tag">CNEB · Evaluación formativa</span>
            </div>
        </div>
    </div>
</section>

<!-- CÓMO FUNCIONA -->
<section class="how" id="como">
    <div class="section-inner">
        <div class="section-label">Proceso</div>
        <h2 class="section-title">Listo en 3 pasos</h2>
        <p class="section-sub">Sin curva de aprendizaje. Sin configuraciones complicadas.</p>
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <h3>Elige tu módulo</h3>
                <p>Selecciona si necesitas una sesión, programación bimestral, proyecto ABP o rúbrica.</p>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <h3>Describe tu necesidad</h3>
                <p>Escribe en lenguaje natural: área, grado, tema y contexto. La IA entiende tu realidad.</p>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <h3>Descarga en Word</h3>
                <p>Obtén tu documento listo para imprimir o compartir. Sigue conversando para refinarlo.</p>
            </div>
        </div>
    </div>
</section>

<!-- CONTEXTO HUANCAVELICA -->
<section class="context">
    <div class="section-inner">
        <div class="section-label">Contexto local</div>
        <h2 class="section-title">Diseñado para la realidad andina</h2>
        <p class="section-sub">YachaPlanner conoce Huancavelica. Sus planificaciones incluyen contextos reales de la región.</p>
        <div class="context-grid">
            <div class="context-item">
                <div class="emoji">🥔</div>
                <h3>Papa nativa y quinua</h3>
                <p>Agricultura andina integrada a proyectos STEAM de ciencias y matemáticas.</p>
            </div>
            <div class="context-item">
                <div class="emoji">🌊</div>
                <h3>Río Ichu y recursos hídricos</h3>
                <p>Problemas reales de contaminación y conservación como contexto de indagación.</p>
            </div>
            <div class="context-item">
                <div class="emoji">🦙</div>
                <h3>Ganadería de alpaca</h3>
                <p>Actividades económicas locales como punto de partida para proyectos interdisciplinarios.</p>
            </div>
            <div class="context-item">
                <div class="emoji">🗣️</div>
                <h3>Lengua quechua</h3>
                <p>Enfoque intercultural bilingüe integrado en las planificaciones pedagógicas.</p>
            </div>
            <div class="context-item">
                <div class="emoji">❄️</div>
                <h3>Heladas y cambio climático</h3>
                <p>Fenómenos climáticos andinos como situaciones significativas para el aprendizaje.</p>
            </div>
            <div class="context-item">
                <div class="emoji">🏔️</div>
                <h3>7 UGELs conectadas</h3>
                <p>Huancavelica, Angaraes, Acobamba, Churcampa, Tayacaja, Huaytará y Castrovirreyna.</p>
            </div>
        </div>
    </div>
</section>

<!-- PRECIOS -->
<section class="pricing" id="precios">
    <div class="section-inner">
        <div class="section-label">Planes</div>
        <h2 class="section-title">Precios pensados para docentes peruanos</h2>
        <p class="section-sub">Empieza gratis. Actualiza cuando necesites más.</p>
        <div class="pricing-grid">
            <div class="pricing-card">
                <div class="pricing-name">Gratuito</div>
                <div class="pricing-price">S/ 0 <span>/ semana</span></div>
                <div class="pricing-desc">Para explorar YachaPlanner sin compromiso.</div>
                <ul class="pricing-features">
                    <li>5 generaciones por semana</li>
                    <li>Los 4 módulos disponibles</li>
                    <li>Exportar Word (con marca de agua)</li>
                    <li>Historial de sesiones</li>
                    <li class="off">Sin marca de agua</li>
                    <li class="off">Soporte prioritario</li>
                </ul>
                <a href="{{ route('register') }}" class="btn-plan btn-plan-outline">Empezar gratis</a>
            </div>
            <div class="pricing-card featured">
                <div class="pricing-name">Pro Docente</div>
                <div class="pricing-price">S/ 25 <span>/ mes</span></div>
                <div class="pricing-desc">Para docentes que planifican semanalmente.</div>
                <ul class="pricing-features">
                    <li>Generaciones ilimitadas</li>
                    <li>Los 4 módulos disponibles</li>
                    <li>Word sin marca de agua</li>
                    <li>Historial completo</li>
                    <li>Soporte prioritario</li>
                    <li class="off">Panel institucional</li>
                </ul>
                <a href="{{ route('register') }}" class="btn-plan btn-plan-green">Obtener Pro →</a>
            </div>
            <div class="pricing-card">
                <div class="pricing-name">Institución</div>
                <div class="pricing-price">S/ 350 <span>/ año</span></div>
                <div class="pricing-desc">Para IEs y UGELs con múltiples docentes.</div>
                <ul class="pricing-features">
                    <li>Hasta 30 docentes Pro</li>
                    <li>Panel del director</li>
                    <li>Contexto institucional propio</li>
                    <li>Factura para UGEL</li>
                    <li>Soporte dedicado</li>
                    <li>Capacitación incluida</li>
                </ul>
                <a href="mailto:jean@quipubit.com" class="btn-plan btn-plan-outline">Contactar →</a>
            </div>
        </div>
    </div>
</section>

<!-- CTA FINAL -->
<section class="cta">
    <h2>¿Listo para planificar con IA?</h2>
    <p>Únete a los docentes de Huancavelica que ya ahorran horas cada semana.</p>
    <div class="hero-btns">
        @auth
            <a href="{{ route('chat.index') }}" class="btn-primary">Ir a mi planificador →</a>
        @else
            <a href="{{ route('register') }}" class="btn-primary">Empezar gratis →</a>
        @endauth
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="footer-logo">🌱 YachaPlanner</div>
    <p style="margin-top:8px;">
        Desarrollado por <a href="#">Quipubit — Escuela Tecnológica</a> · Huancavelica, Perú<br>
        Con apoyo de UNICEF Perú · Área Huancavelica
    </p>
    <p style="margin-top:16px;">
        <a href="{{ route('login') }}">Iniciar sesión</a> ·
        <a href="{{ route('register') }}">Registrarse</a>
    </p>
</footer>

</body>
</html>