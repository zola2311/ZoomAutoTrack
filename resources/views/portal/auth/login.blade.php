@extends('layouts.portal-auth')

@section('title', 'Client Portal Sign In')

@section('content')
    <h2 class="h4 fw-bold text-dark text-center mb-1">Customer Portal Login</h2>
    <p class="text-muted small text-center mb-3">Sign in to manage your vehicles and view verified service passports.</p>

    <!-- Quick test account autofill buttons -->
    <div class="demo-accounts-box p-3 mb-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="text-muted text-uppercase fw-bold" style="font-size: 10.5px; letter-spacing: 0.05em;">
                <i class="ti ti-users text-primary me-1"></i> Demo Test Accounts
            </span>
            <span class="text-muted small" style="font-size: 11px;">Password: <code>password</code></span>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" onclick="quickFillCustomer('abebe.kebede@gmail.com', 'password')" class="btn-adminator-secondary">
                <i class="ti ti-user-check text-primary"></i> Abebe Kebede (5 Vehicles)
            </button>
            <button type="button" onclick="quickFillCustomer('happycreatives2025@gmail.com', 'password')" class="btn-adminator-secondary">
                <i class="ti ti-user text-muted"></i> Happy Creatives
            </button>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-3 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="ti ti-circle-check fs-5 me-2 text-success"></i>
                <div class="small">{{ session('status') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="ti ti-alert-circle fs-5 me-2 text-danger"></i>
                <div class="small">{{ $errors->first() }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form id="customerLoginForm" method="POST" action="{{ route('portal.login') }}" autocomplete="off">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Email address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" id="portalEmail" name="email" value="{{ old('email') }}" class="form-control" placeholder="client@example.com" required autofocus>
            </div>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label fw-semibold text-dark small mb-0">Password</label>
                <a href="{{ route('portal.password.request') }}" class="small text-primary text-decoration-none">Forgot password?</a>
            </div>
            <div class="input-group">
                <span class="input-group-text"><i class="ti ti-lock"></i></span>
                <input type="password" id="portalPassword" name="password" class="form-control" placeholder="Account password" required>
            </div>
        </div>

        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="rememberMe" checked>
                <label class="form-check-label text-muted small" for="rememberMe">Keep me signed in on this device</label>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn-adminator-primary">
                <i class="ti ti-login"></i> Sign In to Portal
            </button>
        </div>
    </form>

    <div class="text-center text-muted small mt-3">
        Don't have an account yet? <a href="{{ route('portal.register') }}" class="text-primary fw-semibold">Register here</a>
    </div>

    <div class="text-center mt-3 pt-3 border-top">
        <a href="/admin/login" class="small text-muted text-decoration-none">
            <i class="ti ti-shield-lock me-1 text-primary"></i> Workshop Staff Login &rarr;
        </a>
    </div>

    <script>
        function quickFillCustomer(email, password = 'password') {
            const emailEl = document.getElementById('portalEmail');
            const passEl = document.getElementById('portalPassword');
            if (emailEl) {
                emailEl.value = email;
            }
            if (passEl) {
                passEl.value = password;
            }
        }
    </script>
@endsection
