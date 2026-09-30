@extends('layouts.portal-auth')

@section('title', 'Set your password')

@section('content')
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
            <input type="email" name="email" value="{{ old('email', $email) }}" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label class="form-label">New password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">Set password &amp; continue</button>
        </div>
    </form>
@endsection
