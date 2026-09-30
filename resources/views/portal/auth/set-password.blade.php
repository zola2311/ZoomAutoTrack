@extends('layouts.portal-auth')

@section('title', 'Set your password')

@section('content')
    <div class="text-center mb-3">
        <i class="ti ti-shield-lock" style="font-size:40px;color:#0C447C"></i>
    </div>
    <h2 class="h2 text-center mb-4">Set your password</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('portal.password.update') }}" autocomplete="off">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label class="form-label">Email address</label>
            <div class="input-group input-group-flat">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" name="email" value="{{ old('email', $email) }}" class="form-control" required autofocus>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">New password</label>
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
                <i class="ti ti-check me-1"></i> Set password &amp; continue
            </button>
        </div>
    </form>
@endsection
