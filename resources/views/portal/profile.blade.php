@extends('layouts.portal')

@section('title', 'My Profile')

@section('content')
    <h2 class="page-title mb-3 mt-4"><i class="ti ti-user me-2"></i>My Profile</h2>

    <div class="row">
        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar avatar-xl mb-3"
                         style="background:#0C447C;color:#fff;font-size:32px;font-weight:700;border-radius:50%;width:80px;height:80px;display:inline-flex;align-items:center;justify-content:center;">
                        {{ strtoupper(substr($customer->full_name, 0, 1)) }}
                    </div>
                    <h3 class="mb-1">{{ $customer->full_name }}</h3>
                    <div class="text-secondary small mb-2">{{ $customer->email }}</div>
                    <div class="mb-2">
                        <span class="badge bg-yellow-lt">
                            <i class="ti ti-star me-1"></i>{{ $customer->loyalty_points }} loyalty points
                        </span>
                    </div>
                    <div class="text-secondary small">
                        <i class="ti ti-git-branch me-1"></i>Referral code: <strong>{{ $customer->referral_code }}</strong>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row g-2 text-center">
                        <div class="col-6">
                            <div class="fw-bold">{{ $customer->vehicles->count() }}</div>
                            <div class="text-secondary small">Vehicles</div>
                        </div>
                        <div class="col-6">
                            <div class="fw-bold">{{ $customer->jobCards()->whereNotNull('completed_at')->count() }}</div>
                            <div class="text-secondary small">Visits</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Update your details</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('portal.profile.update') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Full name</label>
                            <div class="input-group input-group-flat">
                                <span class="input-group-text"><i class="ti ti-user"></i></span>
                                <input type="text" name="full_name" value="{{ old('full_name', $customer->full_name) }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <div class="input-group input-group-flat">
                                <span class="input-group-text"><i class="ti ti-phone"></i></span>
                                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <div class="input-group input-group-flat">
                                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="form-control" required>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h4 class="mb-3 text-secondary small text-uppercase">Change password <span class="fw-normal">(leave blank to keep current)</span></h4>

                        <div class="mb-3">
                            <label class="form-label">Current password</label>
                            <div class="input-group input-group-flat">
                                <span class="input-group-text"><i class="ti ti-lock"></i></span>
                                <input type="password" name="current_password" class="form-control" placeholder="Enter current password">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New password</label>
                            <div class="input-group input-group-flat">
                                <span class="input-group-text"><i class="ti ti-lock-plus"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm new password</label>
                            <div class="input-group input-group-flat">
                                <span class="input-group-text"><i class="ti ti-lock-check"></i></span>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i> Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
