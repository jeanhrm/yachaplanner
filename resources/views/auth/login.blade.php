<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión — YachaPlanner</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f0fdf4; min-height: 100vh; display: flex; }

        /* LADO IZQUIERDO */
        .left-panel {
            width: 45%;
            background: linear-gradient(135deg, #059669 0%, #047857 60%, #065f46 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 48px;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -100px; left: -60px;
            width: 400px; height: 400px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }

        .left-content { position: relative; z-index: 1; text-align: center; }

        .left-logo {
            font-size: 48px;
            margin-bottom: 16px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .left-title {
            font-size: 32px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .left-subtitle {
            font-size: 15px;
            color: rgba(255,255,255,0.8);
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            text-align: left;
            width: 100%;
            max-width: 320px;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 14px 16px;
            backdrop-filter: blur(4px);
        }

        .feature-icon { font-size: 20px; flex-shrink: 0; margin-top: 1px; }

        .feature-text h4 {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 2px;
        }

        .feature-text p {
            font-size: 12px;
            color: rgba(255,255,255,0.7);
            line-height: 1.4;
        }

        .left-badge {
            margin-top: 32px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 20px;
            padding: 8px 20px;
            font-size: 12px;
            color: rgba(255,255,255,0.9);
            font-weight: 500;
        }

        /* LADO DERECHO */
        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 48px 40px;
        }

        .form-container {
            width: 100%;
            max-width: 420px;
        }

        .form-header { margin-bottom: 32px; }

        .form-header h2 {
            font-size: 26px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 6px;
        }

        .form-header p {
            font-size: 14px;
            color: #6b7280;
        }

        .form-header p a {
            color: #059669;
            font-weight: 600;
            text-decoration: none;
        }

        .form-header p a:hover { text-decoration: underline; }

        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            color: #111827;
            font-family: inherit;
            transition: all 0.2s;
            background: #fff;
            outline: none;
        }

        .form-input:focus {
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5,150,105,0.1);
        }

        .form-input::placeholder { color: #9ca3af; }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
        }

        .remember-label input {
            width: 16px; height: 16px;
            accent-color: #059669;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 13px;
            color: #059669;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover { text-decoration: underline; }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #059669, #047857);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            box-shadow: 0 4px 12px rgba(5,150,105,0.3);
            letter-spacing: 0.02em;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(5,150,105,0.4);
        }

        .btn-submit:active { transform: translateY(0); }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
            color: #9ca3af;
            font-size: 13px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .btn-register {
            display: block;
            width: 100%;
            padding: 14px;
            background: #fff;
            color: #059669;
            border: 2px solid #d1fae5;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
            font-family: inherit;
        }

        .btn-register:hover {
            background: #f0fdf4;
            border-color: #059669;
            transform: translateY(-1px);
        }

        .error-msg {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #dc2626;
            margin-bottom: 16px;
        }

        .back-link {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #6b7280;
            text-decoration: none;
            margin-bottom: 32px;
            transition: color 0.15s;
        }

        .back-link:hover { color: #059669; }

        .stats-row {
            display: flex;
            gap: 24px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #f3f4f6;
        }

        .stat-item { text-align: center; }
        .stat-num { font-size: 20px; font-weight: 800; color: #059669; }
        .stat-label { font-size: 11px; color: #9ca3af; margin-top: 2px; }

        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { padding: 32px 24px; }
        }
    </style>
</head>
<body>

<div class="left-panel">
    <div class="left-content">
        <div class="left-logo">🌱</div>
        <h1 class="left-title">YachaPlanner</h1>
        <p class="left-subtitle">Planificaciones STEAM en minutos,<br>con contexto andino real</p>

        <div class="feature-list">
            <div class="feature-item">
                <span class="feature-icon">📝</span>
                <div class="feature-text">
                    <h4>Sesiones y proyectos ABP</h4>
                    <p>Generados con IA, alineados al CNEB</p>
                </div>
            </div>
            <div class="feature-item">
                <span class="feature-icon">🏔️</span>
                <div class="feature-text">
                    <h4>Contexto huancavelicano</h4>
                    <p>Papa nativa, río Ichu, quechua, heladas</p>
                </div>
            </div>
            <div class="feature-item">
                <span class="feature-icon">⬇️</span>
                <div class="feature-text">
                    <h4>Exporta a Word al instante</h4>
                    <p>Listo para imprimir o compartir</p>
                </div>
            </div>
        </div>

        <div class="left-badge">🦙 Hecho para docentes de Huancavelica</div>
    </div>
</div>

<div class="right-panel">
    <div class="form-container">
        <a href="/" class="back-link">← Volver al inicio</a>

        <div class="form-header">
            <h2>¡Bienvenido de vuelta! 👋</h2>
            <p>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate gratis</a></p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            @if($errors->any())
            <div class="error-msg">
                {{ $errors->first() }}
            </div>
            @endif

            <div class="form-group">
                <label class="form-label" for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" class="form-input"
                    placeholder="tucorreo@ejemplo.com"
                    value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Contraseña</label>
                <input id="password" type="password" name="password" class="form-input"
                    placeholder="••••••••" required>
            </div>

            <div class="form-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember">
                    Recordarme
                </label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">¿Olvidaste tu contraseña?</a>
                @endif
            </div>

            <button type="submit" class="btn-submit">
                Ingresar a YachaPlanner →
            </button>

            <div class="divider">o</div>

            <a href="{{ route('register') }}" class="btn-register">
                Crear cuenta gratis
            </a>
        </form>

        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-num">7</div>
                <div class="stat-label">UGELs</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">4</div>
                <div class="stat-label">Módulos</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">∞</div>
                <div class="stat-label">Contextos</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">Word</div>
                <div class="stat-label">Exportación</div>
            </div>
        </div>
    </div>
</div>

</body>
</html>