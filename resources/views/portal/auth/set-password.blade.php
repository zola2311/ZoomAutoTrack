@extends('layouts.portal-auth')

@section('title', 'Set Your Password')

@section('content')
    <div class="text-center mb-3">
        <span class="icon-holder c-blue-500" style="width: 48px; height: 48px; font-size: 24px;">
            <i class="ti ti-shield-lock"></i>
        </span>
    </div>
    <h2 class="h4 fw-bold text-dark text-center mb-1">Set Your Password</h2>
    <p class="text-muted small text-center mb-4">Create a secure password to access your client portal.</p>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="ti ti-alert-circle fs-5 me-2 text-danger"></i>
                <div class="small">{{ $errors->first() }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('portal.password.update') }}" autocomplete="off">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Email address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" name="email" value="{{ old('email', $email) }}" class="form-control" required autofocus>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">New password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="ti ti-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Confirm password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="ti ti-lock-check"></i></span>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn-adminator-primary">
                <i class="ti ti-check"></i> Set Password &amp; Continue
            </button>
        </div>
    </form>
@endsection
