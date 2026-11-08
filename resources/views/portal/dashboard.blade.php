@extends('layouts.portal')

@section('title', 'Dashboard')

@section('content')

    <!-- Welcome Hero Card (Adminator Style) -->
    <div class="adminator-card mb-4" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #ffffff; border: none; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.15);">
        <div class="adminator-card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                        <span class="badge" style="background: rgba(255,255,255,0.15); color: #ffffff; font-family: 'JetBrains Mono', monospace; font-size: 11px;">
                            <i class="ti ti-shield-check me-1"></i> ID: {{ $customer->customer_code ?: 'CUST-' . str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                        @if ($customer->loyalty_points >= 1000)
                            <span class="badge" style="background: #f59e0b; color: #111827; font-weight: 700; font-size: 11px;">
                                <i class="ti ti-award me-1"></i> Gold Tier Member
                            </span>
                        @elseif ($customer->loyalty_points >= 500)
                            <span class="badge" style="background: #38bdf8; color: #0f172a; font-weight: 700; font-size: 11px;">
                                <i class="ti ti-award me-1"></i> Silver Tier Member
                            </span>
                        @else
                            <span class="badge" style="background: rgba(255,255,255,0.12); color: #e2e8f0; font-size: 11px;">
                                <i class="ti ti-check me-1"></i> Standard Member
                            </span>
                        @endif
                    </div>

                    <h1 class="h3 text-white fw-bold mb-2">Welcome back, {{ $customer->full_name }}</h1>
                    <p class="text-white-50 mb-3" style="max-width: 620px; font-size: 13.5px; line-height: 1.6;">
                        Manage your vehicle fleet, access verified digital service passports, track monotonic odometer readings, and schedule authorized garage maintenance.
                    </p>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('portal.appointments.create') }}" class="btn-adminator-primary" style="background: #0d6efd; border-color: #0d6efd;">
                            <i class="ti ti-calendar-plus"></i> Schedule Service
                        </a>
                        <a href="{{ route('portal.history') }}" class="btn-adminator-secondary" style="background: rgba(255,255,255,0.12); border-color: rgba(255,255,255,0.25); color: #ffffff !important;">
                            <i class="ti ti-history"></i> Service Logbook
                        </a>
                        <a href="{{ route('portal.appointments.index') }}" class="btn-adminator-secondary" style="background: rgba(255,255,255,0.12); border-color: rgba(255,255,255,0.25); color: #ffffff !important;">
                            <i class="ti ti-calendar"></i> My Appointments
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 d-none d-lg-block text-end">
                    <div style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); border-radius: 10px; padding: 18px; display: inline-block; text-align: left; min-width: 230px;">
                        <div class="text-white-50 small mb-1 text-uppercase fw-bold" style="font-size: 10.5px; letter-spacing: 0.05em;">Workshop Status</div>
                        <div class="d-flex align-items-center mb-2">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; display: inline-block; margin-right: 8px; box-shadow: 0 0 6px #10b981;"></span>
                            <span class="fw-bold text-white" style="font-size: 13px;">Online & Operational</span>
                        </div>
                        <div class="text-white-50 small" style="font-size: 11.5px;">
                            <i class="ti ti-map-pin me-1 text-info"></i> Zoom AutoTrack Bole (HQ)
                        </div>
                        <div class="text-white-50 small mt-1" style="font-size: 11.5px;">
                            <i class="ti ti-calendar me-1 text-info"></i> {{ now()->format('D, M j, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Adminator Stat Metric Widgets -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-widget">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-widget-title">Registered Fleet</div>
                    <span class="icon-holder c-blue-500 m-0">
                        <i class="ti ti-car"></i>
                    </span>
                </div>
                <div class="stat-widget-val">{{ $vehicles->count() }} <span class="text-muted fw-normal" style="font-size: 14px;">vehicles</span></div>
                <div class="d-flex align-items-center text-muted small mt-2" style="font-size: 12px;">
                    <span class="text-success fw-bold me-1"><i class="ti ti-shield-check"></i> 100%</span>
                    <span>Passports Active</span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="stat-widget">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-widget-title">Loyalty Points</div>
                    <span class="icon-holder c-amber-500 m-0">
                        <i class="ti ti-award"></i>
                    </span>
                </div>
                <div class="stat-widget-val">{{ number_format($customer->loyalty_points) }} <span class="text-muted fw-normal" style="font-size: 14px;">pts</span></div>
                <div class="text-muted small mt-2" style="font-size: 12px;">
                    Earn 100 points per 1,000 ETB
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="stat-widget">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-widget-title">Workshop Visits</div>
                    <span class="icon-holder c-green-500 m-0">
                        <i class="ti ti-check"></i>
                    </span>
                </div>
                <div class="stat-widget-val">{{ $totalVisits }} <span class="text-muted fw-normal" style="font-size: 14px;">visits</span></div>
                <div class="text-muted small mt-2" style="font-size: 12px;">
                    @if ($lastVisit)
                        Last: {{ $lastVisit->completed_at->format('M d, Y') }}
                    @else
                        No visits recorded yet
                    @endif
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="stat-widget">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-widget-title">Referral Code</div>
                    <span class="icon-holder c-purple-500 m-0">
                        <i class="ti ti-gift"></i>
                    </span>
                </div>
                <div class="stat-widget-val font-monospace" style="font-size: 22px;">{{ $customer->referral_code }}</div>
                <div class="text-muted small mt-2" style="font-size: 12px;">
                    <span class="text-primary fw-semibold">{{ $referralCount }} friends</span> joined
                </div>
            </div>
        </div>
    </div>

    <!-- Service Due Alert (if any vehicle needs maintenance) -->
    @php
        $reminders = $vehicles->map(fn ($v) => ['vehicle' => $v, 'due' => $v->nextServiceDue()])
            ->filter(fn ($r) => $r['due']['is_due'] || $r['due']['km_remaining'] <= 500);
    @endphp

    @if ($reminders->isNotEmpty())
        <div class="alert alert-warning alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-start">
                <span class="icon-holder c-amber-500 me-3" style="width: 36px; height: 36px; font-size: 20px;">
                    <i class="ti ti-bell-ringing"></i>
                </span>
                <div class="flex-grow-1">
                    <div class="fw-bold mb-1" style="font-size: 14.5px; color: #92400e;">Maintenance Attention Required</div>
                    <div class="d-flex flex-column gap-2 mt-2">
                        @foreach ($reminders as $r)
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="plate-badge-et">{{ $r['vehicle']->plate_number }}</span>
                                <span class="fw-semibold small text-dark">{{ $r['vehicle']->make }} {{ $r['vehicle']->model }}:</span>
                                @if ($r['due']['is_due'])
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Service Overdue</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">{{ number_format($r['due']['km_remaining']) }} km remaining</span>
                                @endif
                                <a href="{{ route('portal.appointments.create', ['vehicle_id' => $r['vehicle']->id]) }}" class="btn-adminator-primary py-1 px-2.5 ms-auto" style="font-size: 12px;">
                                    Book Service &rarr;
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Main 2-Column Split: Fleet Cards & Right Sidebar -->
    <div class="row g-4">
        
        <!-- Left Column: Registered Vehicles -->
        <div class="col-lg-7 col-xl-8">
            <div class="adminator-card">
                <div class="adminator-card-header">
                    <div>
                        <h2 class="adminator-card-title">
                            <i class="ti ti-car text-primary"></i> My Registered Vehicles
                        </h2>
                        <div class="text-muted small" style="font-size: 12px;">Certified records, live condition scores, and digital passports</div>
                    </div>
                    <div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold">
                            {{ $vehicles->count() }} Registered
                        </span>
                    </div>
                </div>

                @if ($vehicles->isEmpty())
                    <div class="adminator-card-body text-center py-5">
                        <div class="icon-holder c-blue-500 mx-auto mb-3" style="width: 52px; height: 52px; font-size: 26px;">
                            <i class="ti ti-car-off"></i>
                        </div>
                        <h4 class="fw-bold mb-1">No vehicles currently registered</h4>
                        <p class="text-muted small mb-3">When you service your vehicle at Zoom AutoTrack Bole, it will automatically appear here with a full digital passport.</p>
                        <a href="{{ route('portal.appointments.create') }}" class="btn-adminator-primary">
                            <i class="ti ti-calendar-plus"></i> Schedule First Visit
                        </a>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach ($vehicles as $vehicle)
                            @php
                                $due = $vehicle->nextServiceDue();
                                $health = $vehicle->latestHealthScores();
                                $overall = $health['Overall'] ?? null;
                                $healthStatus = $overall === null ? 'Pending Data' : ($overall >= 75 ? 'Optimal' : ($overall >= 50 ? 'Fair' : 'Attention Needed'));
                                $healthColor = $overall === null ? 'secondary' : ($overall >= 75 ? 'success' : ($overall >= 50 ? 'warning' : 'danger'));
                                $plateParts = explode(' ', $vehicle->plate_number);
                                $plateRegion = count($plateParts) > 1 ? $plateParts[0] : 'ET';
                                $plateNum = count($plateParts) > 1 ? implode(' ', array_slice($plateParts, 1)) : $vehicle->plate_number;
                            @endphp

                            <div class="list-group-item p-3 border-bottom">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="plate-badge-et">
                                            <span class="region">{{ $plateRegion }}</span>
                                            <span>{{ $plateNum }}</span>
                                        </span>

                                        @if ($due['is_due'])
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 11px;">
                                                <i class="ti ti-alert-triangle me-1"></i> Service Due
                                            </span>
                                        @else
                                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 11px;">
                                                <i class="ti ti-check me-1"></i> Roadworthy
                                            </span>
                                        @endif

                                        @if ($vehicle->fuel_type)
                                            <span class="badge bg-light text-secondary border text-uppercase" style="font-size: 10.5px;">{{ $vehicle->fuel_type }}</span>
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ $vehicle->passportUrl() }}" target="_blank" class="btn-adminator-secondary" style="font-size: 12px; padding: 5px 10px;">
                                            <i class="ti ti-id-badge-2 text-primary"></i> Passport
                                        </a>
                                        <a href="{{ route('passport.certificate', $vehicle->qr_code) }}" target="_blank" class="btn-adminator-secondary" style="font-size: 12px; padding: 5px 10px;">
                                            <i class="ti ti-certificate text-success"></i> Certificate
                                        </a>
                                    </div>
                                </div>

                                <div class="row align-items-center my-2">
                                    <div class="col-sm-7">
                                        <div class="h5 fw-bold text-dark mb-1">
                                            {{ $vehicle->make }} {{ $vehicle->model }}
                                            @if ($vehicle->year)
                                                <span class="text-muted fw-normal" style="font-size: 14px;">({{ $vehicle->year }})</span>
                                            @endif
                                        </div>
                                        <div class="text-muted small" style="font-size: 12px;">
                                            <i class="ti ti-dashboard me-1"></i> {{ number_format($vehicle->current_mileage) }} km &bull; VIN: <span class="font-monospace text-dark">{{ $vehicle->vin ?: 'Verified Record' }}</span>
                                        </div>
                                    </div>

                                    <div class="col-sm-5 mt-2 mt-sm-0 text-sm-end">
                                        <div class="d-inline-block text-start" style="min-width: 140px;">
                                            <div class="d-flex justify-content-between align-items-baseline mb-1">
                                                <span class="text-muted text-uppercase fw-bold" style="font-size: 10.5px; letter-spacing: 0.04em;">Health Score</span>
                                                <span class="fw-bold text-{{ $healthColor }}" style="font-size: 12.5px;">
                                                    {{ $overall ? $overall . '/100' : 'Pending' }}
                                                </span>
                                            </div>
                                            <div class="progress" style="height: 6px; background-color: #f1f5f9; border-radius: 4px;">
                                                <div class="progress-bar bg-{{ $healthColor }}" style="width: {{ $overall ?? 15 }}%; border-radius: 4px;"></div>
                                            </div>
                                            <div class="text-end mt-1">
                                                <span class="badge bg-{{ $healthColor }}-subtle text-{{ $healthColor }} border border-{{ $healthColor }}-subtle" style="font-size: 10px;">{{ $healthStatus }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if (!empty($health))
                                    <div class="p-2 rounded border my-2" style="background: var(--adminator-footer-bg); border-color: var(--adminator-border) !important;">
                                        <div class="text-muted text-uppercase fw-bold mb-1" style="font-size: 10px; letter-spacing: 0.04em;">Diagnostic Systems Assessment</div>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach ($health as $category => $score)
                                                @if ($category !== 'Overall')
                                                    @php $c = $score >= 75 ? 'success' : ($score >= 50 ? 'warning' : 'danger'); @endphp
                                                    <span class="badge bg-{{ $c }}-subtle text-{{ $c }} border border-{{ $c }}-subtle" style="font-size: 10.5px;">
                                                        {{ $category }}: {{ $score }}%
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-2 mt-2 border-top">
                                    <div class="text-muted small" style="font-size: 12px;">
                                        <i class="ti ti-clock me-1"></i>
                                        @if ($due['km_remaining'] > 0)
                                            Next service recommended in <strong class="text-dark">{{ number_format($due['km_remaining']) }} km</strong>
                                        @else
                                            <span class="text-danger fw-bold">Overdue for maintenance check</span>
                                        @endif
                                    </div>

                                    <a href="{{ route('portal.appointments.create', ['vehicle_id' => $vehicle->id]) }}" class="btn-adminator-primary" style="font-size: 12px; padding: 5px 12px;">
                                        <i class="ti ti-calendar-plus"></i> Book Service for this Vehicle
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Recent Visits, Referral Card, Guarantees -->
        <div class="col-lg-5 col-xl-4">
            
            <!-- Recent Completed Visits -->
            <div class="adminator-card mb-4">
                <div class="adminator-card-header">
                    <h2 class="adminator-card-title">
                        <i class="ti ti-history text-primary"></i> Recent Visits
                    </h2>
                    <div>
                        <a href="{{ route('portal.history') }}" class="btn-adminator-secondary" style="font-size: 11.5px; padding: 4px 8px;">
                            Full Logbook &rarr;
                        </a>
                    </div>
                </div>

                @if ($recentHistory->isEmpty())
                    <div class="adminator-card-body text-center py-4 text-muted small">
                        <i class="ti ti-clipboard-off d-block mb-1 fs-3 text-muted"></i>
                        No completed workshop services recorded yet.
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach ($recentHistory as $job)
                            <div class="list-group-item p-3 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-holder c-blue-500 m-0" style="width: 34px; height: 34px; font-size: 16px;">
                                        <i class="ti ti-tool"></i>
                                    </div>
                                    <div class="flex-grow-1 text-truncate">
                                        <div class="fw-semibold text-dark text-truncate" style="font-size: 13px;">
                                            {{ \Illuminate\Support\Str::limit($job->customer_complaint, 36) ?: 'Scheduled Maintenance & Inspection' }}
                                        </div>
                                        <div class="d-flex align-items-center gap-2 text-muted small mt-1" style="font-size: 11px;">
                                            <span class="badge bg-light text-dark border font-monospace">{{ $job->vehicle->plate_number }}</span>
                                            <span>{{ $job->completed_at->format('M d, Y') }}</span>
                                            <span>&bull;</span>
                                            <span>Job #{{ $job->job_number }}</span>
                                        </div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 10.5px;">Completed</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Referral Card (Adminator Style) -->
            <div class="adminator-card mb-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%); color: #ffffff; border: none; box-shadow: 0 4px 15px rgba(13, 110, 253, 0.25);">
                <div class="adminator-card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h3 class="h6 fw-bold text-white mb-0">Refer a Vehicle Owner</h3>
                        <span class="icon-holder c-purple-500 m-0" style="width: 32px; height: 32px; font-size: 16px; background: rgba(255,255,255,0.2); color: #ffffff;">
                            <i class="ti ti-gift"></i>
                        </span>
                    </div>
                    <div class="text-white-50 small mb-3" style="font-size: 12px; line-height: 1.5;">
                        Both you and your friend receive 500 bonus loyalty points upon their first garage service.
                    </div>

                    <div class="input-group mb-2">
                        <input type="text" class="form-control font-monospace" readonly
                               value="{{ route('portal.register', ['ref' => $customer->referral_code]) }}"
                               id="referralLink" style="font-size: 11.5px; background: #ffffff; color: #111827; font-weight: 600;">
                        <button class="btn btn-light" type="button" id="copyRefBtn" onclick="copyReferralLink()" style="font-weight: 600; font-size: 12px;">
                            <i class="ti ti-copy me-1"></i> Copy
                        </button>
                    </div>

                    <div class="d-flex align-items-center justify-content-between text-white-50 small" style="font-size: 11.5px;">
                        <span>Your Code: <strong class="text-white font-monospace">{{ $customer->referral_code }}</strong></span>
                        <span class="text-white fw-semibold">{{ $referralCount }} joined</span>
                    </div>
                </div>
            </div>

            <!-- Digital Security & Guarantee Card -->
            <div class="adminator-card">
                <div class="adminator-card-header">
                    <h2 class="adminator-card-title">
                        <i class="ti ti-shield-lock text-primary"></i> System Guarantees
                    </h2>
                </div>
                <div class="adminator-card-body p-3">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-start gap-2.5">
                            <span class="icon-holder c-blue-500 flex-shrink-0" style="width: 32px; height: 32px; font-size: 15px;">
                                <i class="ti ti-qrcode"></i>
                            </span>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 13px;">Tamper-Proof QR Passports</div>
                                <div class="text-muted small" style="font-size: 11.5px; line-height: 1.4;">Service histories are permanently anchored and verified via unique cryptographic QR tokens.</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-2.5">
                            <span class="icon-holder c-green-500 flex-shrink-0" style="width: 32px; height: 32px; font-size: 15px;">
                                <i class="ti ti-gauge"></i>
                            </span>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 13px;">Odometer Integrity Verification</div>
                                <div class="text-muted small" style="font-size: 11.5px; line-height: 1.4;">Mileage readings are monotonically audited on every workshop intake to combat rollbacks.</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-2.5">
                            <span class="icon-holder c-purple-500 flex-shrink-0" style="width: 32px; height: 32px; font-size: 15px;">
                                <i class="ti ti-certificate"></i>
                            </span>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 13px;">Official Roadworthiness Certificates</div>
                                <div class="text-muted small" style="font-size: 11.5px; line-height: 1.4;">Generate official, printable roadworthiness inspection certificates for vehicle resale.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    function copyReferralLink() {
        const input = document.getElementById('referralLink');
        navigator.clipboard.writeText(input.value).then(() => {
            const btn = document.getElementById('copyRefBtn');
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="ti ti-check me-1"></i> Copied!';
            btn.classList.add('btn-success');
            btn.classList.remove('btn-light');
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-light');
            }, 2500);
        });
    }
</script>
@endpush
