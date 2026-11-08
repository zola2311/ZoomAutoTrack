@extends('layouts.portal')

@section('title', 'Account Settings & Profile')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h4 fw-bold text-dark mb-1">Account Settings</h1>
            <div class="text-muted small">Manage your client profile, contact details, and password credentials.</div>
        </div>
        <div>
            <span class="badge bg-success-subtle text-success border border-success-subtle py-1.5 px-2.5 fw-semibold" style="font-size: 12px;">
                <i class="ti ti-shield-check me-1"></i> Verified Account
            </span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: User Summary Card -->
        <div class="col-lg-4">
            <div class="adminator-card mb-4">
                <div class="adminator-card-body text-center p-4">
                    <div style="width: 68px; height: 68px; border-radius: 50%; background: #eff6ff; color: #0d6efd; font-size: 26px; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; border: 2px solid #bfdbfe; margin-bottom: 14px;">
                        {{ strtoupper(substr($customer->full_name, 0, 2)) }}
                    </div>
                    <h2 class="h5 fw-bold text-dark mb-1">{{ $customer->full_name }}</h2>
                    <div class="text-muted small font-monospace mb-3">{{ $customer->email }}</div>

                    <div class="mb-3">
                        @if ($customer->loyalty_points >= 1000)
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-bold" style="font-size: 11.5px;">
                                <i class="ti ti-award me-1"></i> {{ number_format($customer->loyalty_points) }} Points (Gold Tier)
                            </span>
                        @elseif ($customer->loyalty_points >= 500)
                            <span class="badge bg-info-subtle text-info border border-info-subtle fw-bold" style="font-size: 11.5px;">
                                <i class="ti ti-award me-1"></i> {{ number_format($customer->loyalty_points) }} Points (Silver Tier)
                            </span>
                        @else
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 11.5px;">
                                <i class="ti ti-award me-1"></i> {{ number_format($customer->loyalty_points) }} Loyalty Points
                            </span>
                        @endif
                    </div>

                    <div class="p-2.5 rounded border text-start mb-2" style="background: var(--adminator-footer-bg); border-color: var(--adminator-border) !important;">
                        <div class="text-muted text-uppercase fw-bold mb-1" style="font-size: 10px; letter-spacing: 0.05em;">Client ID</div>
                        <div class="fw-bold font-monospace text-dark">
                            {{ $customer->customer_code ?: 'CUST-' . str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>

                    <div class="p-2.5 rounded border text-start" style="background: var(--adminator-footer-bg); border-color: var(--adminator-border) !important;">
                        <div class="text-muted text-uppercase fw-bold mb-1" style="font-size: 10px; letter-spacing: 0.05em;">Referral Code</div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fw-bold text-primary font-monospace fs-4">{{ $customer->referral_code }}</span>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 11px;">{{ $customer->referrals()->count() }} Joined</span>
                        </div>
                    </div>
                </div>

                <div class="adminator-card-footer p-3 bg-light border-top">
                    <div class="row g-2 text-center">
                        <div class="col-6 border-end">
                            <div class="h4 fw-bold text-dark mb-0">{{ $customer->vehicles->count() }}</div>
                            <div class="text-muted small" style="font-size: 11px;">Registered Fleet</div>
                        </div>
                        <div class="col-6">
                            <div class="h4 fw-bold text-dark mb-0">{{ $customer->jobCards()->whereNotNull('completed_at')->count() }}</div>
                            <div class="text-muted small" style="font-size: 11px;">Completed Visits</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Workshop -->
            <div class="adminator-card">
                <div class="adminator-card-header">
                    <h2 class="adminator-card-title">
                        <i class="ti ti-building text-primary"></i> Assigned Workshop
                    </h2>
                </div>
                <div class="adminator-card-body small text-muted">
                    <div class="fw-bold text-dark mb-1">Zoom AutoTrack Bole (HQ)</div>
                    <div>Bole Road, Addis Ababa, Ethiopia</div>
                    <div class="mt-2 text-dark"><i class="ti ti-phone me-1 text-primary"></i>+251 11 123 4567</div>
                </div>
            </div>
        </div>

        <!-- Right Column: Edit Profile Form -->
        <div class="col-lg-8">
            <div class="adminator-card">
                <div class="adminator-card-header">
                    <h2 class="adminator-card-title">
                        <i class="ti ti-user-edit text-primary"></i> Update Profile Information
                    </h2>
                </div>
                <div class="adminator-card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                            <div class="d-flex align-items-start">
                                <span class="icon-holder c-red-500 me-3" style="width: 32px; height: 32px; font-size: 18px;">
                                    <i class="ti ti-alert-circle"></i>
                                </span>
                                <div>
                                    <div class="fw-bold mb-1">Please review the highlighted errors:</div>
                                    <ul class="mb-0 ps-3 small">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('portal.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Full Legal Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="ti ti-user"></i></span>
                                    <input type="text" name="full_name" value="{{ old('full_name', $customer->full_name) }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Contact Phone <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="ti ti-phone"></i></span>
                                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="ti ti-mail"></i></span>
                                <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="form-control" required>
                            </div>
                            <div class="text-muted small mt-1.5" style="font-size: 11.5px;">This email is used for login credentials and maintenance status updates.</div>
                        </div>

                        <hr class="my-4" style="border-color: #e5e7eb;">

                        <h3 class="h6 fw-bold text-dark mb-3">
                            <i class="ti ti-lock me-1 text-primary"></i> Change Security Password
                        </h3>
                        <p class="text-muted small mb-3" style="font-size: 12.5px;">Leave password fields blank if you do not wish to change your current password.</p>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark">Current Password</label>
                                <input type="password" name="current_password" class="form-control" placeholder="Current password">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark">New Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Min 8 characters">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn-adminator-primary">
                                <i class="ti ti-check"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
