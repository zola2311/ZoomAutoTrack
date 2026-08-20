@extends('layouts.portal-auth')

@section('title', 'Sign up')

@section('content')
    <h2 class="h2 text-center mb-4">Create your account</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
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
            <input type="text" name="full_name" value="{{ old('full_name') }}" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="you@example.com" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">Create account</button>
        </div>
    </form>

    <div class="text-center text-secondary mt-3">
        Already have an account? <a href="{{ route('portal.login') }}">Sign in</a>
    </div>
@endsection
