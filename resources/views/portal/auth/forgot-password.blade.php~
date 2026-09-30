@extends('layouts.portal-auth')

@section('title', 'Forgot password')

@section('content')
    <h2 class="h2 text-center mb-4">Forgot your password?</h2>
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
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">Send reset link</button>
        </div>
    </form>

    <div class="text-center text-secondary mt-3">
        <a href="{{ route('portal.login') }}">Back to login</a>
    </div>
@endsection
