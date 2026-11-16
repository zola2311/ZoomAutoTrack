<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Customer Portal') — Zoom EV</title>

    <!-- Theme State Initializer (Prevents FOUC) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('portal_theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <!-- Bootstrap 5 & Google Fonts (Adminator Typography) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Icons (Themify / Tabler icon set) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <style>
        /* ==========================================================================
           Adminator Admin Dashboard Theme (puikinsh/Adminator-admin-dashboard)
           With Seamless Dark / Light Mode Support
           ========================================================================== */
        :root {
            --adminator-blue: #0d6efd;
            --adminator-blue-hover: #0b5ed7;
            --adminator-sidebar-w: 260px;
            --adminator-header-h: 65px;
            --adminator-bg: #f9fafb;
            --adminator-text: #374151;
            --adminator-border: #e5e7eb;
            --adminator-card-bg: #ffffff;
            --adminator-card-header: #ffffff;
            --adminator-title: #111827;
            --adminator-muted: #6b7280;
            --adminator-hover-bg: #f3f4f6;
            --adminator-footer-bg: #f8fafc;
        }

        [data-bs-theme="dark"] {
            --adminator-bg: #0b0f19;
            --adminator-text: #94a3b8;
            --adminator-border: #1e293b;
            --adminator-card-bg: #111827;
            --adminator-card-header: #111827;
            --adminator-title: #f8fafc;
            --adminator-muted: #64748b;
            --adminator-hover-bg: #1e293b;
            --adminator-footer-bg: #0b0f19;
        }

        * {
            box-sizing: border-box;
        }

        body.app {
            background-color: var(--adminator-bg);
            color: var(--adminator-text);
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* --------------------------------------------------------------------------
           1. Sidebar (Adminator Navigation Drawer)
           -------------------------------------------------------------------------- */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: var(--adminator-sidebar-w);
            background-color: var(--adminator-card-bg);
            border-right: 1px solid var(--adminator-border);
            z-index: 1000;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        /* Sidebar Logo */
        .sidebar-logo {
            height: var(--adminator-header-h);
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid var(--adminator-border);
            background: var(--adminator-card-bg);
        }

        .logo-box {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            border-radius: 8px;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 17px;
            flex-shrink: 0;
            margin-right: 12px;
            box-shadow: 0 2px 6px rgba(13, 110, 253, 0.3);
        }

        .logo-text {
            font-size: 16px;
            font-weight: 700;
            color: var(--adminator-title);
            letter-spacing: -0.01em;
            white-space: nowrap;
        }

        .logo-sub {
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--adminator-muted);
        }

        /* Sidebar Menu */
        .sidebar-menu {
            list-style: none;
            padding: 15px 0;
            margin: 0;
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-menu .nav-item {
            margin: 2px 10px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 11px 16px;
            color: var(--adminator-text);
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .sidebar-link:hover {
            color: var(--adminator-title);
            background-color: var(--adminator-hover-bg);
        }

        .sidebar-link.active {
            color: #0d6efd !important;
            background-color: #eff6ff !important;
            font-weight: 600;
        }

        [data-bs-theme="dark"] .sidebar-link.active {
            color: #60a5fa !important;
            background-color: rgba(59, 130, 246, 0.15) !important;
        }

        .icon-holder {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            margin-right: 12px;
            flex-shrink: 0;
            transition: all 0.15s ease;
        }

        /* Adminator Color Tokens */
        .c-blue-500 { color: #0d6efd; background-color: #eff6ff; }
        .c-green-500 { color: #10b981; background-color: #ecfdf5; }
        .c-purple-500 { color: #8b5cf6; background-color: #f5f3ff; }
        .c-amber-500 { color: #f59e0b; background-color: #fffbeb; }
        .c-red-500 { color: #ef4444; background-color: #fef2f2; }

        [data-bs-theme="dark"] .c-blue-500 { color: #60a5fa; background-color: rgba(59, 130, 246, 0.15); }
        [data-bs-theme="dark"] .c-green-500 { color: #34d399; background-color: rgba(16, 185, 129, 0.15); }
        [data-bs-theme="dark"] .c-purple-500 { color: #a78bfa; background-color: rgba(139, 92, 246, 0.15); }
        [data-bs-theme="dark"] .c-amber-500 { color: #fbbf24; background-color: rgba(245, 158, 11, 0.15); }
        [data-bs-theme="dark"] .c-red-500 { color: #f87171; background-color: rgba(239, 68, 68, 0.15); }

        .sidebar-footer-card {
            margin: 15px;
            padding: 12px;
            background: var(--adminator-footer-bg);
            border: 1px solid var(--adminator-border);
            border-radius: 8px;
        }

        /* --------------------------------------------------------------------------
           2. Page Container (Header + Main Content)
           -------------------------------------------------------------------------- */
        .page-container {
            margin-left: var(--adminator-sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Top Header Navbar */
        .header.navbar {
            height: var(--adminator-header-h);
            background: var(--adminator-card-bg);
            border-bottom: 1px solid var(--adminator-border);
            padding: 0 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 900;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .sidebar-toggle-btn, .theme-toggle-btn {
            background: none;
            border: 1px solid transparent;
            color: var(--adminator-muted);
            font-size: 18px;
            cursor: pointer;
            padding: 7px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .sidebar-toggle-btn:hover, .theme-toggle-btn:hover {
            color: var(--adminator-title);
            background: var(--adminator-hover-bg);
            border-color: var(--adminator-border);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 28px;
        }

        /* --------------------------------------------------------------------------
           3. Adminator UI Components (Cards, Buttons, Tables)
           -------------------------------------------------------------------------- */
        .adminator-card {
            background: var(--adminator-card-bg);
            border: 1px solid var(--adminator-border);
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            margin-bottom: 24px;
            overflow: hidden;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .adminator-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--adminator-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--adminator-card-header);
        }

        .adminator-card-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--adminator-title);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .adminator-card-body {
            padding: 20px;
        }

        /* Stat metric widgets */
        .stat-widget {
            background: var(--adminator-card-bg);
            border: 1px solid var(--adminator-border);
            border-radius: 10px;
            padding: 18px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            height: 100%;
            transition: transform 0.15s ease, background-color 0.2s ease, border-color 0.2s ease;
        }

        .stat-widget:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.06);
        }

        .stat-widget-title {
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--adminator-muted);
            margin-bottom: 6px;
        }

        .stat-widget-val {
            font-size: 26px;
            font-weight: 800;
            color: var(--adminator-title);
            line-height: 1.2;
        }

        /* Buttons (Adminator Style) */
        .btn-adminator-primary {
            background: #0d6efd;
            border: 1px solid #0d6efd;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 13.5px;
            border-radius: 6px;
            padding: 8px 16px;
            box-shadow: 0 1px 3px rgba(13, 110, 253, 0.25);
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-adminator-primary:hover {
            background: #0b5ed7;
            border-color: #0a58ca;
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(13, 110, 253, 0.35);
        }

        .btn-adminator-secondary {
            background: var(--adminator-card-bg);
            border: 1px solid var(--adminator-border);
            color: var(--adminator-title) !important;
            font-weight: 600;
            font-size: 13px;
            border-radius: 6px;
            padding: 7px 14px;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-adminator-secondary:hover {
            background: var(--adminator-hover-bg);
            border-color: #475569;
            color: var(--adminator-title) !important;
        }

        /* Ethiopian License Plate Component */
        .plate-badge-et {
            display: inline-flex;
            align-items: center;
            background: #ffffff;
            color: #0f172a;
            border: 1.5px solid #111827;
            border-radius: 4px;
            padding: 1px 7px;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 12px;
            letter-spacing: 0.05em;
            box-shadow: 0 1px 2px rgba(0,0,0,0.08);
            line-height: 1.4;
        }

        .plate-badge-et .region {
            color: #0d6efd;
            margin-right: 5px;
            padding-right: 5px;
            border-right: 1px solid #d1d5db;
            font-size: 11px;
        }

        /* Dark Theme Overrides & Utilities */
        [data-bs-theme="dark"] .text-dark {
            color: var(--adminator-title) !important;
        }
        [data-bs-theme="dark"] .bg-white {
            background-color: var(--adminator-card-bg) !important;
        }
        [data-bs-theme="dark"] .bg-light {
            background-color: var(--adminator-hover-bg) !important;
        }
        [data-bs-theme="dark"] .border {
            border-color: var(--adminator-border) !important;
        }
        [data-bs-theme="dark"] .border-bottom {
            border-color: var(--adminator-border) !important;
        }
        [data-bs-theme="dark"] .border-top {
            border-color: var(--adminator-border) !important;
        }
        [data-bs-theme="dark"] .border-end {
            border-color: var(--adminator-border) !important;
        }
        [data-bs-theme="dark"] .table {
            --bs-table-color: var(--adminator-text);
            --bs-table-bg: var(--adminator-card-bg);
            --bs-table-border-color: var(--adminator-border);
            --bs-table-hover-bg: var(--adminator-hover-bg);
            --bs-table-hover-color: var(--adminator-title);
        }
        [data-bs-theme="dark"] .table-light {
            --bs-table-bg: var(--adminator-hover-bg);
            --bs-table-color: var(--adminator-muted);
            border-color: var(--adminator-border);
        }
        [data-bs-theme="dark"] .list-group-item {
            background-color: var(--adminator-card-bg);
            border-color: var(--adminator-border);
            color: var(--adminator-text);
        }
        [data-bs-theme="dark"] .form-control, [data-bs-theme="dark"] .form-select {
            background-color: #0b0f19;
            border-color: var(--adminator-border);
            color: var(--adminator-title);
        }
        [data-bs-theme="dark"] .form-control:focus, [data-bs-theme="dark"] .form-select:focus {
            background-color: #0b0f19;
            border-color: #3b82f6;
            color: #ffffff;
        }
        [data-bs-theme="dark"] .input-group-text {
            background-color: #1e293b;
            border-color: var(--adminator-border);
            color: var(--adminator-muted);
        }
        [data-bs-theme="dark"] .dropdown-menu {
            background-color: var(--adminator-card-bg);
            border-color: var(--adminator-border);
        }
        [data-bs-theme="dark"] .dropdown-item {
            color: var(--adminator-text);
        }
        [data-bs-theme="dark"] .dropdown-item:hover {
            background-color: var(--adminator-hover-bg);
            color: var(--adminator-title);
        }

        /* Responsive sidebar collapse */
        body.app.is-collapsed .sidebar {
            width: 70px;
        }
        body.app.is-collapsed .sidebar .logo-text,
        body.app.is-collapsed .sidebar .logo-sub,
        body.app.is-collapsed .sidebar .title,
        body.app.is-collapsed .sidebar-footer-card {
            display: none !important;
        }
        body.app.is-collapsed .sidebar .icon-holder {
            margin-right: 0 auto;
        }
        body.app.is-collapsed .sidebar-link {
            justify-content: center;
            padding: 12px 0;
        }
        body.app.is-collapsed .page-container {
            margin-left: 70px;
        }

        @media (max-width: 768px) {
            .sidebar {
                left: -260px;
            }
            body.app.drawer-open .sidebar {
                left: 0;
            }
            .page-container {
                margin-left: 0 !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="app">

    <!-- 1. Adminator Sidebar Navigation -->
    <aside class="sidebar">
        <!-- Logo -->
        <div class="sidebar-logo">
            <a href="{{ route('portal.dashboard') }}" class="d-flex align-items-center">
                <span class="logo-box">AT</span>
                <div>
                    <div class="logo-text">Zoom EV</div>
                    <div class="logo-sub">Customer Portal</div>
                </div>
            </a>
        </div>

        <!-- Sidebar Menu Links -->
        <ul class="sidebar-menu">
            <li class="nav-item">
                <a class="sidebar-link {{ request()->routeIs('portal.dashboard') ? 'active' : '' }}" href="{{ route('portal.dashboard') }}">
                    <span class="icon-holder c-blue-500">
                        <i class="ti ti-dashboard"></i>
                    </span>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="sidebar-link {{ request()->routeIs('portal.history') ? 'active' : '' }}" href="{{ route('portal.history') }}">
                    <span class="icon-holder c-purple-500">
                        <i class="ti ti-file-certificate"></i>
                    </span>
                    <span class="title">Service History</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="sidebar-link {{ request()->routeIs('portal.appointments.*') ? 'active' : '' }}" href="{{ route('portal.appointments.index') }}">
                    <span class="icon-holder c-amber-500">
                        <i class="ti ti-calendar"></i>
                    </span>
                    <span class="title">Appointments</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="sidebar-link {{ request()->routeIs('quotation.*') ? 'active' : '' }}" href="{{ route('quotation.create') }}">
                    <span class="icon-holder c-deep-orange-500">
                        <i class="ti ti-calculator"></i>
                    </span>
                    <span class="title">Get Price Quote</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="sidebar-link {{ request()->routeIs('portal.profile') ? 'active' : '' }}" href="{{ route('portal.profile') }}">
                    <span class="icon-holder c-green-500">
                        <i class="ti ti-user-cog"></i>
                    </span>
                    <span class="title">Account Settings</span>
                </a>
            </li>
        </ul>

        <!-- Workshop Status Card (Adminator footer widget) -->
        <div class="sidebar-footer-card">
            <div class="d-flex align-items-center mb-1">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; display: inline-block; margin-right: 6px;"></span>
                <span class="fw-bold" style="font-size: 12px; color: var(--adminator-title);">Zoom EV Bole (HQ)</span>
            </div>
            <div class="text-muted small" style="font-size: 11px;">
                Bole Road, Addis Ababa
            </div>
            <div class="text-muted small mt-1" style="font-size: 11px;">
                <i class="ti ti-phone me-1"></i> +251 11 123 4567
            </div>
        </div>
    </aside>

    <!-- 2. Adminator Page Container (Header + Main Viewport) -->
    <div class="page-container">

        <!-- Top Navigation Bar -->
        <header class="header navbar">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="sidebar-toggle-btn" id="sidebar-toggle" title="Toggle Navigation">
                    <i class="ti ti-menu-2"></i>
                </button>
                <div class="d-none d-md-flex align-items-center gap-2">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 11.5px; font-weight: 600;">
                        <i class="ti ti-shield-check me-1"></i> Official Customer Portal
                    </span>
                    <span class="text-muted small">&bull; Zoom EV</span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <a href="{{ route('home') }}" target="_blank" class="text-muted small text-decoration-none d-none d-sm-inline-flex align-items-center" title="Open Public Website">
                    <i class="ti ti-external-link me-1"></i> Website
                </a>

                <!-- Dark / Light Theme Toggle -->
                <button type="button" class="theme-toggle-btn" id="theme-toggle" title="Toggle Dark/Light Mode" aria-label="Toggle theme">
                    <i class="ti ti-moon" id="theme-toggle-icon"></i>
                </button>

                <a href="{{ route('portal.appointments.create') }}" class="btn-adminator-primary">
                    <i class="ti ti-calendar-plus"></i> <span class="d-none d-sm-inline">Book Service</span>
                </a>

                <!-- User Dropdown Menu -->
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <div style="width: 34px; height: 34px; border-radius: 50%; background: #eff6ff; color: #0d6efd; font-weight: 700; display: flex; align-items: center; justify-content: center; font-size: 13px; border: 1px solid #bfdbfe;">
                            {{ strtoupper(substr(auth('customer')->user()->full_name, 0, 2)) }}
                        </div>
                        <div class="d-none d-lg-block text-start ms-2">
                            <div class="fw-semibold lh-1" style="font-size: 13px; color: var(--adminator-title);">{{ auth('customer')->user()->full_name }}</div>
                            <div class="text-muted small mt-0.5 font-monospace" style="font-size: 10.5px;">{{ auth('customer')->user()->customer_code ?: 'CUST-' . str_pad(auth('customer')->user()->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border mt-2">
                        <li><h6 class="dropdown-header">Verified Client Account</h6></li>
                        <li><a class="dropdown-item" href="{{ route('portal.profile') }}"><i class="ti ti-user me-2 text-muted"></i> Account Settings</a></li>
                        <li><a class="dropdown-item" href="{{ route('portal.appointments.index') }}"><i class="ti ti-calendar me-2 text-muted"></i> Appointments</a></li>
                        <li><a class="dropdown-item" href="{{ route('portal.history') }}"><i class="ti ti-history me-2 text-muted"></i> Service History</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('portal.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="ti ti-logout me-2"></i> Sign Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Main Viewport -->
        <main class="main-content">
            <div id="mainContent" class="container-fluid p-0">
                
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-circle-check fs-4 me-2 text-success"></i>
                            <div>{{ session('status') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- Adminator Footer -->
        <footer class="py-3 px-4 text-center border-top small" style="background-color: var(--adminator-card-bg); border-color: var(--adminator-border) !important; color: var(--adminator-muted);">
            &copy; {{ date('Y') }} <strong style="color: var(--adminator-title);">Zoom EV</strong>. Digital Garage Management & Vehicle Service History System.
        </footer>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Sidebar Toggle
            const toggleBtn = document.getElementById('sidebar-toggle');
            toggleBtn?.addEventListener('click', function (e) {
                e.preventDefault();
                if (window.innerWidth <= 768) {
                    document.body.classList.toggle('drawer-open');
                } else {
                    document.body.classList.toggle('is-collapsed');
                }
            });

            // 2. Dark / Light Theme Toggle
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeToggleIcon = document.getElementById('theme-toggle-icon');

            function syncThemeIcon(theme) {
                if (!themeToggleIcon) return;
                if (theme === 'dark') {
                    themeToggleIcon.className = 'ti ti-sun text-warning';
                    themeToggleBtn.setAttribute('title', 'Switch to Light Mode');
                } else {
                    themeToggleIcon.className = 'ti ti-moon';
                    themeToggleBtn.setAttribute('title', 'Switch to Dark Mode');
                }
            }

            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            syncThemeIcon(currentTheme);

            themeToggleBtn?.addEventListener('click', function (e) {
                e.preventDefault();
                const active = document.documentElement.getAttribute('data-bs-theme') || 'light';
                const target = active === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-bs-theme', target);
                localStorage.setItem('portal_theme', target);
                syncThemeIcon(target);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
