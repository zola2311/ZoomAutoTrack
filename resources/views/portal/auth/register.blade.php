@extends('layouts.portal-auth')

@section('title', 'Client Registration')

@section('content')
    <h2 class="h4 fw-bold text-dark text-center mb-1">Create Customer Account</h2>
    <p class="text-muted small text-center mb-4">Register to access verified service passports and book garage services.</p>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="ti ti-alert-circle fs-5 me-2 text-danger"></i>
                <div class="small">{{ $errors->first() }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('portal.register') }}" autocomplete="off">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Full Legal Name <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="ti ti-user"></i></span>
                <input type="text" name="full_name" value="{{ old('full_name') }}" class="form-control" placeholder="Abebe Kebede" required autofocus>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Contact Phone <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="ti ti-phone"></i></span>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="09XXXXXXXX" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Email Address <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="client@example.com" required>
            </div>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label fw-semibold text-dark small mb-0">Referral Code</label>
                <span class="text-muted small" style="font-size: 11px;">Optional</span>
            </div>
            <div class="input-group">
                <span class="input-group-text"><i class="ti ti-gift"></i></span>
                <input type="text" name="referral_code" value="{{ old('referral_code', $ref ?? '') }}" class="form-control font-monospace" placeholder="e.g. AB12CD">
            </div>
            <div class="text-muted small mt-1" style="font-size: 11.5px;">Enter an invitation code to earn 500 bonus loyalty points upon first service.</div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-sm-6">
                <label class="form-label fw-semibold text-dark small">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                </div>
            </div>
            <div class="col-sm-6">
                <label class="form-label fw-semibold text-dark small">Confirm Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-lock-check"></i></span>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn-adminator-primary">
                <i class="ti ti-user-plus"></i> Register Account
            </button>
        </div>
    </form>

    <div class="text-center text-muted small mt-3">
        Already have an account? <a href="{{ route('portal.login') }}" class="text-primary fw-semibold">Sign in here</a>
    </div>
@endsection
