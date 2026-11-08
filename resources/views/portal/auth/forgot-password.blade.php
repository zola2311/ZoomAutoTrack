@extends('layouts.portal-auth')

@section('title', 'Forgot Password')

@section('content')
    <div class="text-center mb-3">
        <span class="icon-holder c-blue-500" style="width: 48px; height: 48px; font-size: 24px;">
            <i class="ti ti-lock-question"></i>
        </span>
    </div>
    <h2 class="h4 fw-bold text-dark text-center mb-1">Forgot your password?</h2>
    <p class="text-muted small text-center mb-4">Enter your registered email address and we'll send you a password reset link.</p>

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

    <form method="POST" action="{{ route('portal.password.email') }}" autocomplete="off">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Email address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="client@example.com" required autofocus>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn-adminator-primary">
                <i class="ti ti-send"></i> Send Reset Link
            </button>
        </div>
    </form>

    <div class="text-center text-muted small mt-3">
        <a href="{{ route('portal.login') }}" class="text-primary text-decoration-none">
            <i class="ti ti-arrow-left me-1"></i> Back to sign in
        </a>
    </div>
@endsection
