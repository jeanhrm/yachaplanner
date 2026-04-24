<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro — YachaPlanner</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f0fdf4; min-height: 100vh; display: flex; }

        .left-panel {
            width: 40%;
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

        .left-title { font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 8px; }
        .left-subtitle { font-size: 14px; color: rgba(255,255,255,0.8); margin-bottom: 36px; line-height: 1.6; }

        .plan-card {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 16px;
            padding: 20px;
            width: 100%;
            max-width: 300px;
            margin-bottom: 16px;
            text-align: left;
        }

        .plan-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .plan-price {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 4px;
        }

        .plan-price span { font-size: 14px; font-weight: 400; opacity: 0.8; }

        .plan-features {
            list-style: none;
            margin-top: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .plan-features li {
            font-size: 12px;
            color: rgba(255,255,255,0.9);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .plan-features li::before {
            content: '✓';
            color: #6ee7b7;
            font-weight: 700;
            flex-shrink: 0;
        }

        .left-note {
            font-size: 12px;
            color: rgba(255,255,255,0.6);
            margin-top: 16px;
            line-height: 1.5;
        }

        /* DERECHO */
        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            overflow-y: auto;
        }

        .form-container { width: 100%; max-width: 460px; }

        .back-link {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #6b7280;
            text-decoration: none;
            margin-bottom: 28px;
            transition: color 0.15s;
        }

        .back-link:hover { color: #059669; }

        .form-header { margin-bottom: 28px; }

        .form-header h2 {
            font-size: 26px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 6px;
        }

        .form-header p { font-size: 14px; color: #6b7280; }
        .form-header p a { color: #059669; font-weight: 600; text-decoration: none; }
        .form-header p a:hover { text-decoration: underline; }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group { margin-bottom: 16px; }
        .form-group.full { grid-column: 1 / -1; }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-input {
            width: 100%;
            padding: 11px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
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

        select.form-input { cursor: pointer; }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #059669;
            margin: 20px 0 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #d1fae5;
        }

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
            margin-top: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(5,150,105,0.4);
        }

        .terms-note {
            font-size: 11px;
            color: #9ca3af;
            text-align: center;
            margin-top: 12px;
            line-height: 1.5;
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

        .field-error {
            font-size: 11px;
            color: #dc2626;
            margin-top: 4px;
        }

        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { padding: 32px 24px; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="left-panel">
    <div class="left-content">
        <div class="left-logo">🌱</div>
        <h1 class="left-title">Empieza gratis hoy</h1>
        <p class="left-subtitle">Sin tarjeta de crédito.<br>Sin compromisos.</p>

        <div class="plan-card">
            <div class="plan-badge">Plan gratuito</div>
            <div class="plan-price">S/ 0 <span>/ semana</span></div>
            <ul class="plan-features">
                <li>5 generaciones con IA por semana</li>
                <li>Los 4 módulos disponibles</li>
                <li>Exportar Word con marca de agua</li>
                <li>Historial de sesiones</li>
                <li>Contexto andino de Huancavelica</li>
            </ul>
        </div>

        <p class="left-note">
            ¿Necesitas más? El plan Pro cuesta<br>
            <strong style="color:#6ee7b7;">S/ 25 / mes</strong> — generaciones ilimitadas.
        </p>
    </div>
</div>

<div class="right-panel">
    <div class="form-container">
        <a href="/" class="back-link">← Volver al inicio</a>

        <div class="form-header">
            <h2>Crea tu cuenta 🎉</h2>
            <p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
        </div>

        @if($errors->any())
        <div class="error-msg">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Datos personales --}}
            <div class="section-title">👤 Datos personales</div>

            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label" for="name">Nombre completo</label>
                    <input id="name" type="text" name="name" class="form-input"
                        placeholder="Ej: María Quispe Huamán"
                        value="{{ old('name') }}" required autofocus>
                    @error('name')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group full">
                    <label class="form-label" for="email">Correo electrónico</label>
                    <input id="email" type="email" name="email" class="form-input"
                        placeholder="tucorreo@ejemplo.com"
                        value="{{ old('email') }}" required>
                    @error('email')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Contraseña</label>
                    <input id="password" type="password" name="password" class="form-input"
                        placeholder="Mínimo 8 caracteres" required>
                    @error('password')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="form-input" placeholder="Repite tu contraseña" required>
                </div>
            </div>

            {{-- Datos profesionales --}}
            <div class="section-title">🏫 Datos profesionales</div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="ugel">UGEL</label>
                    <select id="ugel" name="ugel" class="form-input">
                        <option value="">— Seleccionar —</option>
                        <option value="UGEL Huancavelica"    {{ old('ugel')=='UGEL Huancavelica'    ?'selected':'' }}>UGEL Huancavelica</option>
                        <option value="UGEL Angaraes"        {{ old('ugel')=='UGEL Angaraes'        ?'selected':'' }}>UGEL Angaraes</option>
                        <option value="UGEL Acobamba"        {{ old('ugel')=='UGEL Acobamba'        ?'selected':'' }}>UGEL Acobamba</option>
                        <option value="UGEL Churcampa"       {{ old('ugel')=='UGEL Churcampa'       ?'selected':'' }}>UGEL Churcampa</option>
                        <option value="UGEL Tayacaja"        {{ old('ugel')=='UGEL Tayacaja'        ?'selected':'' }}>UGEL Tayacaja</option>
                        <option value="UGEL Huaytará"        {{ old('ugel')=='UGEL Huaytará'        ?'selected':'' }}>UGEL Huaytará</option>
                        <option value="UGEL Castrovirreyna"  {{ old('ugel')=='UGEL Castrovirreyna'  ?'selected':'' }}>UGEL Castrovirreyna</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="nivel">Nivel educativo</label>
                    <select id="nivel" name="nivel" class="form-input">
                        <option value="">— Seleccionar —</option>
                        <option value="Primaria"   {{ old('nivel')=='Primaria'   ?'selected':'' }}>Primaria</option>
                        <option value="Secundaria" {{ old('nivel')=='Secundaria' ?'selected':'' }}>Secundaria</option>
                        <option value="Ambos"      {{ old('nivel')=='Ambos'      ?'selected':'' }}>Ambos</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label class="form-label" for="area_docente">Área curricular</label>
                    <select id="area_docente" name="area_docente" class="form-input">
                        <option value="">— Seleccionar —</option>
                        <option value="Todas las áreas (primaria)" {{ old('area_docente')=='Todas las áreas (primaria)' ?'selected':'' }}>Todas las áreas (soy tutor de primaria)</option>
                        <option value="Matemática"                 {{ old('area_docente')=='Matemática'                 ?'selected':'' }}>Matemática</option>
                        <option value="Comunicación"               {{ old('area_docente')=='Comunicación'               ?'selected':'' }}>Comunicación</option>
                        <option value="Ciencia y Tecnología"       {{ old('area_docente')=='Ciencia y Tecnología'       ?'selected':'' }}>Ciencia y Tecnología</option>
                        <option value="Personal Social"            {{ old('area_docente')=='Personal Social'            ?'selected':'' }}>Personal Social</option>
                        <option value="Historia, Geografía y Economía" {{ old('area_docente')=='Historia, Geografía y Economía' ?'selected':'' }}>Historia, Geografía y Economía</option>
                        <option value="Desarrollo Personal y Cívica"   {{ old('area_docente')=='Desarrollo Personal y Cívica'   ?'selected':'' }}>Desarrollo Personal y Cívica</option>
                        <option value="Arte y Cultura"             {{ old('area_docente')=='Arte y Cultura'             ?'selected':'' }}>Arte y Cultura</option>
                        <option value="Educación Física"           {{ old('area_docente')=='Educación Física'           ?'selected':'' }}>Educación Física</option>
                        <option value="Inglés"                     {{ old('area_docente')=='Inglés'                     ?'selected':'' }}>Inglés</option>
                        <option value="Educación para el Trabajo"  {{ old('area_docente')=='Educación para el Trabajo'  ?'selected':'' }}>Educación para el Trabajo</option>
                        <option value="Educación Religiosa"        {{ old('area_docente')=='Educación Religiosa'        ?'selected':'' }}>Educación Religiosa</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label class="form-label" for="institucion">Institución Educativa (opcional)</label>
                    <input id="institucion" type="text" name="institucion" class="form-input"
                        placeholder="Ej: IE 36001 Huancavelica"
                        value="{{ old('institucion') }}">
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Crear mi cuenta gratis →
            </button>

            <p class="terms-note">
                Al registrarte aceptas nuestros términos de uso.<br>
                Tus datos son confidenciales y no se comparten.
            </p>
        </form>
    </div>
</div>

</body>
</html>