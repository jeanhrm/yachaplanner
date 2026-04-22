<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div style="max-width:900px;margin:60px auto;padding:0 24px;text-align:center;">

        <div style="font-size:64px;margin-bottom:16px;">🌱</div>
        <h1 style="font-size:28px;font-weight:700;color:#111827;margin-bottom:8px;">
            Bienvenido, {{ auth()->user()->name }}
        </h1>
        <p style="font-size:16px;color:#6b7280;margin-bottom:40px;">
            Tienes <strong style="color:#059669;">{{ auth()->user()->remainingCredits() }} créditos</strong> disponibles esta semana.
        </p>

        <!-- Botón principal -->
        <a href="{{ route('chat.index') }}"
            style="display:inline-flex;align-items:center;gap:12px;background:#059669;color:#fff;padding:18px 40px;border-radius:12px;font-size:18px;font-weight:700;text-decoration:none;transition:all 0.15s;box-shadow:0 8px 24px rgba(5,150,105,0.3);">
            ✍️ Ir al Planificador
        </a>

        <!-- Módulos rápidos -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-top:48px;">
            <a href="{{ route('chat.index') }}"
                style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;text-decoration:none;transition:all 0.15s;"
                onmouseover="this.style.borderColor='#059669'" onmouseout="this.style.borderColor='#e5e7eb'">
                <div style="font-size:28px;margin-bottom:8px;">📅</div>
                <div style="font-size:13px;font-weight:600;color:#111827;">Prog. Bimestral</div>
                <div style="font-size:12px;color:#9ca3af;margin-top:4px;">Planifica tu bimestre</div>
            </a>
            <a href="{{ route('chat.index') }}"
                style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;text-decoration:none;transition:all 0.15s;"
                onmouseover="this.style.borderColor='#059669'" onmouseout="this.style.borderColor='#e5e7eb'">
                <div style="font-size:28px;margin-bottom:8px;">📝</div>
                <div style="font-size:13px;font-weight:600;color:#111827;">Sesión de Aprendizaje</div>
                <div style="font-size:12px;color:#9ca3af;margin-top:4px;">Crea tu sesión</div>
            </a>
            <a href="{{ route('chat.index') }}"
                style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;text-decoration:none;transition:all 0.15s;"
                onmouseover="this.style.borderColor='#059669'" onmouseout="this.style.borderColor='#e5e7eb'">
                <div style="font-size:28px;margin-bottom:8px;">🔬</div>
                <div style="font-size:13px;font-weight:600;color:#111827;">Proyecto STEAM</div>
                <div style="font-size:12px;color:#9ca3af;margin-top:4px;">Diseña tu proyecto</div>
            </a>
            <a href="{{ route('chat.index') }}"
                style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;text-decoration:none;transition:all 0.15s;"
                onmouseover="this.style.borderColor='#059669'" onmouseout="this.style.borderColor='#e5e7eb'">
                <div style="font-size:28px;margin-bottom:8px;">✅</div>
                <div style="font-size:13px;font-weight:600;color:#111827;">Rúbrica</div>
                <div style="font-size:12px;color:#9ca3af;margin-top:4px;">Evalúa con criterios</div>
            </a>
        </div>

        <!-- Info de cuenta -->
        <div style="margin-top:48px;background:#f9fafb;border-radius:12px;padding:24px;text-align:left;display:flex;gap:32px;flex-wrap:wrap;">
            <div>
                <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;margin-bottom:4px;">Plan actual</div>
                <div style="font-size:15px;font-weight:600;color:#111827;">Gratuito</div>
            </div>
            <div>
                <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;margin-bottom:4px;">Créditos esta semana</div>
                <div style="font-size:15px;font-weight:600;color:#059669;">{{ auth()->user()->remainingCredits() }} disponibles</div>
            </div>
            <div>
                <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;margin-bottom:4px;">Cuenta</div>
                <div style="font-size:15px;font-weight:600;color:#111827;">{{ auth()->user()->email }}</div>
            </div>
        </div>

    </div>
</x-app-layout>