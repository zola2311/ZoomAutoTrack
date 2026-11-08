@extends('layouts.portal-auth')

@section('title', 'Verify Your Email')

@section('content')
    <div class="text-center mb-3">
        <span class="icon-holder c-blue-500" style="width: 48px; height: 48px; font-size: 24px;">
            <i class="ti ti-mail-check"></i>
        </span>
    </div>

    <h2 class="h4 fw-bold text-dark text-center mb-2">Verify Your Email</h2>

    <p class="text-muted small text-center mb-4">
        We sent a verification link to your email address. Click the link in the message to activate full access to your account.
    </p>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-3 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="ti ti-circle-check fs-5 me-2 text-success"></i>
                <div class="small">{{ session('status') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('portal.verification.send') }}">
        @csrf
        <button type="submit" class="btn-adminator-primary mb-3">
            <i class="ti ti-refresh"></i> Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('portal.logout') }}">
        @csrf
        <button type="submit" class="btn btn-link w-100 text-muted small text-decoration-none">
            <i class="ti ti-logout me-1"></i> Sign Out
        </button>
    </form>
@endsection
