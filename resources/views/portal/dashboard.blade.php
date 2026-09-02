@extends('layouts.portal')

@section('title', 'My Vehicles')

@section('content')
    <div class="d-flex align-items-center mb-3 mt-4">
        <h2 class="page-title">My Vehicles</h2>
        <div class="ms-auto">
            <span class="badge bg-blue-lt">{{ $customer->loyalty_points }} loyalty points</span>
        </div>
    </div>

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

                        <div class="text-secondary mb-3">{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->year }})</div>

                        <div class="text-secondary small mb-3">
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
