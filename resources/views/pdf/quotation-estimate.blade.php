<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quotation #{{ $quotation->quotation_number }} - Zoom EV</title>
    <style>
        @page {
            margin: 18mm 16mm 18mm 16mm;
            size: A4 portrait;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.45;
            color: #24292e;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table {
            margin-bottom: 16px;
            border-bottom: 2px solid #1E69B8;
            padding-bottom: 12px;
        }
        .logo-cell {
            vertical-align: top;
            width: 55%;
        }
        .brand-title {
            font-size: 20px;
            font-weight: bold;
            color: #1E69B8;
            letter-spacing: -0.5px;
            margin: 0 0 2px 0;
        }
        .brand-subtitle {
            font-size: 9px;
            color: #586069;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
        }
        .company-meta {
            font-size: 9px;
            color: #586069;
            line-height: 1.35;
        }
        .quote-info-cell {
            vertical-align: top;
            width: 45%;
            text-align: right;
        }
        .quote-badge {
            display: inline-block;
            background: #EBF3FA;
            color: #1E69B8;
            border: 1px solid #C6DCF1;
            padding: 4px 10px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .quote-number {
            font-size: 17px;
            font-weight: bold;
            color: #1a202c;
            margin: 0 0 4px 0;
        }
        .meta-line {
            font-size: 9.5px;
            color: #586069;
            margin: 2px 0;
        }
        .meta-line strong {
            color: #24292e;
        }

        .cards-table {
            margin-bottom: 14px;
        }
        .card-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 10px 12px;
            vertical-align: top;
        }
        .card-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #1E69B8;
            margin-bottom: 6px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .info-row {
            font-size: 9.5px;
            margin-bottom: 3px;
        }
        .info-label {
            color: #64748b;
            display: inline-block;
            width: 80px;
        }
        .info-val {
            color: #1e293b;
            font-weight: 500;
        }

        .section-header {
            background-color: #EBF3FA;
            border-left: 3px solid #1E69B8;
            padding: 5px 8px;
            font-size: 10.5px;
            font-weight: bold;
            color: #1E3A5F;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 14px;
            margin-bottom: 8px;
        }
        .assessment-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 8px 10px;
            font-size: 9.5px;
            color: #334155;
            line-height: 1.45;
            margin-bottom: 12px;
        }

        .data-table {
            width: 100%;
            margin-bottom: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 6px 8px;
            text-align: left;
            border-bottom: 1px solid #cbd5e1;
        }
        .data-table td {
            padding: 6px 8px;
            font-size: 9.5px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        .data-table tr:last-child td {
            border-bottom: none;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-semibold {
            font-weight: 600;
        }

        .summary-container {
            margin-top: 10px;
            margin-bottom: 14px;
        }
        .notes-cell {
            vertical-align: top;
            width: 55%;
            padding-right: 12px;
        }
        .totals-cell {
            vertical-align: top;
            width: 45%;
        }
        .totals-table {
            width: 100%;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .totals-table td {
            padding: 5px 10px;
            font-size: 9.5px;
        }
        .grand-total-row {
            background-color: #1E69B8;
            color: #ffffff;
        }
        .grand-total-row td {
            padding: 8px 10px;
            font-size: 12px;
            font-weight: bold;
            color: #ffffff;
        }

        .terms-box {
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 4px;
            padding: 8px 10px;
            font-size: 8px;
            color: #64748b;
            line-height: 1.35;
        }
        .terms-title {
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
            font-size: 8.5px;
            margin-bottom: 3px;
        }

        .footer-table {
            margin-top: 18px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        .sign-box {
            text-align: center;
            width: 33%;
            vertical-align: bottom;
            font-size: 8.5px;
            color: #64748b;
        }
        .sign-line {
            border-top: 1px solid #94a3b8;
            margin: 25px 20px 4px 20px;
        }
    </style>
</head>
<body>

    <!-- Header Block -->
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <div class="brand-title">ZOOM EV</div>
                <div class="brand-subtitle">Certified Digital Vehicle Workshop & Diagnostics Center</div>
                <div class="company-meta">
                    Bole Medhanialem, Bole Sub-City, Addis Ababa, Ethiopia<br>
                    Hotline: +251 911 000 000 &bull; +251 912 345 678<br>
                    Email: support@zoomev.et &bull; Web: www.zoomev.et<br>
                    TIN: 0048291048 &bull; VAT Reg. No: 98217349
                </div>
            </td>
            <td class="quote-info-cell">
                <div class="quote-badge">Official Price Quotation</div>
                <div class="quote-number">#{{ $quotation->quotation_number }}</div>
                <div class="meta-line"><strong>Date:</strong> {{ $quotation->quoted_at ? $quotation->quoted_at->format('d M Y') : now()->format('d M Y') }}</div>
                <div class="meta-line"><strong>Valid Until:</strong> {{ $quotation->valid_until ? $quotation->valid_until->format('d M Y') : now()->addDays(14)->format('d M Y') }}</div>
                <div class="meta-line"><strong>Turnaround:</strong> {{ $quotation->estimated_duration ?: '1-2 Business Days' }}</div>
                <div class="meta-line"><strong>Advisor:</strong> {{ $quotation->quotedBy ? $quotation->quotedBy->name : 'Technical Service Engineer' }}</div>
            </td>
        </tr>
    </table>

    <!-- Customer & Vehicle Specifications -->
    <table class="cards-table">
        <tr>
            <td class="card-box" style="width: 48%; padding-right: 10px;">
                <div class="card-title">Customer Information</div>
                <div class="info-row"><span class="info-label">Full Name:</span> <span class="info-val">{{ $quotation->customer_name }}</span></div>
                <div class="info-row"><span class="info-label">Phone:</span> <span class="info-val">{{ $quotation->phone }}</span></div>
                <div class="info-row"><span class="info-label">Email:</span> <span class="info-val">{{ $quotation->email }}</span></div>
                <div class="info-row"><span class="info-label">City / Location:</span> <span class="info-val">{{ $quotation->city ?: 'Addis Ababa' }}</span></div>
            </td>
            <td style="width: 4%;"></td>
            <td class="card-box" style="width: 48%;">
                <div class="card-title">Vehicle Specifications</div>
                <div class="info-row"><span class="info-label">Vehicle:</span> <span class="info-val">{{ $quotation->year ? $quotation->year . ' ' : '' }}{{ $quotation->make }} {{ $quotation->model }}</span></div>
                <div class="info-row"><span class="info-label">Plate Number:</span> <span class="info-val">{{ $quotation->plate_number ?: 'Not Registered' }}</span></div>
                <div class="info-row"><span class="info-label">Odometer:</span> <span class="info-val">{{ $quotation->mileage ? number_format($quotation->mileage) . ' km' : 'N/A' }}</span></div>
                <div class="info-row"><span class="info-label">Chassis (VIN):</span> <span class="info-val">{{ $quotation->vin ?: 'Not Recorded' }}</span></div>
            </td>
        </tr>
    </table>

    <!-- Diagnostic Assessment & Scope of Work -->
    <div class="section-header">Diagnostic Defect Assessment & Recommended Scope of Work</div>
    <div class="assessment-box">
        <strong>Service Category:</strong> {{ $quotation->service_category }} &bull;
        <strong>Drivable Status:</strong> {{ ucfirst(str_replace('_', ' ', $quotation->drivable_status)) }} &bull;
        <strong>Urgency:</strong> {{ match($quotation->urgency) { 'urgent_asap' => 'Urgent (ASAP)', 'within_week' => 'Within 7 Days', default => 'Flexible' } }}<br>
        @if(!empty($quotation->symptoms))
            <div style="margin-top: 4px; margin-bottom: 4px;">
                <strong>Reported Symptoms:</strong> {{ implode(', ', $quotation->symptoms) }}
            </div>
        @endif
        <div style="margin-top: 6px; border-top: 1px dashed #cbd5e1; padding-top: 5px;">
            <strong>Technical Assessment & Work Scope:</strong><br>
            {{ $quotation->diagnostic_assessment ?: 'Comprehensive multi-point workshop diagnostic inspection, fault tracing, precision defect correction, and post-repair quality assurance test.' }}
        </div>
    </div>

    <!-- Labor Items Table -->
    @if(!empty($quotation->labor_items) && count($quotation->labor_items) > 0)
        <div class="section-header" style="margin-top: 10px;">1. Labor & Specialized Workshop Operations</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%;">#</th>
                    <th style="width: 67%;">Operation / Service Description</th>
                    <th class="text-right" style="width: 25%;">Estimated Labor (ETB)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotation->labor_items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item['description'] ?? 'Workshop Labor' }}</td>
                        <td class="text-right font-semibold">{{ number_format((float)($item['estimated_cost'] ?? 0), 2) }} ETB</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Parts Items Table -->
    @if(!empty($quotation->parts_items) && count($quotation->parts_items) > 0)
        <div class="section-header" style="margin-top: 10px;">2. Required Replacement Parts & Materials</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%;">#</th>
                    <th style="width: 52%;">Part / Component Name</th>
                    <th class="text-center" style="width: 12%;">Qty</th>
                    <th class="text-right" style="width: 14%;">Unit Cost (ETB)</th>
                    <th class="text-right" style="width: 14%;">Total (ETB)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotation->parts_items as $index => $part)
                    @php
                        $qty = (int)($part['quantity'] ?? 1);
                        $unitCost = (float)($part['estimated_cost'] ?? 0);
                        $total = (float)($part['total'] ?? ($qty * $unitCost));
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $part['part_name'] ?? 'Replacement Part' }}</td>
                        <td class="text-center">{{ $qty }}</td>
                        <td class="text-right">{{ number_format($unitCost, 2) }}</td>
                        <td class="text-right font-semibold">{{ number_format($total, 2) }} ETB</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Totals Summary & Terms -->
    <table class="summary-container">
        <tr>
            <td class="notes-cell">
                <div class="terms-box">
                    <div class="terms-title">Quotation Terms & Service Warranty</div>
                    &bull; <strong>Warranty:</strong> All installed genuine parts and repair labor are protected by our 6-Month / 10,000 KM workshop warranty.<br>
                    &bull; <strong>Validity:</strong> This quotation estimate is valid until <strong>{{ $quotation->valid_until ? $quotation->valid_until->format('d M Y') : now()->addDays(14)->format('d M Y') }}</strong>.<br>
                    &bull; <strong>Inspection:</strong> Final costs may vary if concealed internal damage is uncovered during vehicle disassembly.<br>
                    &bull; <strong>Acceptance:</strong> To confirm this quotation and schedule your service slot, contact us via phone or click the booking link sent to your email.
                </div>
            </td>
            <td class="totals-cell">
                <table class="totals-table">
                    <tr>
                        <td>Labor Subtotal:</td>
                        <td class="text-right font-semibold">{{ number_format($quotation->estimated_labor_total, 2) }} ETB</td>
                    </tr>
                    <tr>
                        <td>Parts & Materials Subtotal:</td>
                        <td class="text-right font-semibold">{{ number_format($quotation->estimated_parts_total, 2) }} ETB</td>
                    </tr>
                    @if($quotation->estimated_tax > 0)
                        <tr>
                            <td>VAT / Taxes:</td>
                            <td class="text-right font-semibold">{{ number_format($quotation->estimated_tax, 2) }} ETB</td>
                        </tr>
                    @endif
                    @if($quotation->estimated_discount > 0)
                        <tr>
                            <td>Discount / Promotion:</td>
                            <td class="text-right font-semibold" style="color: #16a34a;">-{{ number_format($quotation->estimated_discount, 2) }} ETB</td>
                        </tr>
                    @endif
                    <tr class="grand-total-row">
                        <td>TOTAL ESTIMATE:</td>
                        <td class="text-right">{{ number_format($quotation->estimated_total, 2) }} ETB</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Signature Sign-off Block -->
    <table class="footer-table">
        <tr>
            <td class="sign-box">
                <div class="sign-line"></div>
                <div>Prepared By: Service Engineering</div>
                <div style="color: #94a3b8; font-size: 7.5px;">Zoom EV Bole HQ</div>
            </td>
            <td class="sign-box">
                <div class="sign-line"></div>
                <div>Authorized Workshop Manager</div>
                <div style="color: #94a3b8; font-size: 7.5px;">Seal & Stamp Certified</div>
            </td>
            <td class="sign-box">
                <div class="sign-line"></div>
                <div>Customer Approval / Signature</div>
                <div style="color: #94a3b8; font-size: 7.5px;">Date: ____________________</div>
            </td>
        </tr>
    </table>

</body>
</html>
