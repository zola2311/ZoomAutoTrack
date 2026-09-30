<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'My Portal') — AutoTrack Ethiopia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0/dist/css/tabler.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <style>
        .navbar-brand-logo {
            width: 32px; height: 32px; border-radius: 8px;
            background: rgba(255,255,255,0.2); color: #fff;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 700; margin-right: 8px; flex-shrink: 0;
        }
        .avatar-circle {
            width: 36px; height: 36px; border-radius: 50%;
            background: rgba(255,255,255,0.2); color: #fff;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 600; flex-shrink: 0;
        }

        /* Collapsed sidebar */
        .navbar-vertical.collapsed {
            width: 4rem !important;
        }
        .navbar-vertical.collapsed .navbar-brand-text,
        .navbar-vertical.collapsed .nav-link-title,
        .navbar-vertical.collapsed .user-info,
        .navbar-vertical.collapsed .navbar-brand-logo {
            display: none !important;
        }
        .navbar-vertical.collapsed .nav-link {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        .navbar-vertical.collapsed .nav-link-icon {
            margin: 0 auto;
        }
        .navbar-vertical.collapsed .sidebar-footer {
            justify-content: center;
            padding: 0.75rem 0;
        }
        .navbar-vertical.collapsed .sidebar-toggle-icon {
            transform: rotate(180deg);
        }

        /* Sidebar transitions */
        .navbar-vertical {
            transition: width 0.2s ease;
        }
        .page-wrapper {
            transition: margin-left 0.2s ease;
        }

        /* Toggle button */
        .sidebar-toggle-btn {
            background: none; border: none; color: rgba(255,255,255,0.7);
            cursor: pointer; padding: 4px 8px; border-radius: 6px;
            line-height: 1;
        }
        .sidebar-toggle-btn:hover { color: #fff; background: rgba(255,255,255,0.1); }
    </style>
</head>
<body class="antialiased">
<div class="wrapper">

    <aside class="navbar navbar-vertical navbar-expand-lg" id="portal-sidebar" data-bs-theme="dark" style="background:#0C447C">
        <div class="container-fluid flex-column h-100 p-0">

            {{-- Top: brand + toggle --}}
            <div class="d-flex align-items-center w-100 px-3 py-3">
                <a href="{{ route('portal.dashboard') }}" class="d-flex align-items-center text-white text-decoration-none flex-fill overflow-hidden">
                    <span class="navbar-brand-logo flex-shrink-0">A</span>
                    <span class="navbar-brand-text fw-semibold text-truncate">AutoTrack Ethiopia</span>
                </a>
                <button class="sidebar-toggle-btn ms-2 d-none d-lg-block" id="sidebarToggle" title="Collapse sidebar">
                    <i class="ti ti-layout-sidebar-left-collapse sidebar-toggle-icon" style="font-size:20px"></i>
                </button>
                {{-- Mobile toggle --}}
                <button class="navbar-toggler d-lg-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            {{-- Nav items --}}
            <div class="collapse navbar-collapse flex-column flex-fill" id="navbar-menu">
                <ul class="navbar-nav w-100 px-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portal.dashboard') ? 'active' : '' }}"
                           href="{{ route('portal.dashboard') }}">
                            <span class="nav-link-icon"><i class="ti ti-home"></i></span>
                            <span class="nav-link-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portal.history') ? 'active' : '' }}"
                           href="{{ route('portal.history') }}">
                            <span class="nav-link-icon"><i class="ti ti-history"></i></span>
                            <span class="nav-link-title">Service History</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portal.appointments.*') ? 'active' : '' }}"
                           href="{{ route('portal.appointments.index') }}">
                            <span class="nav-link-icon"><i class="ti ti-calendar-event"></i></span>
                            <span class="nav-link-title">Appointments</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portal.profile') ? 'active' : '' }}"
                           href="{{ route('portal.profile') }}">
                            <span class="nav-link-icon"><i class="ti ti-user"></i></span>
                            <span class="nav-link-title">My Profile</span>
                        </a>
                    </li>
                </ul>

                {{-- Bottom: user + logout --}}
                <div class="mt-auto w-100 border-top border-white border-opacity-10">
                    <div class="d-flex align-items-center gap-2 px-3 py-3 sidebar-footer">
                        <span class="avatar-circle flex-shrink-0">
                            {{ strtoupper(substr(auth('customer')->user()->full_name, 0, 1)) }}
                        </span>
                        <div class="flex-fill overflow-hidden user-info">
                            <div class="text-white fw-semibold text-truncate small">{{ auth('customer')->user()->full_name }}</div>
                            <div class="text-white-50 text-truncate" style="font-size:11px">{{ auth('customer')->user()->email }}</div>
                        </div>
                        <form method="POST" action="{{ route('portal.logout') }}" class="flex-shrink-0">
                            @csrf
                            <button type="submit" class="sidebar-toggle-btn" title="Logout">
                                <i class="ti ti-logout" style="font-size:18px"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <div class="page-wrapper">
        <div class="page-body">
            <div class="container-xl">
                @if (session('status'))
                    <div class="alert alert-success d-flex align-items-center mt-3">
                        <i class="ti ti-circle-check me-2"></i>
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

        <footer class="footer footer-transparent d-print-none">
            <div class="container-xl">
                <p class="text-secondary mb-0 text-center">
                    &copy; {{ date('Y') }} AutoTrack Ethiopia. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0/dist/js/tabler.min.js"></script>
<script>
    const sidebar = document.getElementById('portal-sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    // Restore state
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebar.classList.add('collapsed');
    }

    toggleBtn?.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    });
</script>
@stack('scripts')
</body>
</html>
