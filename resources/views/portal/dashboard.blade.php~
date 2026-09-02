@extends('layouts.portal')

@section('title', 'My Vehicles')

@section('content')
    <div class="d-flex align-items-center mb-3 mt-4">
        <h2 class="page-title">My Vehicles</h2>
        <div class="ms-auto">
            <span class="badge bg-blue-lt">{{ $customer->loyalty_points }} loyalty points</span>
        </div>
    </div>

    @php
        $reminders = $vehicles->map(fn ($v) => ['vehicle' => $v, 'due' => $v->nextServiceDue()])
            ->filter(fn ($r) => $r['due']['is_due'] || $r['due']['km_remaining'] <= 500);
    @endphp

    @if ($reminders->isNotEmpty())
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Reminders</h3>
            </div>
            <div class="list-group list-group-flush">
                @foreach ($reminders as $r)
                    <div class="list-group-item d-flex align-items-center">
                        <div>
                            <strong>{{ $r['vehicle']->plate_number }}</strong>
                            —
                            @if ($r['due']['is_due'])
                                service is due now
                            @else
                                only {{ number_format($r['due']['km_remaining']) }} km until next service
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($vehicles->isEmpty())
        <div class="card">
            <div class="card-body text-center text-secondary">
                No vehicles on your account yet. Visit a garage to get your first vehicle registered.
            </div>
        </div>
    @endif

    <div class="row row-cards">
        @foreach ($vehicles as $vehicle)
            @php $due = $vehicle->nextServiceDue(); @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="fw-bold">{{ $vehicle->plate_number }}</div>
                            <div class="ms-auto">
                                @if ($due['is_due'])
                                    <span class="badge bg-red-lt">Service due</span>
                                @else
                                    <span class="badge bg-green-lt">On track</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-secondary mb-3">
                            {{ $vehicle->make }} {{ $vehicle->model }}
                            @if ($vehicle->year)
                                ({{ $vehicle->year }})
                            @endif
                        </div>   <div class="text-secondary small mb-3">
                            {{ number_format($vehicle->current_mileage) }} km
                            &middot;
                            @if ($due['km_remaining'] > 0)
                                {{ number_format($due['km_remaining']) }} km until next service
                            @else
                                due now
                            @endif
                        </div>

                        <a href="{{ $vehicle->passportUrl() }}" target="_blank" class="btn btn-outline-primary w-100">
                            View full history
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
