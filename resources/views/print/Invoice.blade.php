@extends('layouts.print')

@section('title', 'Invoice ' . $invoice->invoice_number)

@section('content')
    <div class="letterhead">
        <div>
            <div class="brand">{{ $invoice->branch->name ?? 'AutoTrack Ethiopia' }}</div>
            <div class="branch-info">
                {{ $invoice->branch->address ?? '' }}<br>
                {{ $invoice->branch->phone ?? '' }}
            </div>
        </div>
        <div class="doc-meta">
            <div class="doc-title">INVOICE</div>
            {{ $invoice->invoice_number }}<br>
            Issued: {{ optional($invoice->issued_at)->format('d M Y') }}<br>
            Status: {{ ucfirst($invoice->status) }}
        </div>
    </div>

    <div style="margin-bottom: 16px; font-size: 13px;">
        <strong>Billed to:</strong><br>
        {{ $invoice->customer->display_name ?? '—' }}<br>
        {{ $invoice->customer->phone ?? '' }}
        @if ($invoice->jobCard && $invoice->jobCard->vehicle)
            <br><strong>Vehicle:</strong> {{ $invoice->jobCard->vehicle->plate_number }} — {{ $invoice->jobCard->vehicle->make }} {{ $invoice->jobCard->vehicle->model }}
        @endif
    </div>

    <table>
        <thead>
        <tr>
            <th>Description</th>
            <th>Type</th>
            <th class="text-end">Qty</th>
            <th class="text-end">Unit Price</th>
            <th class="text-end">Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($invoice->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td>{{ ucfirst($item->item_type) }}</td>
                <td class="text-end">{{ $item->quantity }}</td>
                <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-end">{{ number_format($item->total, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Subtotal</td><td class="text-end">{{ number_format($invoice->subtotal, 2) }} ETB</td></tr>
        <tr><td>Discount</td><td class="text-end">-{{ number_format($invoice->discount, 2) }} ETB</td></tr>
        <tr><td>Tax</td><td class="text-end">{{ number_format($invoice->tax, 2) }} ETB</td></tr>
        <tr class="grand"><td>Total</td><td class="text-end">{{ number_format($invoice->total, 2) }} ETB</td></tr>
        <tr><td>Paid</td><td class="text-end">{{ number_format($invoice->paid_amount, 2) }} ETB</td></tr>
        <tr><td>Balance due</td><td class="text-end">{{ number_format($invoice->balance, 2) }} ETB</td></tr>
    </table>
@endsection
