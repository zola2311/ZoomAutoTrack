@extends('layouts.portal')

@section('title', 'Schedule Service Appointment')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h4 fw-bold text-dark mb-1">Schedule Service Appointment</h1>
            <div class="text-muted small">Select your vehicle, choose maintenance services, and pick a preferred date.</div>
        </div>
        <div>
            <a href="{{ route('portal.appointments.index') }}" class="btn-adminator-secondary">
                <i class="ti ti-arrow-left"></i> Back to Appointments
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-start">
                <span class="icon-holder c-red-500 me-3" style="width: 32px; height: 32px; font-size: 18px;">
                    <i class="ti ti-alert-circle"></i>
                </span>
                <div>
                    <div class="fw-bold mb-1">Please review the highlighted errors:</div>
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if ($vehicles->isEmpty())
        <div class="adminator-card">
            <div class="adminator-card-body text-center py-5">
                <div class="icon-holder c-blue-500 mx-auto mb-3" style="width: 52px; height: 52px; font-size: 26px;">
                    <i class="ti ti-car-off"></i>
                </div>
                <h4 class="fw-bold mb-1">No registered vehicles found</h4>
                <p class="text-muted small mb-0">You need an active vehicle on file to book an appointment through this portal.</p>
            </div>
        </div>
    @else
        <form method="POST" action="{{ route('portal.appointments.store') }}">
            @csrf

            <div class="row g-4">
                <!-- Left Form Section (Steps) -->
                <div class="col-lg-8">
                    
                    <!-- Step 1: Select Vehicle -->
                    <div class="adminator-card mb-4">
                        <div class="adminator-card-header">
                            <h2 class="adminator-card-title">
                                <span class="badge bg-primary text-white me-2" style="font-size: 11px; width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%;">1</span>
                                Select Registered Vehicle
                            </h2>
                        </div>
                        <div class="adminator-card-body">
                            <div class="mb-2">
                                <label class="form-label fw-semibold text-dark">Target Fleet Vehicle <span class="text-danger">*</span></label>
                                <select name="vehicle_id" class="form-select form-select-lg" style="font-size: 14px;" required>
                                    <option value="">-- Choose vehicle from your fleet --</option>
                                    @foreach ($vehicles as $v)
                                        <option value="{{ $v->id }}" @selected(old('vehicle_id', request('vehicle_id')) == $v->id)>
                                            {{ $v->plate_number }} — {{ $v->make }} {{ $v->model }} ({{ $v->year ?: 'Verified' }}) &bull; Current: {{ number_format($v->current_mileage) }} km
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-muted small mt-1.5" style="font-size: 11.5px;">Only vehicles verified and registered to your customer account are listed.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Choose Maintenance Services Needed -->
                    <div class="adminator-card mb-4">
                        <div class="adminator-card-header">
                            <h2 class="adminator-card-title">
                                <span class="badge bg-primary text-white me-2" style="font-size: 11px; width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%;">2</span>
                                Maintenance & Diagnostic Services
                            </h2>
                        </div>
                        <div class="adminator-card-body">
                            <label class="form-label fw-semibold text-dark mb-1">Select all services needed for this visit <span class="text-danger">*</span></label>
                            <p class="text-muted small mb-3" style="font-size: 12.5px;">Our certified workshop mechanics will perform complete intake diagnostics based on your selection.</p>

                            <div class="row g-2 mb-3">
                                @foreach ($serviceTypes as $val => $lbl)
                                    <div class="col-md-6">
                                        <label class="d-flex align-items-center p-3 rounded border w-100 cursor-pointer" style="background: var(--adminator-card-bg); border-color: var(--adminator-border); transition: all 0.15s ease;">
                                            <input type="checkbox" name="service_types[]" value="{{ $val }}"
                                                   class="form-check-input me-3 mt-0 service-check"
                                                   style="width: 18px; height: 18px;"
                                                   @checked(in_array($val, old('service_types', [])))>
                                            <span class="fw-semibold text-dark" style="font-size: 13.5px;">{{ $lbl }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div id="otherServiceWrapper" style="display: none;" class="mt-3">
                                <label class="form-label fw-semibold text-dark">Please specify the "Other" service needed <span class="text-danger">*</span></label>
                                <textarea name="other_service" class="form-control" rows="2" maxlength="500" placeholder="Describe the specific inspection, replacement part, or custom repair required...">{{ old('other_service') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Date, Time & Notes -->
                    <div class="adminator-card mb-4">
                        <div class="adminator-card-header">
                            <h2 class="adminator-card-title">
                                <span class="badge bg-primary text-white me-2" style="font-size: 11px; width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%;">3</span>
                                Schedule Window & Notes
                            </h2>
                        </div>
                        <div class="adminator-card-body">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Preferred Appointment Date <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted"><i class="ti ti-calendar"></i></span>
                                        <input type="date" name="requested_date" value="{{ old('requested_date') }}"
                                               class="form-control" min="{{ now()->toDateString() }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Preferred Time Window</label>
                                    <select name="requested_time_slot" class="form-select">
                                        <option value="">Flexible / No preference</option>
                                        <option value="morning" @selected(old('requested_time_slot') === 'morning')>Morning (08:30 AM - 12:30 PM)</option>
                                        <option value="afternoon" @selected(old('requested_time_slot') === 'afternoon')>Afternoon (01:30 PM - 05:30 PM)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-0">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-semibold text-dark mb-0">Symptoms or Special Requests</label>
                                    <span class="text-muted small" style="font-size: 11px;">Optional</span>
                                </div>
                                <textarea name="notes" class="form-control" rows="3" maxlength="1000"
                                          placeholder="E.g., Squeaking noise when braking at low speeds, check engine light on, preparing for long highway trip...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                        <div class="adminator-card-footer p-3 bg-light border-top d-flex align-items-center justify-content-between">
                            <a href="{{ route('portal.appointments.index') }}" class="btn-adminator-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn-adminator-primary">
                                <i class="ti ti-send"></i> Submit Booking Request
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar Details -->
                <div class="col-lg-4">
                    <div class="adminator-card mb-4">
                        <div class="adminator-card-header">
                            <h2 class="adminator-card-title">
                                <i class="ti ti-building text-primary"></i> Service Center
                            </h2>
                        </div>
                        <div class="adminator-card-body">
                            <div class="h5 fw-bold text-dark mb-1">Zoom EV Bole Workshop</div>
                            <div class="text-muted small">Bole Road, Near Airport Roundabout, Addis Ababa</div>
                            <div class="text-muted small mt-2">
                                <i class="ti ti-phone me-1 text-primary"></i> +251 11 123 4567
                            </div>
                            <div class="text-muted small mt-1">
                                <i class="ti ti-clock me-1 text-primary"></i> Mon - Sat: 8:00 AM - 6:00 PM
                            </div>
                        </div>
                    </div>

                    <div class="adminator-card">
                        <div class="adminator-card-header">
                            <h2 class="adminator-card-title">
                                <i class="ti ti-list-check text-primary"></i> What happens next?
                            </h2>
                        </div>
                        <div class="adminator-card-body">
                            <div class="d-flex flex-column gap-3 text-muted small" style="font-size: 12.5px; line-height: 1.5;">
                                <div class="d-flex align-items-start gap-2.5">
                                    <span class="icon-holder c-blue-500 flex-shrink-0" style="width: 26px; height: 26px; font-size: 12px; font-weight: 700;">1</span>
                                    <div>Our workshop Service Advisor reviews your requested slot and parts inventory.</div>
                                </div>
                                <div class="d-flex align-items-start gap-2.5">
                                    <span class="icon-holder c-blue-500 flex-shrink-0" style="width: 26px; height: 26px; font-size: 12px; font-weight: 700;">2</span>
                                    <div>You will receive an automated confirmation notification once reviewed.</div>
                                </div>
                                <div class="d-flex align-items-start gap-2.5">
                                    <span class="icon-holder c-blue-500 flex-shrink-0" style="width: 26px; height: 26px; font-size: 12px; font-weight: 700;">3</span>
                                    <div>Bring your vehicle in; all service records will sync directly into your digital passport.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const otherCb = document.querySelector('input[name="service_types[]"][value="other"]');
        const wrapper = document.getElementById('otherServiceWrapper');

        function toggleOther() {
            if (otherCb && wrapper) {
                wrapper.style.display = otherCb.checked ? 'block' : 'none';
            }
        }

        if (otherCb) {
            otherCb.addEventListener('change', toggleOther);
            toggleOther();
        }
    });
</script>
@endpush
