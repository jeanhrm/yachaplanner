<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>YachaPlanner — {{ config('app.name', 'YachaPlanner') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f0fdf4; margin: 0; }

        /* NAV PRINCIPAL */
        .yp-nav {
            background: #fff;
            border-bottom: 1px solid #d1fae5;
            box-shadow: 0 1px 8px rgba(5,150,105,0.08);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .yp-nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .yp-nav-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-size: 18px;
            font-weight: 800;
            color: #059669;
            letter-spacing: -0.3px;
        }

        .yp-nav-logo span { color: #111827; }

        .yp-nav-logo .logo-icon {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, #059669, #047857);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            box-shadow: 0 2px 8px rgba(5,150,105,0.3);
        }

        .yp-nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .yp-nav-link {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.15s;
            border: none;
            background: none;
            cursor: pointer;
            font-family: inherit;
        }

        .yp-nav-link:hover {
            background: #f0fdf4;
            color: #059669;
        }

        .yp-nav-link.active {
            background: #d1fae5;
            color: #059669;
            font-weight: 600;
        }

        .yp-nav-divider {
            width: 1px;
            height: 20px;
            background: #e5e7eb;
            margin: 0 8px;
        }

        /* DROPDOWN USUARIO */
        .yp-user-menu { position: relative; }

        .yp-user-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 10px;
            border: 2px solid #d1fae5;
            background: #f0fdf4;
            cursor: pointer;
            transition: all 0.15s;
            font-family: inherit;
        }

        .yp-user-btn:hover {
            border-color: #059669;
            background: #d1fae5;
        }

        .yp-user-avatar {
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #059669, #047857);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .yp-user-name {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .yp-user-chevron {
            font-size: 10px;
            color: #9ca3af;
            transition: transform 0.2s;
        }

        .yp-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: #fff;
            border: 1px solid #d1fae5;
            border-radius: 14px;
            box-shadow: 0 8px 32px rgba(5,150,105,0.15);
            min-width: 220px;
            padding: 8px;
            display: none;
            z-index: 100;
        }

        .yp-dropdown.open { display: block; }

        .yp-dropdown-header {
            padding: 10px 12px 12px;
            border-bottom: 1px solid #f0fdf4;
            margin-bottom: 6px;
        }

        .yp-dropdown-name {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 2px;
        }

        .yp-dropdown-email {
            font-size: 12px;
            color: #9ca3af;
        }

        .yp-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            text-decoration: none;
            transition: all 0.15s;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            font-family: inherit;
            text-align: left;
        }

        .yp-dropdown-item:hover { background: #f0fdf4; color: #059669; }

        .yp-dropdown-item.danger:hover { background: #fef2f2; color: #dc2626; }

        .yp-dropdown-divider {
            height: 1px;
            background: #f0fdf4;
            margin: 6px 0;
        }

        /* HEADER DE PÁGINA */
        .yp-page-header {
            background: #fff;
            border-bottom: 1px solid #d1fae5;
        }

        .yp-page-header-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }
    </style>
</head>
<body>

{{-- NAVEGACIÓN PRINCIPAL --}}
<nav class="yp-nav">
    <div class="yp-nav-inner">

        {{-- Logo --}}
        <a href="{{ route('dashboard') }}" class="yp-nav-logo">
            <div class="logo-icon">🌱</div>
            Yacha<span>Planner</span>
        </a>

        {{-- Links --}}
        <div class="yp-nav-links">
            <a href="{{ route('dashboard') }}" class="yp-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                🏠 Inicio
            </a>
            <a href="{{ route('chat.index') }}" class="yp-nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                ✍️ Planificador
            </a>
            <a href="{{ route('credits.index') }}" class="yp-nav-link {{ request()->routeIs('credits.*') ? 'active' : '' }}">
                ⚡ Créditos
            </a>
            <div class="yp-nav-divider"></div>

            {{-- Usuario --}}
            <div class="yp-user-menu" id="userMenu">
                <button class="yp-user-btn" onclick="toggleDropdown()">
                    <div class="yp-user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="yp-user-name">{{ auth()->user()->name }}</span>
                    <span class="yp-user-chevron" id="chevron">▼</span>
                </button>

                <div class="yp-dropdown" id="dropdown">
                    <div class="yp-dropdown-header">
                        <div class="yp-dropdown-name">{{ auth()->user()->name }}</div>
                        <div class="yp-dropdown-email">{{ auth()->user()->email }}</div>
                        @if(auth()->user()->area_docente)
                        <div style="margin-top:4px;font-size:11px;color:#059669;font-weight:600;">
                            {{ auth()->user()->area_docente }}
                            @if(auth()->user()->ugel) · {{ auth()->user()->ugel }} @endif
                        </div>
                        @endif
                    </div>

                    <a href="{{ route('profile.edit') }}" class="yp-dropdown-item">
                        👤 Mi perfil
                    </a>
                    <a href="{{ route('chat.index') }}" class="yp-dropdown-item">
                        ✍️ Planificador
                    </a>

                    <div class="yp-dropdown-divider"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="yp-dropdown-item danger">
                            🚪 Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- HEADER DE PÁGINA (slot) --}}
@isset($header)
<div class="yp-page-header">
    <div class="yp-page-header-inner">
        {{ $header }}
    </div>
</div>
@endisset

{{-- CONTENIDO --}}
<main>
    {{ $slot }}
</main>

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dropdown');
        const chevron = document.getElementById('chevron');
        dropdown.classList.toggle('open');
        chevron.style.transform = dropdown.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0)';
    }

    // Cerrar al hacer clic fuera
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('userMenu');
        if (menu && !menu.contains(e.target)) {
            document.getElementById('dropdown').classList.remove('open');
            document.getElementById('chevron').style.transform = 'rotate(0)';
        }
    });
</script>

</body>
</html>