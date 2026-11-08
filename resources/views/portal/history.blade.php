@extends('layouts.portal')

@section('title', 'Service History Logbook')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h4 fw-bold text-dark mb-1">Service History Logbook</h1>
            <div class="text-muted small">Tamper-proof certified garage records, monotonic odometers, and digital passports.</div>
        </div>
        <div>
            <a href="{{ route('portal.appointments.create') }}" class="btn-adminator-primary">
                <i class="ti ti-calendar-plus"></i> Book a Service
            </a>
        </div>
    </div>

    @if ($jobCards->isEmpty())
        <div class="adminator-card">
            <div class="adminator-card-body text-center py-5">
                <div class="icon-holder c-blue-500 mx-auto mb-3" style="width: 52px; height: 52px; font-size: 26px;">
                    <i class="ti ti-clipboard-off"></i>
                </div>
                <h4 class="fw-bold mb-1">No completed service records found</h4>
                <p class="text-muted small mb-3">Once your vehicles are serviced and finalized at Zoom AutoTrack Bole, their full diagnostic history and invoices will appear here.</p>
                <a href="{{ route('portal.appointments.create') }}" class="btn-adminator-primary">
                    <i class="ti ti-calendar-plus"></i> Book a Service
                </a>
            </div>
        </div>
    @else
        <div class="adminator-card">
            <div class="adminator-card-header">
                <h2 class="adminator-card-title">
                    <i class="ti ti-history text-primary"></i> Completed Work Orders & Maintenance History
                </h2>
                <div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold">
                        <i class="ti ti-shield-check me-1"></i> Digitally Verified
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13.5px;">
                    <thead class="table-light text-muted text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">
                        <tr>
                            <th class="ps-3 py-3">Completion Date</th>
                            <th class="py-3">Vehicle</th>
                            <th class="py-3">Work Order #</th>
                            <th class="py-3">Service & Notes Summary</th>
                            <th class="py-3">Odometer</th>
                            <th class="py-3">Certification</th>
                            <th class="pe-3 py-3 text-end">Digital Passport</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobCards as $job)
                            <tr>
                                <td class="ps-3 py-3">
                                    <div class="fw-semibold text-dark">
                                        <i class="ti ti-calendar me-1 text-primary"></i>
                                        {{ $job->completed_at ? $job->completed_at->format('M d, Y') : 'In Progress' }}
                                    </div>
                                    <div class="text-muted small font-monospace" style="font-size: 11px;">
                                        {{ $job->completed_at ? $job->completed_at->format('h:i A') : '' }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="mb-1">
                                        <span class="plate-badge-et">
                                            {{ $job->vehicle->plate_number }}
                                        </span>
                                    </div>
                                    <div class="small text-muted">
                                        {{ $job->vehicle->make }} {{ $job->vehicle->model }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-dark text-white font-monospace" style="font-size: 11.5px;">
                                        #{{ $job->job_number }}
                                    </span>
                                </td>
                                <td class="py-3" style="max-width: 320px;">
                                    <div class="fw-medium text-dark text-truncate">
                                        {{ $job->customer_complaint ?: 'Scheduled Routine Maintenance & Inspection' }}
                                    </div>
                                    <div class="text-muted small" style="font-size: 11.5px;">
                                        Branch: {{ $job->branch ? $job->branch->name : 'Zoom AutoTrack Bole (HQ)' }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="fw-semibold text-dark small d-flex align-items-center">
                                        <i class="ti ti-gauge me-1 text-muted"></i>
                                        {{ $job->mileage_at_checkin ? number_format($job->mileage_at_checkin) . ' km' : 'Recorded' }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 11px;">
                                        <i class="ti ti-check me-0.5"></i> Certified Safe
                                    </span>
                                </td>
                                <td class="pe-3 py-3 text-end">
                                    <a href="{{ $job->vehicle->passportUrl() }}" target="_blank" class="btn-adminator-secondary" style="font-size: 12px; padding: 5px 10px;">
                                        <i class="ti ti-id-badge-2 text-primary"></i> Passport
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
