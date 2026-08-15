<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $vehicle->make }} {{ $vehicle->model }} — {{ $vehicle->plate_number }} | Vehicle Passport</title>
    <meta name="robots" content="noindex">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:wght@600;700&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #1F3A44;
            --clay: #A8503B;
            --teal: #2F6F62;
        }
        body { font-family: 'IBM Plex Sans', sans-serif; background: #F1F0EA; }
        .brand-serif { font-family: 'Source Serif 4', serif; font-weight: 700; }
        .passport-header { background: var(--navy); color: #fff; }
        .score-ring { transform: rotate(-90deg); }
        .badge-pass { background: rgba(47,111,98,.12); color: var(--teal); }
        .badge-warning { background: rgba(191,135,26,.12); color: #8a6416; }
        .badge-danger { background: rgba(168,80,59,.12); color: var(--clay); }
    </style>
</head>
<body>
<div class="passport-header py-4 mb-4">
    <div class="container-xl d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <div class="text-uppercase small" style="opacity:.7">Digital Vehicle Passport</div>
            <div class="brand-serif fs-2">{{ $vehicle->make }} {{ $vehicle->model }} @if($vehicle->year) ({{ $vehicle->year }}) @endif</div>
            <div class="mt-1">
                <span class="badge bg-white text-dark">{{ $vehicle->plate_number }}</span>
                @if($vehicle->branch)
                    <span class="ms-2 small" style="opacity:.8">Serviced at {{ $vehicle->branch->name }}</span>
                @endif
            </div>
        </div>
        @if($vehicle->qr_code)
            <div class="text-center">
                <div class="bg-white p-2 rounded">
                    {!! QrCode::size(96)->generate($vehicle->passportUrl()) !!}
                </div>
                <div class="small mt-1" style="opacity:.7">Scan to verify</div>
            </div>
        @endif
    </div>
</div>

<div class="container-xl mb-5">
    <div class="row g-3">
        {{-- Health score --}}
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h3 class="card-title">Vehicle Health</h3>
                    @if(count($healthScores))
                        @php $overall = $healthScores['Overall'] ?? null; @endphp
                        <div class="d-flex justify-content-center my-3">
                            <div style="position:relative;width:110px;height:110px;">
                                <svg width="110" height="110" viewBox="0 0 110 110" class="score-ring">
                                    <circle cx="55" cy="55" r="46" fill="none" stroke="#e6e4da" stroke-width="9"/>
                                    <circle cx="55" cy="55" r="46" fill="none"
                                            stroke="{{ $overall >= 75 ? '#2F6F62' : ($overall >= 50 ? '#BF871A' : '#A8503B') }}"
                                            stroke-width="9"
                                            stroke-dasharray="{{ 2 * pi() * 46 }}"
                                            stroke-dashoffset="{{ (1 - $overall / 100) * 2 * pi() * 46 }}"
                                            stroke-linecap="round"/>
                                </svg>
                                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:600;">
                                    {{ $overall }}
                                </div>
                            </div>
                        </div>
                        <div class="text-start">
                            @foreach($healthScores as $system => $score)
                                @continue($system === 'Overall')
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>{{ $system }}</span>
                                    <span class="fw-medium">{{ $score }}</span>
                                </div>
                                <div class="progress progress-sm mb-2">
                                    <div class="progress-bar" style="width:{{ $score }}%; background:{{ $score >= 75 ? '#2F6F62' : ($score >= 50 ? '#BF871A' : '#A8503B') }};"></div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-secondary small">No inspection on record yet.</p>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h3 class="card-title">Next Service Due</h3>
                    <div class="badge {{ $nextServiceDue['is_due'] ? 'badge-danger' : 'badge-pass' }} mb-2">
                        {{ $nextServiceDue['is_due'] ? 'Due now' : 'On track' }}
                    </div>
                    <div class="small text-secondary">
                        At {{ number_format($nextServiceDue['due_mileage']) }} km
                        or by {{ $nextServiceDue['due_date']->format('d M Y') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Service history --}}
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Service History</h3>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($jobCards as $job)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div class="fw-medium">{{ $job->completed_at?->format('d M Y') }}</div>
                                <div class="small text-secondary">{{ number_format($job->mileage_at_checkin) }} km</div>
                            </div>
                            @if($job->services->count())
                                <ul class="small mb-1 mt-1 ps-3">
                                    @foreach($job->services as $service)
                                        <li>{{ $service->description }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            @if($job->partsUsed->count())
                                <div class="small text-secondary">
                                    Parts: {{ $job->partsUsed->map(fn ($p) => optional($p->inventoryItem)->name)->filter()->join(', ') }}
                                </div>
                            @endif
                            @if($job->media->count())
                                <div class="small text-secondary mt-1">
                                    <i class="ti ti-photo"></i> {{ $job->media->count() }} photo(s) on file
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="list-group-item text-secondary small">No completed services on record yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="text-center text-secondary small mt-4">
        This record is issued and verified by AutoTrack Ethiopia. Chassis: {{ $vehicle->chassis_number ?? '—' }}
    </div>
</div>
</body>
</html>
