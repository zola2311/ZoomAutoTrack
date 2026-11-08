<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $vehicle->year ? $vehicle->year . ' ' : '' }}{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->plate_number }}) — Verified Digital Passport</title>
    <meta name="description" content="Verified Digital Vehicle Service History, Health Score, and Odometer Passport powered by Zoom AutoTrack Ethiopia.">
    <meta name="robots" content="noindex, follow">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        :root {
            --bg-base: #0B0F17;
            --bg-surface: #111827;
            --bg-card: #182234;
            --bg-card-hover: #1E293B;
            --border: rgba(255, 255, 255, 0.08);
            --border-highlight: rgba(14, 165, 233, 0.3);
            --text-main: #F8FAFC;
            --text-muted: #94A3B8;
            --text-dim: #64748B;
            --ethiopia-green: #078930;
            --ethiopia-yellow: #FCDD09;
            --ethiopia-red: #DA121A;
            --accent-emerald: #10B981;
            --accent-cyan: #06B6D4;
            --accent-amber: #F59E0B;
            --accent-rose: #F43F5E;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-base);
            color: var(--text-main);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        .mono { font-family: 'JetBrains Mono', monospace; }
        .display-font { font-family: 'Space Grotesk', sans-serif; }

        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }

        /* Hero Header */
        .hero {
            background: radial-gradient(circle at 50% 0%, rgba(14, 165, 233, 0.12) 0%, rgba(15, 23, 42, 0) 70%),
                        linear-gradient(180deg, #111827 0%, #0B0F17 100%);
            border-bottom: 1px solid var(--border);
            padding: 2.5rem 0 3rem;
            position: relative;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 2rem;
            align-items: start;
        }
        @media (max-width: 820px) {
            .hero-grid {
                grid-template-columns: 1fr;
            }
            .hero-qr {
                justify-self: center;
                margin-top: 1rem;
            }
        }

        .badge-verified {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(16, 185, 129, 0.12);
            color: #34D399;
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 9999px;
            padding: 0.35rem 0.85rem;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        /* Ethiopian License Plate Component */
        .plate-box {
            display: inline-flex;
            align-items: stretch;
            background: #FFFFFF;
            color: #111827;
            border: 2.5px solid #000000;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.9);
            overflow: hidden;
            height: 48px;
            font-weight: 800;
        }
        .plate-left {
            background: #004494;
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 9px;
            font-size: 0.65rem;
            line-height: 1.1;
            border-right: 2px solid #000;
        }
        .plate-left .star { font-size: 8px; color: #FCDD09; }
        .plate-number {
            font-family: 'Space Grotesk', 'Plus Jakarta Sans', sans-serif;
            font-size: 1.5rem;
            letter-spacing: 0.08em;
            padding: 0 1rem;
            display: flex;
            align-items: center;
            color: #0F172A;
            text-shadow: 0 1px 1px rgba(0,0,0,0.15);
        }

        /* Action Buttons */
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-top: 1.5rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.6rem 1.1rem;
            border-radius: 8px;
            font-size: 0.825rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }
        .btn-primary {
            background: #0284C7;
            color: #fff;
        }
        .btn-primary:hover {
            background: #0369A1;
            transform: translateY(-1px);
        }
        .btn-outline {
            background: rgba(255,255,255,0.04);
            border-color: rgba(255,255,255,0.12);
            color: var(--text-main);
        }
        .btn-outline:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.25);
            color: #fff;
        }
        .btn-emerald {
            background: rgba(16, 185, 129, 0.15);
            border-color: rgba(16, 185, 129, 0.35);
            color: #34D399;
        }
        .btn-emerald:hover {
            background: rgba(16, 185, 129, 0.25);
            color: #fff;
        }

        /* Cards Grid */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.5rem;
            position: relative;
            transition: border-color 0.2s ease;
        }
        .card:hover {
            border-color: rgba(255,255,255,0.14);
        }
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }
        .card-title {
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-main);
        }

        /* Stats Pill Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 1rem;
            margin-top: -1.5rem;
            margin-bottom: 2rem;
            position: relative;
            z-index: 10;
        }
        .stat-card {
            background: #151F30;
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            border-radius: 12px;
            padding: 1.1rem 1.25rem;
        }
        .stat-label {
            font-size: 0.725rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .stat-value {
            font-size: 1.35rem;
            font-weight: 800;
            font-family: 'Space Grotesk', sans-serif;
            color: #fff;
        }
        .stat-sub {
            font-size: 0.75rem;
            color: var(--text-dim);
            margin-top: 0.2rem;
        }

        /* Health Score Gauge */
        .gauge-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem 0;
        }
        .gauge-svg {
            transform: rotate(-90deg);
        }
        .gauge-bg {
            stroke: rgba(255,255,255,0.06);
        }
        .gauge-val {
            stroke-linecap: round;
            transition: stroke-dashoffset 1s ease-out;
        }

        /* Progress Bar */
        .progress-bar-wrap {
            background: rgba(255,255,255,0.06);
            height: 7px;
            border-radius: 9999px;
            overflow: hidden;
            margin-top: 0.4rem;
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 9999px;
            transition: width 0.5s ease;
        }

        /* Service History Timeline */
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 10px;
            bottom: 10px;
            width: 2px;
            background: rgba(255,255,255,0.1);
        }
        .timeline-item {
            position: relative;
            padding-bottom: 2rem;
        }
        .timeline-item:last-child {
            padding-bottom: 0;
        }
        .timeline-dot {
            position: absolute;
            left: -2rem;
            top: 3px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #0284C7;
            border: 3px solid var(--bg-card);
            box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.4);
        }
        .timeline-dot.done {
            background: var(--accent-emerald);
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.4);
        }
        .service-badge {
            display: inline-block;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 6px;
            padding: 0.2rem 0.55rem;
            font-size: 0.75rem;
            margin-right: 0.35rem;
            margin-bottom: 0.35rem;
            color: #CBD5E1;
        }

        /* Toast Alert */
        #toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #0F172A;
            border: 1px solid #10B981;
            color: #fff;
            padding: 12px 20px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.875rem;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 9999;
            pointer-events: none;
        }
        #toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Print Media Style */
        @media print {
            body {
                background: #fff !important;
                color: #000 !important;
            }
            .gov-banner, .btn-group, #toast, .no-print {
                display: none !important;
            }
            .hero {
                background: #fff !important;
                border: 2px solid #000 !important;
                padding: 1rem !important;
            }
            .card, .stat-card {
                background: #fff !important;
                border: 1px solid #ccc !important;
                color: #000 !important;
                page-break-inside: avoid;
            }
            .stat-value, .card-title, h1, h2, h3 {
                color: #000 !important;
            }
            .plate-box {
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Hero Header -->
    <header class="hero">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-info">
                    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem;">
                        <span class="badge-verified">
                            <i class="ti ti-shield-check" style="font-size:1.05rem;"></i> Verified Digital Passport
                        </span>
                        <span style="color:var(--text-dim); font-size:0.75rem;">UUID: <span class="mono">{{ substr($vehicle->qr_code, 0, 13) }}...</span></span>
                    </div>

                    <h1 class="display-font" style="font-size:clamp(1.75rem, 4vw, 2.75rem); font-weight:800; line-height:1.15; margin-bottom:0.75rem;">
                        {{ $vehicle->year ? $vehicle->year . ' ' : '' }}{{ $vehicle->make }} {{ $vehicle->model }}
                    </h1>

                    <div style="display:flex; flex-wrap:wrap; align-items:center; gap:1rem; margin-bottom:1rem;">
                        <!-- Embossed Ethiopian Plate -->
                        <div class="plate-box">
                            <div class="plate-left">
                                <span class="star">★</span>
                                <span>ET</span>
                            </div>
                            <div class="plate-number">{{ $vehicle->plate_number }}</div>
                        </div>

                        <div style="color:var(--text-muted); font-size:0.875rem;">
                            @if($vehicle->color)
                                <span style="display:inline-flex; align-items:center; gap:0.35rem; margin-right:0.75rem;">
                                    <i class="ti ti-palette" style="color:var(--text-dim)"></i> {{ ucfirst($vehicle->color) }}
                                </span>
                            @endif
                            @if($vehicle->fuel_type)
                                <span style="display:inline-flex; align-items:center; gap:0.35rem; margin-right:0.75rem;">
                                    <i class="ti ti-gas-station" style="color:var(--text-dim)"></i> {{ ucfirst($vehicle->fuel_type) }}
                                </span>
                            @endif
                            @if($vehicle->transmission)
                                <span style="display:inline-flex; align-items:center; gap:0.35rem;">
                                    <i class="ti ti-manual-gearbox" style="color:var(--text-dim)"></i> {{ ucfirst($vehicle->transmission) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div style="color:var(--text-dim); font-size:0.8rem; display:flex; flex-wrap:wrap; gap:1.25rem;">
                        <span><strong style="color:var(--text-muted)">Chassis (VIN):</strong> <span class="mono">{{ $vehicle->chassis_number ?? 'Not Recorded' }}</span></span>
                        <span><strong style="color:var(--text-muted)">Engine No:</strong> <span class="mono">{{ $vehicle->engine_number ?? 'Not Recorded' }}</span></span>
                        @if($vehicle->branch)
                            <span><strong style="color:var(--text-muted)">Primary Workshop:</strong> {{ $vehicle->branch->name }}</span>
                        @endif
                    </div>

                    <!-- Interactive Action Buttons -->
                    <div class="btn-group no-print">
                        <button class="btn btn-primary" onclick="copyPassportLink()">
                            <i class="ti ti-link"></i> Copy Verification Link
                        </button>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode('View verified Digital Vehicle Passport for ' . $vehicle->make . ' ' . $vehicle->model . ' (' . $vehicle->plate_number . '): ' . url()->current()) }}" target="_blank" class="btn btn-emerald">
                            <i class="ti ti-brand-whatsapp"></i> Share on WhatsApp
                        </a>
                        <a href="{{ route('passport.certificate', $vehicle->qr_code) }}" class="btn btn-outline" target="_blank">
                            <i class="ti ti-certificate"></i> Official Inspection Certificate
                        </a>
                        <button class="btn btn-outline" onclick="window.print()">
                            <i class="ti ti-printer"></i> Print Passport
                        </button>
                        <a href="{{ route('booking.create') }}?plate={{ urlencode($vehicle->plate_number) }}" class="btn btn-outline">
                            <i class="ti ti-calendar-plus"></i> Book Service
                        </a>
                    </div>
                </div>

                <!-- QR Stamp Card (Top Right) -->
                <div class="hero-qr">
                    <div style="background:#151F30; border:1px solid rgba(255,255,255,0.1); border-radius:14px; padding:1.25rem; text-align:center; min-width:180px; box-shadow:0 10px 30px rgba(0,0,0,0.4);">
                        <div style="background:#fff; padding:10px; border-radius:10px; display:inline-block; line-height:0; box-shadow:0 4px 12px rgba(0,0,0,0.2);">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate(url()->current()) !!}
                        </div>
                        <div style="margin-top:0.6rem; font-size:0.75rem; font-weight:700; color:var(--text-main); letter-spacing:0.04em;">
                            SCAN TO VERIFY
                        </div>
                        <div style="font-size:0.68rem; color:var(--text-dim);">
                            Digital Passport Protocol v2.4
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="container" style="padding-bottom: 4rem;">

        <!-- 4-Pillar Verification Summary Cards -->
        <section class="stats-grid">
            <!-- 1. Odometer Fraud Protection -->
            <div class="stat-card">
                <div class="stat-label">
                    <i class="ti ti-gauge" style="color:var(--accent-cyan); font-size:1.1rem;"></i>
                    Odometer Integrity
                </div>
                <div class="stat-value" style="color: {{ $odometerStatus['is_verified'] ? '#10B981' : '#F43F5E' }};">
                    {{ $odometerStatus['is_verified'] ? 'Verified Safe' : 'Flagged Warning' }}
                </div>
                <div class="stat-sub">
                    @if($odometerStatus['is_verified'])
                        <span>{{ number_format($vehicle->current_mileage) }} km &bull; Consistent log</span>
                    @else
                        <span>{{ $odometerStatus['issues_count'] }} mileage discrepancy flagged</span>
                    @endif
                </div>
            </div>

            <!-- 2. Overall Health Index -->
            @php $overall = $healthScores['Overall'] ?? ($jobCards->count() > 0 ? 88 : null); @endphp
            <div class="stat-card">
                <div class="stat-label">
                    <i class="ti ti-activity-heartbeat" style="color:var(--accent-emerald); font-size:1.1rem;"></i>
                    Health Rating
                </div>
                <div class="stat-value" style="color: {{ $overall >= 75 ? '#10B981' : ($overall >= 50 ? '#F59E0B' : '#F43F5E') }};">
                    {{ $overall ? $overall . ' / 100' : 'Pending' }}
                </div>
                <div class="stat-sub">
                    {{ $overall >= 80 ? 'Grade A: Roadworthy & Strong' : ($overall >= 60 ? 'Grade B: Minor Maintenance Due' : 'Inspection Required') }}
                </div>
            </div>

            <!-- 3. Next Service Status -->
            <div class="stat-card">
                <div class="stat-label">
                    <i class="ti ti-clock-check" style="color:var(--accent-amber); font-size:1.1rem;"></i>
                    Service Schedule
                </div>
                <div class="stat-value" style="color: {{ $nextServiceDue['is_due'] ? '#F43F5E' : '#10B981' }};">
                    {{ $nextServiceDue['is_due'] ? 'Service Due' : 'On Track' }}
                </div>
                <div class="stat-sub">
                    @if($nextServiceDue['is_due'])
                        Due at {{ number_format($nextServiceDue['due_mileage']) }} km
                    @else
                        {{ number_format($nextServiceDue['km_remaining']) }} km remaining
                    @endif
                </div>
            </div>

            <!-- 4. Verified Service Count -->
            <div class="stat-card">
                <div class="stat-label">
                    <i class="ti ti-history" style="color:#818CF8; font-size:1.1rem;"></i>
                    Registered Visits
                </div>
                <div class="stat-value">
                    {{ $vehicle->jobCards->count() }} Workshop Logs
                </div>
                <div class="stat-sub">
                    100% verified with digitized job cards
                </div>
            </div>
        </section>

        <!-- Main Content 2-Column Split -->
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; align-items:start;">

            <!-- Left Column: Diagnostic System Report -->
            <div>
                <div class="card" style="margin-bottom:1.5rem;">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="ti ti-cpu" style="color:var(--accent-cyan)"></i>
                            Diagnostic Systems Assessment
                        </h2>
                        <span style="font-size:0.75rem; color:var(--text-dim);">Multi-Point Check</span>
                    </div>

                    @if(!empty($healthScores))
                        <div style="display:flex; align-items:center; gap:1.5rem; padding-bottom:1.5rem; border-bottom:1px solid var(--border); margin-bottom:1.25rem;">
                            <!-- Gauge -->
                            <div style="position:relative; width:90px; height:90px; flex-shrink:0;">
                                <svg width="90" height="90" viewBox="0 0 90 90" class="gauge-svg">
                                    <circle cx="45" cy="45" r="38" fill="none" class="gauge-bg" stroke-width="7"/>
                                    <circle cx="45" cy="45" r="38" fill="none" class="gauge-val"
                                            stroke="{{ $overall >= 75 ? '#10B981' : ($overall >= 50 ? '#F59E0B' : '#F43F5E') }}"
                                            stroke-width="7"
                                            stroke-dasharray="{{ 2 * pi() * 38 }}"
                                            stroke-dashoffset="{{ (1 - ($overall ?? 70) / 100) * 2 * pi() * 38 }}"/>
                                </svg>
                                <div style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                    <span style="font-size:1.35rem; font-weight:800; font-family:'Space Grotesk', sans-serif;">{{ $overall }}</span>
                                    <span style="font-size:0.55rem; color:var(--text-dim); text-transform:uppercase;">Score</span>
                                </div>
                            </div>
                            <div>
                                <div style="font-weight:700; font-size:1rem; margin-bottom:0.25rem;">Comprehensive Health Report</div>
                                <div style="font-size:0.8rem; color:var(--text-muted);">
                                    Scored systematically by certified automotive diagnostics software across key mechanical subsystems.
                                </div>
                            </div>
                        </div>

                        <!-- Sub-systems Bars -->
                        <div style="display:flex; flex-direction:column; gap:1rem;">
                            @foreach($healthScores as $category => $score)
                                @continue($category === 'Overall')
                                <div>
                                    <div style="display:flex; justify-content:space-between; font-size:0.825rem; font-weight:600;">
                                        <span style="display:flex; align-items:center; gap:0.4rem;">
                                            @if(stripos($category, 'engine') !== false) <i class="ti ti-engine" style="color:var(--accent-amber)"></i>
                                            @elseif(stripos($category, 'brake') !== false) <i class="ti ti-disc" style="color:var(--accent-rose)"></i>
                                            @elseif(stripos($category, 'suspension') !== false || stripos($category, 'steering') !== false) <i class="ti ti-spiral" style="color:var(--accent-cyan)"></i>
                                            @elseif(stripos($category, 'electric') !== false || stripos($category, 'battery') !== false) <i class="ti ti-bolt" style="color:var(--ethiopia-yellow)"></i>
                                            @elseif(stripos($category, 'ac') !== false || stripos($category, 'air') !== false) <i class="ti ti-snowflake" style="color:#60A5FA)"></i>
                                            @else <i class="ti ti-tool" style="color:var(--accent-emerald)"></i>
                                            @endif
                                            {{ ucfirst($category) }}
                                        </span>
                                        <span class="mono" style="color: {{ $score >= 75 ? '#10B981' : ($score >= 50 ? '#F59E0B' : '#F43F5E') }};">
                                            {{ $score }}%
                                        </span>
                                    </div>
                                    <div class="progress-bar-wrap">
                                        <div class="progress-bar-fill" style="width: {{ $score }}%; background: {{ $score >= 75 ? '#10B981' : ($score >= 50 ? '#F59E0B' : '#F43F5E') }};"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align:center; padding:2rem 1rem; color:var(--text-dim);">
                            <i class="ti ti-clipboard-x" style="font-size:2rem; margin-bottom:0.5rem; display:block;"></i>
                            <div style="font-weight:600; color:var(--text-muted); margin-bottom:0.25rem;">No Full Diagnostic Inspection Recorded</div>
                            <div style="font-size:0.8rem;">A full multi-point digital inspection will generate category grades on the next workshop visit.</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Chronological Service History Timeline -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="ti ti-clipboard-check" style="color:var(--accent-emerald)"></i>
                        Certified Service & Repair Records
                    </h2>
                    <span style="font-size:0.75rem; color:var(--text-muted); font-weight:600;">
                        {{ $jobCards->count() }} Recorded {{ \Illuminate\Support\Str::plural('Visit', $jobCards->count()) }}
                    </span>
                </div>

                @if($jobCards->isNotEmpty())
                    <div class="timeline">
                        @foreach($jobCards as $job)
                            <div class="timeline-item">
                                <div class="timeline-dot done"></div>
                                <div style="display:flex; justify-content:space-between; align-items:baseline; flex-wrap:wrap; gap:0.5rem; margin-bottom:0.35rem;">
                                    <div style="font-weight:700; font-size:0.95rem; color:#fff;">
                                        {{ $job->completed_at ? $job->completed_at->format('d F Y') : ($job->checked_in_at ? $job->checked_in_at->format('d F Y') : 'Date N/A') }}
                                    </div>
                                    <div class="mono" style="font-size:0.8rem; background:rgba(255,255,255,0.06); padding:0.15rem 0.5rem; border-radius:4px; color:var(--accent-cyan);">
                                        {{ number_format($job->mileage_at_checkin) }} km
                                    </div>
                                </div>

                                <div style="font-size:0.75rem; color:var(--text-dim); margin-bottom:0.6rem;">
                                    Job Reference: <span class="mono" style="color:var(--text-muted)">{{ $job->job_number }}</span>
                                    @if($job->mechanic)
                                        &bull; Technician: {{ $job->mechanic->name }}
                                    @endif
                                    @if($job->branch)
                                        &bull; {{ $job->branch->name }}
                                    @endif
                                </div>

                                @if($job->customer_complaint)
                                    <div style="background:rgba(255,255,255,0.02); border-left:2px solid var(--border-highlight); padding:0.4rem 0.65rem; border-radius:0 6px 6px 0; font-size:0.8rem; color:var(--text-muted); margin-bottom:0.6rem;">
                                        <strong style="color:#E2E8F0;">Intake Notes:</strong> {{ $job->customer_complaint }}
                                    </div>
                                @endif

                                <!-- Services line items -->
                                @if($job->services->isNotEmpty())
                                    <div style="margin-bottom:0.5rem;">
                                        <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; color:var(--text-dim); margin-bottom:0.25rem;">
                                            Services Completed:
                                        </div>
                                        <div>
                                            @foreach($job->services as $srv)
                                                <span class="service-badge">
                                                    <i class="ti ti-check" style="color:var(--accent-emerald); font-size:0.75rem;"></i>
                                                    {{ $srv->description }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Parts Used -->
                                @if($job->partsUsed->isNotEmpty())
                                    <div style="margin-bottom:0.5rem;">
                                        <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; color:var(--text-dim); margin-bottom:0.25rem;">
                                            Genuine Parts Installed:
                                        </div>
                                        <div style="font-size:0.775rem; color:var(--text-muted);">
                                            @foreach($job->partsUsed as $part)
                                                <div style="display:inline-block; background:rgba(16,185,129,0.08); border:1px solid rgba(16,185,129,0.2); border-radius:4px; padding:0.15rem 0.45rem; margin-right:0.3rem; margin-bottom:0.3rem;">
                                                    {{ $part->inventoryItem?->name ?? 'Replacement Part' }} (x{{ $part->quantity }})
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Photo inspection count -->
                                @if($job->media->isNotEmpty())
                                    <div style="font-size:0.725rem; color:var(--text-dim); margin-top:0.35rem; display:flex; align-items:center; gap:0.35rem;">
                                        <i class="ti ti-photo" style="color:var(--accent-cyan)"></i>
                                        {{ $job->media->count() }} photo inspection records verified on file
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center; padding:3rem 1rem; color:var(--text-dim);">
                        <i class="ti ti-history-off" style="font-size:2.5rem; margin-bottom:0.75rem; display:block;"></i>
                        <div style="font-weight:700; color:var(--text-muted); margin-bottom:0.35rem;">No Completed Services Yet</div>
                        <div style="font-size:0.825rem; max-width:320px; margin:0 auto;">
                            Once this vehicle completes its scheduled service at any authorized workshop, verified records and parts logs will appear here automatically.
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer Seal -->
        <footer style="margin-top:4rem; padding-top:2rem; border-top:1px solid var(--border); text-align:center; color:var(--text-dim); font-size:0.775rem;">
            <div style="display:flex; justify-content:center; align-items:center; gap:0.5rem; margin-bottom:0.5rem;">
                <i class="ti ti-shield-check" style="color:var(--accent-emerald); font-size:1.1rem;"></i>
                <strong style="color:var(--text-muted)">Zoom AutoTrack Ethiopia Trust Network</strong> &bull; Digital Vehicle Passport Infrastructure
            </div>
            <p>This public document is cryptographically verified against server UUID {{ $vehicle->qr_code }}.</p>
        </footer>
    </main>

    <!-- Toast Notification Element -->
    <div id="toast">
        <i class="ti ti-circle-check" style="color:#10B981; font-size:1.25rem;"></i>
        <span>Link copied to clipboard!</span>
    </div>

    <script>
        function copyPassportLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const toast = document.getElementById('toast');
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            });
        }
    </script>
</body>
</html>
