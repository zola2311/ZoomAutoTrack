@extends('layouts.portal-auth')

@section('title', 'Login')

@section('content')
    <h2 class="h2 text-center mb-1">Welcome back</h2>
    <p class="text-secondary text-center mb-4">Log in to view your vehicles and service history</p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-center">
            <i class="ti ti-alert-circle me-2"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('portal.login') }}" autocomplete="off">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email address</label>
            <div class="input-group input-group-flat">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="you@example.com" required autofocus>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label">
                Password
                <a href="{{ route('portal.password.request') }}" class="float-end small text-secondary">Forgot password?</a>
            </label>
            <div class="input-group input-group-flat">
                <span class="input-group-text"><i class="ti ti-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Your password" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-check">
                <input type="checkbox" name="remember" class="form-check-input">
                <span class="form-check-label">Remember me</span>
            </label>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">
                <i class="ti ti-login me-1"></i> Sign in
            </button>
        </div>
    </form>

    <div class="text-center text-secondary mt-3">
        Don't have an account yet? <a href="{{ route('portal.register') }}">Sign up</a>
    </div>
@endsection
