@extends('layouts.portal')

@section('title', 'My Appointments')

@section('content')
    <div class="d-flex align-items-center mb-3 mt-4">
        <h2 class="page-title">My Appointments</h2>
        <div class="ms-auto">
            <a href="{{ route('portal.appointments.create') }}" class="btn btn-primary">Book appointment</a>
        </div>
    </div>

    @if ($appointments->isEmpty())
        <div class="card">
            <div class="card-body text-center text-secondary">
                No appointment requests yet.
            </div>
        </div>
    @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Vehicle</th>
                        <th>Service</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($appointments as $appt)
                        <tr>
                            <td>
                                {{ $appt->requested_date->format('d M Y') }}
                                @if ($appt->requested_time_slot)
                                    <span class="text-secondary">({{ ucfirst($appt->requested_time_slot) }})</span>
                                @endif
                            </td>
                            <td>{{ $appt->vehicle->plate_number ?? '—' }}</td>
                            <td>
                                @foreach ($appt->service_types ?? [] as $type)
                                    <span class="badge bg-blue-lt mb-1">
                                            {{ \App\Http\Controllers\Portal\AppointmentController::SERVICE_TYPES[$type] ?? $type }}
                                        </span>
                                @endforeach
                                @if ($appt->other_service_description)
                                    <div class="text-secondary small mt-1">{{ $appt->other_service_description }}</div>
                                @endif
                            </td>
                            <td>
                                @if ($appt->status === 'pending')
                                    <span class="badge bg-yellow-lt">Pending</span>
                                @elseif ($appt->status === 'confirmed')
                                    <span class="badge bg-green-lt">Confirmed</span>
                                @else
                                    <span class="badge bg-red-lt">Rejected</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
