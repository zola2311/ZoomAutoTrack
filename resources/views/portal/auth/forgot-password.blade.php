@extends('layouts.portal-auth')

@section('title', 'Forgot password')

@section('content')
    <div class="text-center mb-3">
        <i class="ti ti-lock-question" style="font-size:40px;color:#0C447C"></i>
    </div>
    <h2 class="h2 text-center mb-1">Forgot your password?</h2>
    <p class="text-secondary text-center mb-4">Enter your email and we'll send you a reset link.</p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('portal.password.email') }}" autocomplete="off">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email address</label>
            <div class="input-group input-group-flat">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
            </div>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">
                <i class="ti ti-send me-1"></i> Send reset link
            </button>
        </div>
    </form>

    <div class="text-center text-secondary mt-3">
        <a href="{{ route('portal.login') }}"><i class="ti ti-arrow-left"></i> Back to login</a>
    </div>
@endsection
