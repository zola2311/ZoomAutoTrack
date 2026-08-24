@extends('layouts.portal')

@section('title', 'Book an appointment')

@section('content')

    <h2 class="page-title mb-3 mt-4">
        Book an appointment
    </h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($vehicles->isEmpty())

        <div class="card">
            <div class="card-body text-center text-secondary">

                <p class="mb-2">
                    You have no vehicles on your account yet.
                </p>

                <p class="mb-0">
                    Please add a vehicle before booking an appointment.
                </p>

            </div>
        </div>

    @else

        <div class="card">

            <div class="card-body">

                <form
                    method="POST"
                    action="{{ route('portal.appointments.store') }}"
                >

                    @csrf


                    {{-- Vehicle --}}

                    <div class="mb-4">

                        <label class="form-label">
                            Vehicle
                        </label>

                        <select
                            name="vehicle_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select a vehicle
                            </option>

                            @foreach ($vehicles as $vehicle)

                                <option
                                    value="{{ $vehicle->id }}"
                                    @selected(
                                        old('vehicle_id') == $vehicle->id
                                    )
                                >

                                    {{ $vehicle->plate_number }}
                                    —
                                    {{ $vehicle->make }}
                                    {{ $vehicle->model }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Services --}}

                    <div class="mb-4">

                        <label class="form-label mb-1">

                            What service do you need?

                        </label>

                        <div class="form-hint mb-3">

                            You can select one or more services.

                        </div>


                        <div class="row g-2">

                            @foreach ($serviceTypes as $value => $label)

                                <div class="col-md-6">

                                    <label
                                        class="form-selectgroup-item w-100"
                                    >

                                        <input
                                            type="checkbox"
                                            name="service_types[]"
                                            value="{{ $value }}"
                                            class="form-selectgroup-input service-checkbox"
                                            @checked(
                                                in_array(
                                                    $value,
                                                    old(
                                                        'service_types',
                                                        []
                                                    )
                                                )
                                            )
                                        >

                                        <span
                                            class="form-selectgroup-label d-flex align-items-center"
                                        >

                                            {{ $label }}

                                        </span>

                                    </label>

                                </div>

                            @endforeach

                        </div>

                    </div>


                    {{-- Other Service --}}

                    <div
                        id="other-service-container"
                        class="mb-4"
                        style="display: none;"
                    >

                        <label class="form-label">

                            Please describe the service you need

                        </label>

                        <textarea
                            name="other_service"
                            class="form-control"
                            rows="3"
                            maxlength="500"
                            placeholder="Describe the service you need..."
                        >{{ old('other_service') }}</textarea>

                    </div>


                    {{-- Date and Time --}}

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Preferred date

                            </label>

                            <input
                                type="date"
                                name="requested_date"
                                value="{{ old('requested_date') }}"
                                class="form-control"
                                min="{{ now()->toDateString() }}"
                                required
                            >

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Preferred time

                            </label>

                            <select
                                name="requested_time_slot"
                                class="form-select"
                            >

                                <option value="">
                                    No preference
                                </option>

                                <option
                                    value="morning"
                                    @selected(
                                        old('requested_time_slot')
                                        === 'morning'
                                    )
                                >
                                    Morning
                                </option>

                                <option
                                    value="afternoon"
                                    @selected(
                                        old('requested_time_slot')
                                        === 'afternoon'
                                    )
                                >
                                    Afternoon
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- Notes --}}

                    <div class="mb-4">

                        <label class="form-label">

                            Additional details
                            <span class="text-secondary">
                                (Optional)
                            </span>

                        </label>

                        <div class="form-hint mb-2">

                            Describe the problem or provide any additional information.

                        </div>

                        <textarea
                            name="notes"
                            class="form-control"
                            rows="4"
                            maxlength="1000"
                            placeholder="For example: There is a noise coming from the front of the vehicle..."
                        >{{ old('notes') }}</textarea>

                    </div>


                    {{-- Submit --}}

                    <div class="form-footer">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            Request appointment

                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif

@endsection


@push('scripts')

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const otherCheckbox = document.querySelector(
                    'input[name="service_types[]"][value="other"]'
                );

                const otherContainer = document.getElementById(
                    'other-service-container'
                );


                function toggleOtherService()
                {
                    if (! otherCheckbox) {
                        return;
                    }

                    if (otherCheckbox.checked) {

                        otherContainer.style.display = 'block';

                    } else {

                        otherContainer.style.display = 'none';

                    }
                }


                if (otherCheckbox) {

                    otherCheckbox.addEventListener(
                        'change',
                        toggleOtherService
                    );

                    toggleOtherService();

                }

            }
        );

    </script>

@endpush
