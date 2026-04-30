<x-app-layout>
    <x-slot name="header">
        <div style="padding:16px 0;display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:18px;font-weight:700;color:#111827;">⚡ Obtener más créditos</h2>
        </div>
    </x-slot>

    <style>
        .credits-layout { max-width: 900px; margin: 40px auto; padding: 0 24px; }

        .credits-status {
            background: linear-gradient(135deg, #059669, #047857);
            border-radius: 20px;
            padding: 28px 32px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
            box-shadow: 0 8px 24px rgba(5,150,105,0.3);
        }

        .credits-status-left h3 {
            font-size: 14px;
            font-weight: 500;
            color: rgba(255,255,255,0.8);
            margin-bottom: 6px;
        }

        .credits-status-left .credit-num {
            font-size: 48px;
            font-weight: 800;
            line-height: 1;
        }

        .credits-status-left .credit-label {
            font-size: 13px;
            color: rgba(255,255,255,0.7);
            margin-top: 4px;
        }

        .credits-status-right {
            text-align: right;
        }

        .credits-status-right .plan-name {
            font-size: 12px;
            font-weight: 600;
            background: rgba(255,255,255,0.2);
            padding: 4px 14px;
            border-radius: 20px;
            margin-bottom: 8px;
            display: inline-block;
        }

        .credits-status-right p {
            font-size: 13px;
            color: rgba(255,255,255,0.8);
            line-height: 1.5;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .plan-card {
            background: #fff;
            border-radius: 20px;
            padding: 28px;
            border: 2px solid #e5e7eb;
            position: relative;
            transition: all 0.2s;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }

        .plan-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(5,150,105,0.12);
            border-color: #a7f3d0;
        }

        .plan-card.featured {
            border-color: #059669;
            box-shadow: 0 8px 32px rgba(5,150,105,0.2);
        }

        .plan-card.featured::before {
            content: '⭐ MÁS POPULAR';
            position: absolute;
            top: -13px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #059669, #047857);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 16px;
            border-radius: 20px;
            letter-spacing: 0.08em;
            white-space: nowrap;
        }

        .plan-name {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #9ca3af;
            margin-bottom: 8px;
        }

        .plan-price {
            font-size: 36px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 4px;
            line-height: 1;
        }

        .plan-price span {
            font-size: 15px;
            font-weight: 500;
            color: #9ca3af;
        }

        .plan-desc {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f3f4f6;
            line-height: 1.5;
        }

        .plan-features {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 24px;
        }

        .plan-features li {
            font-size: 13px;
            color: #374151;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            line-height: 1.4;
        }

        .plan-features li::before {
            content: '✓';
            color: #059669;
            font-weight: 700;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .btn-whatsapp {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 13px;
            background: #25d366;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            font-family: inherit;
            box-shadow: 0 4px 12px rgba(37,211,102,0.3);
        }

        .btn-whatsapp:hover {
            background: #22c55e;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37,211,102,0.4);
        }

        .btn-whatsapp-outline {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 13px;
            background: #fff;
            color: #25d366;
            border: 2px solid #25d366;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-whatsapp-outline:hover {
            background: #f0fdf4;
            transform: translateY(-1px);
        }

        .how-section {
            background: #fff;
            border-radius: 20px;
            padding: 32px;
            border: 1px solid #d1fae5;
            box-shadow: 0 2px 12px rgba(5,150,105,0.06);
        }

        .how-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .how-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
        }

        .how-step {
            text-align: center;
            padding: 16px;
            background: #f0fdf4;
            border-radius: 12px;
            border: 1px solid #d1fae5;
        }

        .how-step .step-icon { font-size: 28px; margin-bottom: 8px; }

        .how-step h4 {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }

        .how-step p {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.5;
        }
    </style>

    <div class="credits-layout">

        {{-- Estado actual --}}
        <div class="credits-status">
            <div class="credits-status-left">
                <h3>Créditos disponibles esta semana</h3>
                <div class="credit-num">{{ $user->remainingCredits() }}</div>
                <div class="credit-label">de 5 créditos de prueba (plan gratuito)</div>
            </div>
            <div class="credits-status-right">
                <div class="plan-name">Plan Gratuito</div>
                <p>Se reinician<br>cada lunes</p>
            </div>
        </div>

        {{-- Planes --}}
        <div class="plans-grid">

            {{-- Plan Pro --}}
            <div class="plan-card featured">
                <div class="plan-name">Pro Docente</div>
                <div class="plan-price">S/ 25 <span>/ mes</span></div>
                <div class="plan-desc">Para docentes que planifican semanalmente sin límites.</div>
                <ul class="plan-features">
                    <li>Generaciones ilimitadas todo el mes</li>
                    <li>Los 4 módulos disponibles</li>
                    <li>Word sin marca de agua</li>
                    <li>Historial completo de sesiones</li>
                    <li>Soporte prioritario por WhatsApp</li>
                </ul>
                <a href="{{ $whatsappPro }}" target="_blank" class="btn-whatsapp">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.117.549 4.103 1.508 5.835L.057 23.203c-.088.352.043.724.328.964.19.157.424.24.662.24.104 0 .209-.016.312-.049l5.578-1.499A11.95 11.95 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.852 0-3.626-.49-5.163-1.341l-.37-.213-3.821 1.027 1.004-3.7-.228-.381A9.944 9.944 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                    Escribir por WhatsApp → S/ 25
                </a>
            </div>

            {{-- Plan Institución --}}
            <div class="plan-card">
                <div class="plan-name">Institución</div>
                <div class="plan-price">S/ 350 <span>/ año</span></div>
                <div class="plan-desc">Para IEs y UGELs con varios docentes trabajando juntos.</div>
                <ul class="plan-features">
                    <li>Hasta 30 docentes con plan Pro</li>
                    <li>Panel del director</li>
                    <li>Contexto institucional propio</li>
                    <li>Factura para UGEL o municipio</li>
                    <li>Capacitación incluida</li>
                    <li>Soporte dedicado</li>
                </ul>
                <a href="{{ $whatsappInstitucion }}" target="_blank" class="btn-whatsapp-outline">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.117.549 4.103 1.508 5.835L.057 23.203c-.088.352.043.724.328.964.19.157.424.24.662.24.104 0 .209-.016.312-.049l5.578-1.499A11.95 11.95 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.852 0-3.626-.49-5.163-1.341l-.37-.213-3.821 1.027 1.004-3.7-.228-.381A9.944 9.944 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                    Consultar por WhatsApp → S/ 350
                </a>
            </div>
        </div>

        {{-- Cómo funciona --}}
        <div class="how-section">
            <div class="how-title">📱 ¿Cómo funciona el pago?</div>
            <div class="how-steps">
                <div class="how-step">
                    <div class="step-icon">💬</div>
                    <h4>1. Escríbenos</h4>
                    <p>Haz clic en el botón y envía un mensaje por WhatsApp</p>
                </div>
                <div class="how-step">
                    <div class="step-icon">📲</div>
                    <h4>2. Paga por Yape</h4>
                    <p>Te enviamos el número de Yape para transferir</p>
                </div>
                <div class="how-step">
                    <div class="step-icon">📸</div>
                    <h4>3. Envía tu captura</h4>
                    <p>Mándanos la captura del pago por WhatsApp</p>
                </div>
                <div class="how-step">
                    <div class="step-icon">⚡</div>
                    <h4>4. Activamos tu plan</h4>
                    <p>En menos de 1 hora tu cuenta queda activa</p>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>