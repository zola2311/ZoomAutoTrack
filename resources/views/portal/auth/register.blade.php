@extends('layouts.portal-auth')

@section('title', 'Sign up')

@section('content')
    <h2 class="h2 text-center mb-1">Create your account</h2>
    <p class="text-secondary text-center mb-4">Track your vehicles and service history online</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('portal.register') }}" autocomplete="off">
        @csrf

        <div class="mb-3">
            <label class="form-label">Full name</label>
            <div class="input-group input-group-flat">
                <span class="input-group-text"><i class="ti ti-user"></i></span>
                <input type="text" name="full_name" value="{{ old('full_name') }}" class="form-control" required autofocus>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <div class="input-group input-group-flat">
                <span class="input-group-text"><i class="ti ti-phone"></i></span>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Email address</label>
            <div class="input-group input-group-flat">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="you@example.com" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Referral code <span class="text-secondary">(optional)</span></label>
            <div class="input-group input-group-flat">
                <span class="input-group-text"><i class="ti ti-gift"></i></span>
                <input type="text" name="referral_code" value="{{ old('referral_code', $ref ?? '') }}" class="form-control" placeholder="e.g. AB12CD">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group input-group-flat">
                <span class="input-group-text"><i class="ti ti-lock"></i></span>
                <input type="password" name="password" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm password</label>
            <div class="input-group input-group-flat">
                <span class="input-group-text"><i class="ti ti-lock-check"></i></span>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">
                <i class="ti ti-user-plus me-1"></i> Create account
            </button>
        </div>
    </form>

    <div class="text-center text-secondary mt-3">
        Already have an account? <a href="{{ route('portal.login') }}">Sign in</a>
    </div>
@endsection
