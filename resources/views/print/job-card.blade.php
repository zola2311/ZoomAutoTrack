@extends('layouts.print')

@section('title', 'Job Card ' . $jobCard->job_number)

@section('content')
    <div class="letterhead">
        <div>
            <div class="brand">{{ $jobCard->branch->name ?? 'AutoTrack Ethiopia' }}</div>
            <div class="branch-info">
                {{ $jobCard->branch->address ?? '' }}<br>
                {{ $jobCard->branch->phone ?? '' }}
            </div>
        </div>
        <div class="doc-meta">
            <div class="doc-title">JOB CARD</div>
            {{ $jobCard->job_number }}<br>
            Checked in: {{ optional($jobCard->checked_in_at)->format('d M Y H:i') }}<br>
            Status: {{ ucfirst(str_replace('_', ' ', $jobCard->status)) }}
        </div>
    </div>

    <div style="margin-bottom: 16px; font-size: 13px;">
        <strong>Customer:</strong> {{ $jobCard->customer->display_name ?? '—' }} &middot; {{ $jobCard->customer->phone ?? '' }}<br>
        <strong>Vehicle:</strong> {{ $jobCard->vehicle->plate_number ?? '—' }} — {{ $jobCard->vehicle->make ?? '' }} {{ $jobCard->vehicle->model ?? '' }}<br>
        <strong>Mileage at check-in:</strong> {{ number_format($jobCard->mileage_at_checkin) }} km
        &middot;
        <strong>Mechanic:</strong> {{ $jobCard->mechanic->name ?? 'Unassigned' }}
    </div>

    <div style="margin-bottom: 16px; font-size: 13px;">
        <strong>Customer complaint:</strong><br>
        {{ $jobCard->customer_complaint ?: '—' }}
    </div>

    @if ($jobCard->mechanic_notes)
        <div style="margin-bottom: 16px; font-size: 13px;">
            <strong>Mechanic notes:</strong><br>
            {{ $jobCard->mechanic_notes }}
        </div>
    @endif

    @if ($jobCard->services->isNotEmpty())
        <table>
            <thead>
            <tr>
                <th>Service</th>
                <th class="text-end">Labor cost</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($jobCard->services as $service)
                <tr>
                    <td>{{ $service->description }}</td>
                    <td class="text-end">{{ number_format($service->labor_cost, 2) }} ETB</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    @if ($jobCard->partsUsed->isNotEmpty())
        <table>
            <thead>
            <tr>
                <th>Part</th>
                <th class="text-end">Qty</th>
                <th class="text-end">Unit Price</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($jobCard->partsUsed as $part)
                <tr>
                    <td>{{ $part->inventoryItem->name ?? 'Part' }}</td>
                    <td class="text-end">{{ $part->quantity }}</td>
                    <td class="text-end">{{ number_format($part->unit_price, 2) }} ETB</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection
