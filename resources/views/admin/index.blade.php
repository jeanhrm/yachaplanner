<x-app-layout>
    <x-slot name="header">
        <div style="padding:16px 0;display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:18px;font-weight:700;color:#111827;">🛡️ Panel de Administración</h2>
            <span style="font-size:12px;color:#6b7280;">Solo acceso autorizado</span>
        </div>
    </x-slot>

    <style>
        .admin-layout { max-width: 1200px; margin: 32px auto; padding: 0 24px; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px 24px;
            border: 1px solid #d1fae5;
            box-shadow: 0 2px 8px rgba(5,150,105,0.06);
        }

        .stat-card .stat-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #9ca3af;
            margin-bottom: 6px;
        }

        .stat-card .stat-num {
            font-size: 32px;
            font-weight: 800;
            color: #059669;
            line-height: 1;
        }

        .stat-card .stat-sub {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        .search-bar {
            background: #fff;
            border-radius: 14px;
            padding: 16px 20px;
            border: 1px solid #d1fae5;
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .search-input {
            flex: 1;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s;
        }

        .search-input:focus { border-color: #059669; }

        .btn-search {
            padding: 10px 20px;
            background: #059669;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
        }

        .users-table {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #d1fae5;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(5,150,105,0.06);
        }

        .table-header {
            background: linear-gradient(135deg, #059669, #047857);
            padding: 14px 20px;
            display: grid;
            grid-template-columns: 2fr 2fr 1fr 1fr 1fr 1.5fr;
            gap: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255,255,255,0.9);
        }

        .table-row {
            padding: 14px 20px;
            display: grid;
            grid-template-columns: 2fr 2fr 1fr 1fr 1fr 1.5fr;
            gap: 12px;
            align-items: center;
            border-bottom: 1px solid #f0fdf4;
            transition: background 0.15s;
            font-size: 13px;
        }

        .table-row:hover { background: #fafffe; }
        .table-row:last-child { border-bottom: none; }

        .user-info { display: flex; flex-direction: column; gap: 2px; }
        .user-name { font-weight: 600; color: #111827; }
        .user-meta { font-size: 11px; color: #9ca3af; }

        .plan-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-free        { background: #f3f4f6; color: #6b7280; }
        .badge-pro         { background: #d1fae5; color: #065f46; }
        .badge-institution { background: #ede9fe; color: #5b21b6; }

        .credits-display {
            font-weight: 600;
            color: #059669;
            font-size: 13px;
        }

        .actions-cell { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }

        .plan-select {
            border: 1.5px solid #d1fae5;
            border-radius: 8px;
            padding: 5px 8px;
            font-size: 12px;
            color: #374151;
            font-family: inherit;
            background: #fff;
            cursor: pointer;
            outline: none;
        }

        .plan-select:focus { border-color: #059669; }

        .btn-save {
            padding: 5px 12px;
            background: #059669;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.15s;
        }

        .btn-save:hover { background: #047857; }

        .btn-credits {
            padding: 5px 10px;
            background: #fff;
            color: #059669;
            border: 1.5px solid #a7f3d0;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.15s;
        }

        .btn-credits:hover { background: #f0fdf4; }

        .credits-input {
            width: 50px;
            border: 1.5px solid #d1fae5;
            border-radius: 8px;
            padding: 5px 8px;
            font-size: 12px;
            font-family: inherit;
            outline: none;
            text-align: center;
        }

        .alert-success {
            background: #d1fae5;
            border: 1px solid #a7f3d0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            color: #065f46;
            font-weight: 500;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pagination-wrapper {
            padding: 16px 20px;
            border-top: 1px solid #f0fdf4;
            display: flex;
            justify-content: center;
        }
    </style>

    <div class="admin-layout">

        @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        {{-- Stats --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total usuarios</div>
                <div class="stat-num">{{ $stats['total'] }}</div>
                <div class="stat-sub">registrados</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Plan gratuito</div>
                <div class="stat-num">{{ $stats['free'] }}</div>
                <div class="stat-sub">docentes</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Plan Pro</div>
                <div class="stat-num" style="color:#059669;">{{ $stats['pro'] }}</div>
                <div class="stat-sub">docentes activos</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Instituciones</div>
                <div class="stat-num" style="color:#7c3aed;">{{ $stats['institution'] }}</div>
                <div class="stat-sub">IEs activas</div>
            </div>
        </div>

        {{-- Búsqueda --}}
        <form method="GET" action="{{ route('admin.index') }}" class="search-bar">
            <input type="text" name="search" class="search-input"
                placeholder="Buscar por nombre, correo o UGEL..."
                value="{{ $search }}">
            <button type="submit" class="btn-search">🔍 Buscar</button>
            @if($search)
            <a href="{{ route('admin.index') }}"
                style="font-size:13px;color:#6b7280;text-decoration:none;padding:10px;">
                ✕ Limpiar
            </a>
            @endif
        </form>

        {{-- Tabla --}}
        <div class="users-table">
            <div class="table-header">
                <div>Docente</div>
                <div>Área / UGEL</div>
                <div>Plan</div>
                <div>Créditos</div>
                <div>Registro</div>
                <div>Acciones</div>
            </div>

            @forelse($users as $user)
            <div class="table-row">

                {{-- Info --}}
                <div class="user-info">
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-meta">{{ $user->email }}</div>
                </div>

                {{-- Área / UGEL --}}
                <div class="user-info">
                    <div style="font-size:12px;color:#374151;font-weight:500;">
                        {{ $user->area_docente ?? '—' }}
                    </div>
                    <div class="user-meta">{{ $user->ugel ?? '—' }}</div>
                </div>

                {{-- Plan --}}
                <div>
                    @php $plan = $user->plan ?? 'free'; @endphp
                    <span class="plan-badge badge-{{ $plan }}">
                        {{ $plan === 'free' ? 'Gratuito' : ($plan === 'pro' ? 'Pro' : 'Institución') }}
                    </span>
                </div>

                {{-- Créditos --}}
                <div class="credits-display">
                    {{ $user->remainingCredits() }}
                    <span style="font-size:11px;color:#9ca3af;font-weight:400;">disponibles</span>
                </div>

                {{-- Fecha --}}
                <div style="font-size:12px;color:#9ca3af;">
                    {{ $user->created_at->format('d/m/Y') }}
                </div>

                {{-- Acciones --}}
                <div class="actions-cell">
                    {{-- Cambiar plan --}}
                    <form method="POST"
                        action="{{ route('admin.updatePlan', $user) }}"
                        style="display:flex;gap:4px;align-items:center;">
                        @csrf
                        <select name="plan" class="plan-select">
                            <option value="free"        {{ ($user->plan ?? 'free') === 'free'        ? 'selected' : '' }}>Gratuito</option>
                            <option value="pro"         {{ ($user->plan ?? 'free') === 'pro'         ? 'selected' : '' }}>Pro</option>
                            <option value="institution" {{ ($user->plan ?? 'free') === 'institution' ? 'selected' : '' }}>Institución</option>
                        </select>
                        <button type="submit" class="btn-save">Guardar</button>
                    </form>

                    {{-- Agregar créditos --}}
                    <form method="POST"
                        action="{{ route('admin.addCredits', $user) }}"
                        style="display:flex;gap:4px;align-items:center;">
                        @csrf
                        <input type="number" name="credits" class="credits-input"
                            placeholder="+" min="1" max="100" value="5">
                        <button type="submit" class="btn-credits">+Créditos</button>
                    </form>
                </div>
            </div>
            @empty
            <div style="padding:40px;text-align:center;color:#9ca3af;font-size:14px;">
                No se encontraron usuarios.
            </div>
            @endforelse

            @if($users->hasPages())
            <div class="pagination-wrapper">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>