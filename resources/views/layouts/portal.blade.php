<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'My Vehicles')  — AutoTrack Ethiopia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0/dist/css/tabler.min.css">
</head>
<body>
<div class="page">
    <header class="navbar navbar-expand-md navbar-dark d-print-none" style="background:#0C447C">
        <div class="container-xl">
            <a href="{{ route('portal.dashboard') }}" class="navbar-brand">AutoTrack Ethiopia</a>

            <div class="navbar-nav flex-row ms-auto">
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link d-flex lh-1 text-white text-decoration-none" data-bs-toggle="dropdown">
                        {{ auth('customer')->user()->full_name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <form method="POST" action="{{ route('portal.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="navbar-expand-md">
        <div class="collapse navbar-collapse" style="background:var(--tblr-bg-surface)">
            <div class="container-xl">
                <ul class="navbar-nav me-3">
                    <li class="nav-item me-3">
                        <a class="nav-link {{ request()->routeIs('portal.dashboard') ? 'active' : '' }}" href="{{ route('portal.dashboard') }}">My Vehicles</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link {{ request()->routeIs('portal.history') ? 'active' : '' }}" href="{{ route('portal.history') }}">Service History</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link {{ request()->routeIs('portal.appointments.*') ? 'active' : '' }}" href="{{ route('portal.appointments.index') }}">Appointments</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-wrapper">
        <div class="page-body">
            <div class="container-xl">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</div>
@stack('scripts')
</body>
</html>
