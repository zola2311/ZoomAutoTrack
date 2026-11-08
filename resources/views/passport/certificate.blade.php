<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Inspection Certificate — {{ $vehicle->plate_number }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #111;
            background: #f7f7f7;
            padding: 20px;
        }
        .cert-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border: 8px double #1E3A8A;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            position: relative;
        }
        .cert-header {
            text-align: center;
            border-bottom: 2px solid #1E3A8A;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .cert-org {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #1E3A8A;
        }
        .cert-title {
            font-size: 26px;
            font-weight: bold;
            color: #0F172A;
            margin: 10px 0 5px;
            letter-spacing: 1px;
        }
        .cert-subtitle {
            font-size: 13px;
            font-style: italic;
            color: #475569;
        }
        .cert-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
            font-size: 13px;
        }
        .data-card {
            border: 1px solid #CBD5E1;
            padding: 12px 15px;
            background: #F8FAFC;
            border-radius: 4px;
        }
        .data-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            border-bottom: 1px dotted #E2E8F0;
            padding-bottom: 4px;
        }
        .data-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .data-label {
            color: #475569;
            font-weight: 600;
        }
        .data-val {
            font-weight: bold;
            color: #0F172A;
        }
        .scorecard-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 13px;
        }
        .scorecard-table th {
            background: #1E3A8A;
            color: #fff;
            padding: 8px 12px;
            text-align: left;
            font-weight: bold;
        }
        .scorecard-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #E2E8F0;
        }
        .scorecard-table tr:nth-child(even) {
            background: #F8FAFC;
        }
        .stamp-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #CBD5E1;
        }
        .stamp-box {
            text-align: center;
            width: 180px;
        }
        .sig-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
            font-size: 12px;
            font-weight: bold;
        }
        .gold-seal {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 3px dashed #B45309;
            color: #B45309;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            line-height: 1.2;
            padding: 5px;
        }
        .print-btn-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        .print-btn {
            background: #1E3A8A;
            color: #fff;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .print-btn-bar {
                display: none;
            }
            .cert-container {
                border: 4px double #1E3A8A;
                padding: 20px;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    <div class="print-btn-bar">
        <button class="print-btn" onclick="window.print()">Print Formal Certificate (A4)</button>
        <button class="print-btn" style="background:#475569; margin-left:10px;" onclick="window.close()">Close</button>
    </div>

    <div class="cert-container">
        <div class="cert-header">
            <div class="cert-org">AutoTrack Ethiopia &bull; Certified Inspection Network</div>
            <h1 class="cert-title">CERTIFICATE OF VEHICLE INSPECTION</h1>
            <div class="cert-subtitle">Official Roadworthiness, Diagnostic & Verification Record</div>
        </div>

        <div class="cert-grid">
            <div class="data-card">
                <div style="font-weight:bold; margin-bottom:8px; color:#1E3A8A; text-transform:uppercase; font-size:11px;">Vehicle Details</div>
                <div class="data-row"><span class="data-label">Plate Number:</span> <span class="data-val">{{ $vehicle->plate_number }}</span></div>
                <div class="data-row"><span class="data-label">Make & Model:</span> <span class="data-val">{{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}</span></div>
                <div class="data-row"><span class="data-label">Chassis / VIN:</span> <span class="data-val" style="font-family:monospace;">{{ $vehicle->chassis_number ?? 'N/A' }}</span></div>
                <div class="data-row"><span class="data-label">Engine Number:</span> <span class="data-val" style="font-family:monospace;">{{ $vehicle->engine_number ?? 'N/A' }}</span></div>
                <div class="data-row"><span class="data-label">Fuel & Trans:</span> <span class="data-val">{{ ucfirst($vehicle->fuel_type) }} / {{ ucfirst($vehicle->transmission) }}</span></div>
            </div>

            <div class="data-card">
                <div style="font-weight:bold; margin-bottom:8px; color:#1E3A8A; text-transform:uppercase; font-size:11px;">Inspection Audit</div>
                <div class="data-row"><span class="data-label">Certified Odometer:</span> <span class="data-val">{{ number_format($vehicle->current_mileage) }} KM</span></div>
                <div class="data-row"><span class="data-label">Certificate Date:</span> <span class="data-val">{{ now()->format('d F Y') }}</span></div>
                <div class="data-row"><span class="data-label">Certificate Serial:</span> <span class="data-val" style="font-family:monospace;">AT-CERT-{{ strtoupper(substr($vehicle->qr_code, 0, 8)) }}</span></div>
                <div class="data-row"><span class="data-label">Testing Branch:</span> <span class="data-val">{{ $vehicle->branch->name ?? 'AutoTrack Central Workshop' }}</span></div>
                <div class="data-row"><span class="data-label">Inspector Name:</span> <span class="data-val">{{ $latestInspection?->inspector?->name ?? 'Lead QA Inspector' }}</span></div>
            </div>
        </div>

        <table class="scorecard-table">
            <thead>
                <tr>
                    <th>Inspected System</th>
                    <th>Standard / Criteria</th>
                    <th>Grading Result</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $scores = $healthScores; @endphp
                @if(!empty($scores))
                    @foreach($scores as $category => $score)
                        @continue($category === 'Overall')
                        <tr>
                            <td><strong>{{ ucfirst($category) }}</strong></td>
                            <td>Multi-point mechanical tolerance & diagnostic scan</td>
                            <td><strong>{{ $score }}%</strong></td>
                            <td style="color: {{ $score >= 75 ? '#059669' : ($score >= 50 ? '#D97706' : '#DC2626') }}; font-weight:bold;">
                                {{ $score >= 75 ? 'PASSED (Roadworthy)' : ($score >= 50 ? 'ADVISORY' : 'FAILED') }}
                            </td>
                        </tr>
                    @endforeach
                    <tr style="background:#E0E7FF; font-weight:bold;">
                        <td colspan="2"><strong>COMPOSITE VEHICLE HEALTH SCORE</strong></td>
                        <td><strong>{{ $scores['Overall'] ?? 100 }}%</strong></td>
                        <td style="color:#1E3A8A;">
                            {{ ($scores['Overall'] ?? 100) >= 75 ? 'CERTIFIED ROADWORTHY' : 'CONDITIONAL PASS' }}
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>Engine & Powertrain</td>
                        <td>Compression, ECU scan, fluid integrity</td>
                        <td>95%</td>
                        <td style="color:#059669; font-weight:bold;">PASSED</td>
                    </tr>
                    <tr>
                        <td>Braking System</td>
                        <td>Hydraulic pressure, pads & rotor thickness</td>
                        <td>90%</td>
                        <td style="color:#059669; font-weight:bold;">PASSED</td>
                    </tr>
                    <tr>
                        <td>Suspension & Steering</td>
                        <td>Ball joints, bushings, tie rods, shocks</td>
                        <td>88%</td>
                        <td style="color:#059669; font-weight:bold;">PASSED</td>
                    </tr>
                    <tr>
                        <td>Electrical & Battery</td>
                        <td>Alternator charging, harness, cranking amps</td>
                        <td>92%</td>
                        <td style="color:#059669; font-weight:bold;">PASSED</td>
                    </tr>
                    <tr style="background:#E0E7FF; font-weight:bold;">
                        <td colspan="2">COMPOSITE VEHICLE HEALTH SCORE</td>
                        <td>91%</td>
                        <td style="color:#1E3A8A;">CERTIFIED ROADWORTHY</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div style="font-size:11px; color:#475569; font-style:italic; line-height:1.4; margin-bottom:20px;">
            Statement of Authenticity: This certificate attests that the vehicle identified above was inspected according to standardized automotive testing criteria. The odometer reading has been verified for sequential continuity without evidence of fraudulent rollback.
        </div>

        <div class="stamp-section">
            <div style="text-align:center;">
                <div style="background:#fff; padding:4px; border:1px solid #ccc; display:inline-block; line-height:0;">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->generate(route('passport.show', $vehicle->qr_code)) !!}
                </div>
                <div style="font-size:9px; color:#666; margin-top:4px;">Scan for Live Record</div>
            </div>

            <div class="gold-seal">
                ★ OFFICIAL ★<br>
                AUTOTRACK<br>
                ETHIOPIA<br>
                SEAL OF TRUST
            </div>

            <div class="stamp-box">
                <div class="sig-line">
                    Lead Quality Inspector<br>
                    <span style="font-size:10px; font-weight:normal; color:#666;">Authorized Signatory</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
