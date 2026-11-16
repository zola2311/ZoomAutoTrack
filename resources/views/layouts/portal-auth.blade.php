<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Client Portal') — Zoom EV</title>

    <!-- Theme Initializer -->
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

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <style>
        :root {
            --adminator-blue: #0d6efd;
            --adminator-bg: #f9fafb;
            --adminator-text: #374151;
            --adminator-border: #e5e7eb;
            --adminator-card-bg: #ffffff;
            --adminator-title: #111827;
            --adminator-muted: #6b7280;
            --adminator-hover-bg: #f1f5f9;
        }

        [data-bs-theme="dark"] {
            --adminator-bg: #0b0f19;
            --adminator-text: #94a3b8;
            --adminator-border: #1e293b;
            --adminator-card-bg: #111827;
            --adminator-title: #f8fafc;
            --adminator-muted: #64748b;
            --adminator-hover-bg: #1e293b;
        }

        body {
            background-color: var(--adminator-bg);
            color: var(--adminator-text);
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 30px 15px;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .auth-container {
            width: 100%;
            max-width: 460px;
            margin: 0 auto;
        }

        .auth-logo-box {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            border-radius: 10px;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 20px;
            margin-bottom: 12px;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
        }

        .auth-card {
            background: var(--adminator-card-bg);
            border: 1px solid var(--adminator-border);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            padding: 30px;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .demo-accounts-box {
            background-color: var(--adminator-hover-bg);
            border: 1px solid var(--adminator-border) !important;
            border-radius: 8px;
        }

        .btn-adminator-primary {
            background: #0d6efd;
            border: 1px solid #0d6efd;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 14px;
            border-radius: 6px;
            padding: 9px 16px;
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.25);
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
        }

        .btn-adminator-primary:hover {
            background: #0b5ed7;
            border-color: #0a58ca;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.35);
        }

        .btn-adminator-secondary {
            background: var(--adminator-card-bg);
            border: 1px solid var(--adminator-border);
            color: var(--adminator-title) !important;
            font-weight: 600;
            font-size: 12.5px;
            border-radius: 6px;
            padding: 6px 12px;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-adminator-secondary:hover {
            background: var(--adminator-hover-bg);
            border-color: #475569;
            color: var(--adminator-title) !important;
        }

        .input-group-text {
            background-color: var(--adminator-hover-bg);
            border-color: var(--adminator-border);
            color: var(--adminator-muted);
        }

        .theme-toggle-btn {
            background: none;
            border: 1px solid var(--adminator-border);
            color: var(--adminator-muted);
            font-size: 16px;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .theme-toggle-btn:hover {
            color: var(--adminator-title);
            background: var(--adminator-hover-bg);
        }

        [data-bs-theme="dark"] .text-dark {
            color: var(--adminator-title) !important;
        }
        [data-bs-theme="dark"] .bg-light {
            background-color: var(--adminator-hover-bg) !important;
            color: var(--adminator-muted) !important;
        }
        [data-bs-theme="dark"] .border {
            border-color: var(--adminator-border) !important;
        }
        [data-bs-theme="dark"] .form-control {
            background-color: #0b0f19;
            border-color: var(--adminator-border);
            color: var(--adminator-title);
        }
        [data-bs-theme="dark"] .form-control:focus {
            background-color: #0b0f19;
            border-color: #3b82f6;
            color: #ffffff;
        }
        [data-bs-theme="dark"] code {
            background-color: rgba(255, 255, 255, 0.1);
            color: #e2e8f0;
            padding: 2px 5px;
            border-radius: 4px;
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="auth-container">
        <!-- Logo & Title -->
        <div class="text-center mb-4 position-relative">
            <div class="position-absolute end-0 top-0">
                <button type="button" class="theme-toggle-btn" id="theme-toggle" title="Toggle Theme" aria-label="Toggle Theme">
                    <i class="ti ti-moon" id="theme-toggle-icon"></i>
                </button>
            </div>

            <a href="{{ route('home') }}" class="d-inline-block text-decoration-none text-dark">
                <span class="auth-logo-box">AT</span>
                <div class="h4 fw-bold text-dark mb-0">Zoom EV</div>
                <div class="text-muted small text-uppercase" style="font-size: 11px; letter-spacing: 0.08em;">
                    Digital Vehicle Service Portal
                </div>
            </a>
        </div>

        <!-- Auth Card -->
        <div class="auth-card">
            @yield('content')
        </div>

        <div class="text-center mt-4 text-muted small">
            &copy; {{ date('Y') }} Zoom EV. All rights reserved.
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
