@extends('layouts.portal-auth')

@section('title', 'Verify your email')

@section('content')
    <h2 class="h2 text-center mb-3">Verify your email</h2>

    <p class="text-secondary text-center mb-4">
        We sent a verification link to your email address. Click it to activate full access to your account.
    </p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('portal.verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary w-100 mb-3">Resend verification email</button>
    </form>

    <form method="POST" action="{{ route('portal.logout') }}">
        @csrf
        <button type="submit" class="btn btn-link w-100">Logout</button>
    </form>
@endsection
