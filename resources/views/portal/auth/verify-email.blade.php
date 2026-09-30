@extends('layouts.portal-auth')

@section('title', 'Verify your email')

@section('content')
    <div class="text-center mb-3">
        <i class="ti ti-mail-check" style="font-size:44px;color:#0C447C"></i>
    </div>

    <h2 class="h2 text-center mb-3">Verify your email</h2>

    <p class="text-secondary text-center mb-4">
        We sent a verification link to your email address. Click it to activate full access to your account.
    </p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('portal.verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary w-100 mb-3">
            <i class="ti ti-refresh me-1"></i> Resend verification email
        </button>
    </form>

    <form method="POST" action="{{ route('portal.logout') }}">
        @csrf
        <button type="submit" class="btn btn-link w-100 text-secondary">
            <i class="ti ti-logout me-1"></i> Logout
        </button>
    </form>
@endsection
