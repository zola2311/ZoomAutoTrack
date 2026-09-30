@extends('layouts.portal')

@section('title', 'Dashboard')

@section('content')
    <div class="mt-3 pb-4">

        {{-- Welcome banner --}}
        <div class="card mb-4" style="background: linear-gradient(135deg, #0C447C 0%, #1565C0 60%, #1976D2 100%); border: none; border-radius: 20px; overflow: hidden; position: relative;">
            <div style="position:absolute;top:-40px;right:-40px;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,0.05)"></div>
            <div style="position:absolute;bottom:-60px;right:80px;width:150px;height:150px;border-radius:50%;background:rgba(255,255,255,0.04)"></div>
            <div class="card-body py-4 px-4" style="position:relative;z-index:1">
                <div class="row align-items-center g-3">
                    <div class="col">
                        <div class="text-white-50 small mb-1 text-uppercase tracking-wide" style="letter-spacing:.08em">Welcome back</div>
                        <h2 class="text-white mb-1 fw-bold fs-2">{{ $customer->full_name }} 👋</h2>
                        <div class="text-white-50 small">{{ now()->format('l, d F Y') }}</div>
                        <div class="mt-3 d-flex gap-2 flex-wrap">
                            <a href="{{ route('portal.appointments.create') }}" class="btn btn-white btn-sm">
                                <i class="ti ti-calendar-plus me-1"></i> Book service
                            </a>
                            <a href="{{ route('portal.history') }}" class="btn btn-sm" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.3)">
                                <i class="ti ti-history me-1"></i> Service history
                            </a>
                            <a href="{{ route('portal.appointments.index') }}" class="btn btn-sm" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.3)">
                                <i class="ti ti-calendar me-1"></i> My appointments
                            </a>
                        </div>
                    </div>
                    <div class="col-auto d-none d-md-block">
                        <div style="width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;font-size:36px;">
                            🚗
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stat cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="card card-sm h-100" style="border-top: 3px solid #0C447C">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-secondary small text-uppercase" style="letter-spacing:.06em;font-size:11px">Vehicles</span>
                            <span class="avatar avatar-sm bg-blue-lt"><i class="ti ti-car"></i></span>
                        </div>
                        <div class="h1 mb-0 fw-bold">{{ $vehicles->count() }}</div>
                        <div class="text-secondary small mt-1">Registered</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="card card-sm h-100" style="border-top: 3px solid #f59f00">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-secondary small text-uppercase" style="letter-spacing:.06em;font-size:11px">Loyalty</span>
                            <span class="avatar avatar-sm bg-yellow-lt"><i class="ti ti-star"></i></span>
                        </div>
                        <div class="h1 mb-0 fw-bold">{{ number_format($customer->loyalty_points) }}</div>
                        <div class="mt-1">
                            @if ($customer->loyalty_points >= 1000)
                                <span class="badge bg-yellow-lt text-yellow"><i class="ti ti-crown me-1"></i>Gold member</span>
                            @elseif ($customer->loyalty_points >= 500)
                                <span class="badge bg-azure-lt">Silver member</span>
                            @else
                                <span class="badge bg-muted-lt text-secondary">Bronze member</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="card card-sm h-100" style="border-top: 3px solid #2fb344">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-secondary small text-uppercase" style="letter-spacing:.06em;font-size:11px">Total visits</span>
                            <span class="avatar avatar-sm bg-green-lt"><i class="ti ti-clipboard-check"></i></span>
                        </div>
                        <div class="h1 mb-0 fw-bold">{{ $totalVisits }}</div>
                        <div class="text-secondary small mt-1">
                            @if ($lastVisit)
                                Last: {{ $lastVisit->completed_at->format('d M Y') }}
                            @else
                                No visits yet
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="card card-sm h-100" style="border-top: 3px solid #ae3ec9">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-secondary small text-uppercase" style="letter-spacing:.06em;font-size:11px">Referrals</span>
                            <span class="avatar avatar-sm bg-purple-lt"><i class="ti ti-users"></i></span>
                        </div>
                        <div class="h1 mb-0 fw-bold">{{ $referralCount }}</div>
                        <div class="text-secondary small mt-1">Friends referred</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reminders --}}
        @php
            $reminders = $vehicles->map(fn ($v) => ['vehicle' => $v, 'due' => $v->nextServiceDue()])
                ->filter(fn ($r) => $r['due']['is_due'] || $r['due']['km_remaining'] <= 500);
        @endphp

        @if ($reminders->isNotEmpty())
            <div class="card mb-4" style="border-left: 4px solid #f59f00; border-radius: 12px;">
                <div class="card-body py-3">
                    <div class="d-flex gap-3 align-items-start">
                        <span class="avatar bg-yellow-lt flex-shrink-0"><i class="ti ti-bell-ringing"></i></span>
                        <div>
                            <div class="fw-bold mb-1">Service reminders</div>
                            @foreach ($reminders as $r)
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-blue-lt">{{ $r['vehicle']->plate_number }}</span>
                                    @if ($r['due']['is_due'])
                                        <span class="text-danger small fw-semibold"><i class="ti ti-alert-circle me-1"></i>Service overdue</span>
                                    @else
                                        <span class="text-warning small"><i class="ti ti-clock me-1"></i>{{ number_format($r['due']['km_remaining']) }} km remaining</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4">
            {{-- Vehicles column --}}
            <div class="col-lg-7">
                <div class="d-flex align-items-center mb-3">
                    <h3 class="mb-0"><i class="ti ti-car me-2 text-primary"></i>My Vehicles</h3>
                </div>

                @if ($vehicles->isEmpty())
                    <div class="card" style="border-radius:16px;border:2px dashed var(--tblr-border-color)">
                        <div class="card-body text-center py-5 text-secondary">
                            <i class="ti ti-car-off" style="font-size:48px"></i>
                            <div class="mt-2">No vehicles registered yet.</div>
                            <div class="small">Visit a garage to get your first vehicle registered.</div>
                        </div>
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach ($vehicles as $vehicle)
                            @php
                                $due = $vehicle->nextServiceDue();
                                $health = $vehicle->latestHealthScores();
                                $overall = $health['Overall'] ?? null;
                                $healthColor = $overall === null ? 'secondary'
                                    : ($overall >= 70 ? 'success' : ($overall >= 40 ? 'warning' : 'danger'));
                            @endphp
                            <div class="card" style="border-radius:16px;overflow:hidden;border:none;box-shadow:0 1px 8px rgba(0,0,0,.08)">
                                <div style="height:4px;background:var(--tblr-{{ $healthColor }})"></div>
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="avatar avatar-lg bg-blue-lt flex-shrink-0">
                                            <i class="ti ti-car fs-2"></i>
                                        </div>
                                        <div class="flex-fill">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                                <div>
                                                    <span class="badge bg-blue-lt me-2">{{ $vehicle->plate_number }}</span>
                                                    @if ($due['is_due'])
                                                        <span class="badge bg-red-lt"><i class="ti ti-alert-circle me-1"></i>Service due</span>
                                                    @else
                                                        <span class="badge bg-green-lt"><i class="ti ti-circle-check me-1"></i>On track</span>
                                                    @endif
                                                </div>
                                                <a href="{{ $vehicle->passportUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="ti ti-id-badge-2 me-1"></i> Passport
                                                </a>
                                            </div>

                                            <div class="fw-bold mb-1">
                                                {{ $vehicle->make }} {{ $vehicle->model }}
                                                @if ($vehicle->year)
                                                    <span class="text-secondary fw-normal">({{ $vehicle->year }})</span>
                                                @endif
                                            </div>

                                            <div class="text-secondary small mb-2">
                                                <i class="ti ti-gauge me-1"></i>{{ number_format($vehicle->current_mileage) }} km
                                                &middot;
                                                @if ($due['km_remaining'] > 0)
                                                    {{ number_format($due['km_remaining']) }} km to next service
                                                @else
                                                    <span class="text-danger">service overdue</span>
                                                @endif
                                            </div>

                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <div class="flex-fill">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="small text-secondary">Health</span>
                                                        <span class="small fw-bold text-{{ $healthColor }}">{{ $overall ?? '—' }}</span>
                                                    </div>
                                                    <div class="progress" style="height:6px;border-radius:99px">
                                                        <div class="progress-bar bg-{{ $healthColor }}"
                                                             style="width:{{ $overall ?? 0 }}%;border-radius:99px"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if (!empty($health))
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach ($health as $category => $score)
                                                        @if ($category !== 'Overall')
                                                            @php $c = $score >= 70 ? 'success' : ($score >= 40 ? 'warning' : 'danger'); @endphp
                                                            <span class="badge bg-{{ $c }}-lt" style="font-size:10px">
                                                            {{ $category }}: {{ $score }}
                                                        </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Right column --}}
            <div class="col-lg-5">

                {{-- Recent visits --}}
                <div class="card mb-3" style="border-radius:16px;border:none;box-shadow:0 1px 8px rgba(0,0,0,.08)">
                    <div class="card-header" style="border-bottom:1px solid var(--tblr-border-color)">
                        <h3 class="card-title"><i class="ti ti-history me-2 text-primary"></i>Recent visits</h3>
                        <div class="card-options">
                            <a href="{{ route('portal.history') }}" class="btn btn-sm btn-ghost-primary">See all →</a>
                        </div>
                    </div>
                    @if ($recentHistory->isEmpty())
                        <div class="card-body text-secondary text-center small py-4">
                            <i class="ti ti-clipboard-off d-block mb-1" style="font-size:28px"></i>
                            No visits yet
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($recentHistory as $job)
                                <div class="list-group-item py-3">
                                    <div class="d-flex align-items-center gap-3">
                                    <span class="avatar avatar-sm bg-blue-lt flex-shrink-0">
                                        <i class="ti ti-tool"></i>
                                    </span>
                                        <div class="flex-fill overflow-hidden">
                                            <div class="small fw-semibold text-truncate">
                                                {{ \Illuminate\Support\Str::limit($job->customer_complaint, 45) ?: 'Service visit' }}
                                            </div>
                                            <div class="text-secondary d-flex gap-2 mt-1" style="font-size:11px">
                                                <span class="badge bg-blue-lt">{{ $job->vehicle->plate_number }}</span>
                                                {{ $job->completed_at->format('d M Y') }}
                                            </div>
                                        </div>
                                        <span class="badge bg-green-lt flex-shrink-0">Done</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Referral card --}}
                <div class="card mb-3" style="border-radius:16px;border:none;background:linear-gradient(135deg,#0C447C,#1976D2);overflow:hidden;position:relative">
                    <div style="position:absolute;top:-30px;right:-30px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,0.07)"></div>
                    <div class="card-body text-white" style="position:relative;z-index:1">
                        <div class="d-flex align-items-center mb-3 gap-2">
                            <span class="avatar bg-white" style="color:#0C447C"><i class="ti ti-gift"></i></span>
                            <div>
                                <div class="fw-bold">Refer a friend</div>
                                <div class="small text-white-50">You both earn 500 loyalty points</div>
                            </div>
                        </div>
                        <div class="input-group input-group-sm mb-2">
                            <input type="text" class="form-control" readonly
                                   value="{{ route('portal.register', ['ref' => $customer->referral_code]) }}"
                                   id="referralLink" style="font-size:11px">
                            <button class="btn btn-white btn-sm" type="button" id="copyBtn"
                                    onclick="navigator.clipboard.writeText(document.getElementById('referralLink').value);document.getElementById('copyBtn').innerHTML='<i class=\'ti ti-check\'></i> Copied!'">
                                <i class="ti ti-copy"></i> Copy
                            </button>
                        </div>
                        <div class="text-white-50 small">
                            Your code: <strong class="text-white">{{ $customer->referral_code }}</strong>
                            &middot; {{ $referralCount }} friend(s) referred
                        </div>
                    </div>
                </div>

                {{-- Coming soon --}}
                <div class="card" style="border-radius:16px;border:none;box-shadow:0 1px 8px rgba(0,0,0,.08)">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-sparkles me-2" style="color:#f59f00"></i>Coming soon
                        </h3>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach ([
                            ['icon' => 'device-mobile', 'label' => 'Mobile app', 'desc' => 'iOS & Android'],
                            ['icon' => 'discount-2', 'label' => 'Redeem loyalty rewards', 'desc' => 'Points → discounts'],
                            ['icon' => 'brand-whatsapp', 'label' => 'WhatsApp notifications', 'desc' => 'Real-time updates'],
                            ['icon' => 'building-store', 'label' => 'Parts marketplace', 'desc' => 'Order online'],
                            ['icon' => 'chart-bar', 'label' => 'Vehicle analytics', 'desc' => 'Cost & health trends'],
                        ] as $item)
                            <div class="list-group-item d-flex align-items-center gap-3 py-2">
                            <span class="avatar avatar-sm bg-yellow-lt text-yellow flex-shrink-0">
                                <i class="ti ti-{{ $item['icon'] }}"></i>
                            </span>
                                <div class="flex-fill">
                                    <div class="small fw-semibold">{{ $item['label'] }}</div>
                                    <div class="text-secondary" style="font-size:11px">{{ $item['desc'] }}</div>
                                </div>
                                <span class="badge bg-yellow-lt text-yellow" style="font-size:10px">Soon</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
