@extends('layouts.portal')

@section('title', 'Service Appointments')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h4 fw-bold text-dark mb-1">Service Appointments</h1>
            <div class="text-muted small">Manage your upcoming and past garage booking requests.</div>
        </div>
        <div>
            <a href="{{ route('portal.appointments.create') }}" class="btn-adminator-primary">
                <i class="ti ti-calendar-plus"></i> Schedule New Appointment
            </a>
        </div>
    </div>

    @if ($appointments->isEmpty())
        <div class="adminator-card">
            <div class="adminator-card-body text-center py-5">
                <div class="icon-holder c-blue-500 mx-auto mb-3" style="width: 52px; height: 52px; font-size: 26px;">
                    <i class="ti ti-calendar-off"></i>
                </div>
                <h4 class="fw-bold mb-1">No service appointments found</h4>
                <p class="text-muted small mb-3">You have no upcoming or past maintenance bookings on file.</p>
                <a href="{{ route('portal.appointments.create') }}" class="btn-adminator-primary">
                    <i class="ti ti-calendar-plus"></i> Book an Appointment
                </a>
            </div>
        </div>
    @else
        <div class="adminator-card">
            <div class="adminator-card-header">
                <h2 class="adminator-card-title">
                    <i class="ti ti-calendar text-primary"></i> Booking Requests & Scheduled Visits
                </h2>
                <div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold">
                        {{ $appointments->count() }} Total
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13.5px;">
                    <thead class="table-light text-muted text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">
                        <tr>
                            <th class="ps-3 py-3">Appointment Date</th>
                            <th class="py-3">Vehicle</th>
                            <th class="py-3">Requested Services</th>
                            <th class="py-3">Status</th>
                            <th class="pe-3 py-3">Intake Workshop</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $appt)
                            <tr>
                                <td class="ps-3 py-3">
                                    <div class="fw-semibold text-dark">
                                        <i class="ti ti-calendar me-1 text-primary"></i>
                                        {{ $appt->requested_date->format('D, M d, Y') }}
                                    </div>
                                    <div class="text-muted small" style="font-size: 11.5px;">
                                        <i class="ti ti-clock me-0.5"></i> Slot: {{ ucfirst($appt->requested_time_slot ?: 'Flexible') }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    @if ($appt->vehicle)
                                        <div class="mb-1">
                                            <span class="plate-badge-et">
                                                {{ $appt->vehicle->plate_number }}
                                            </span>
                                        </div>
                                        <div class="small text-muted">
                                            {{ $appt->vehicle->make }} {{ $appt->vehicle->model }}
                                        </div>
                                    @else
                                        <span class="text-muted">&mdash;</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($appt->service_types ?? [] as $type)
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 11px;">
                                                {{ \App\Http\Controllers\Portal\AppointmentController::SERVICE_TYPES[$type] ?? ucwords(str_replace('_', ' ', $type)) }}
                                            </span>
                                        @endforeach
                                    </div>
                                    @if ($appt->other_service_description)
                                        <div class="text-muted small mt-1 fst-italic" style="font-size: 11.5px;">
                                            <i class="ti ti-info-circle me-0.5"></i>{{ $appt->other_service_description }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3">
                                    @if ($appt->status === 'pending')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle" style="font-size: 11.5px;">
                                            <i class="ti ti-clock me-1"></i> Pending Review
                                        </span>
                                    @elseif ($appt->status === 'confirmed')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 11.5px;">
                                            <i class="ti ti-circle-check me-1"></i> Confirmed
                                        </span>
                                    @elseif ($appt->status === 'cancelled' || $appt->status === 'rejected')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 11.5px;">
                                            <i class="ti ti-x me-1"></i> Cancelled
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 11.5px;">{{ ucfirst($appt->status) }}</span>
                                    @endif
                                </td>
                                <td class="pe-3 py-3">
                                    <div class="fw-semibold text-dark" style="font-size: 12.5px;">Zoom EV Bole (HQ)</div>
                                    <div class="text-muted small" style="font-size: 11px;">Bole Road, Addis Ababa</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
