@extends('layouts.site')

@section('title', 'Book a Service — AutoTrack Ethiopia')
@section('meta_description', 'Book a garage service in Addis Ababa — no account required')

@section('content')
    <div class="theme-page padding-bottom-66">
        <div class="row gray full-width page-header vertical-align-table">
            <div class="row full-width padding-top-bottom-50 vertical-align-cell">
                <div class="row">
                    <div class="page-header-left">
                        <h1>BOOK A SERVICE</h1>
                    </div>
                    <div class="page-header-right">
                        <div class="bread-crumb-container">
                            <label>YOU ARE HERE:</label>
                            <ul class="bread-crumb">
                                <li><a title="HOME" href="{{ url('/') }}">HOME</a></li>
                                <li class="separator">&#47;</li>
                                <li>BOOK A SERVICE</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix">
            <div class="row page-margin-top">
                <div class="column column-1-1">
                    <div class="row">
                        <p class="description align-center">
                            No account needed to request a service — just fill in your details below.
                            Already have login credentials from a past visit? <a href="{{ route('portal.login') }}">Log in here</a> instead to book faster.
                        </p>
                    </div>
                </div>
            </div>

            @if (session('status'))
                <div class="row page-margin-top">
                    <div class="column column-1-1">
                        <div style="
                background: linear-gradient(135deg, #1a5c0a 0%, #2d8a10 100%);
                border-radius: 12px;
                padding: 24px 28px;
                position: relative;
                overflow: hidden;
            ">
                            <div style="position:absolute;top:-20px;right:-20px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,0.06)"></div>
                            <div style="position:absolute;bottom:-30px;right:60px;width:70px;height:70px;border-radius:50%;background:rgba(255,255,255,0.04)"></div>
                            <div style="display:flex;align-items:flex-start;gap:16px;position:relative;z-index:1">
                                <div style="width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:22px;">
                                    ✅
                                </div>
                                <div>
                                    <div style="color:#fff;font-weight:700;font-size:17px;margin-bottom:4px;">Appointment request sent!</div>
                                    <div style="color:rgba(255,255,255,0.85);font-size:14px;line-height:1.6;">{{ session('status') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($errors->any())
                <div class="row page-margin-top">
                    <div class="column column-1-1">
                        <div class="cost-calculator-box clearfix" style="border-left: 3px solid #B4432B;">
                            <p style="margin: 0; color: #B4432B; font-weight: bold;">Please fix the following:</p>
                            <ul style="margin: 8px 0 0 16px; color: #B4432B;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row page-margin-top">
                <form class="contact-form" id="booking-form" method="POST" action="{{ route('booking.store') }}">
                    @csrf
                    <div class="row">
                        <fieldset class="column column-1-3">
                            <div class="cost-calculator-box clearfix">
                                <label>PLATE NUMBER</label>
                                <input class="cost-slider-input big" name="plate_number" type="text" value="{{ old('plate_number') }}" placeholder="Plate Number *">
                                @error('plate_number') <p class="template-bullet" style="color:#B4432B;">{{ $message }}</p> @enderror
                            </div>
                        </fieldset>
                        <fieldset class="column column-1-3">
                            <div class="cost-calculator-box clearfix">
                                <label>VEHICLE MAKE</label>
                                <input class="cost-slider-input big" name="make" type="text" value="{{ old('make') }}" placeholder="Make * — e.g. Toyota">
                                @error('make') <p class="template-bullet" style="color:#B4432B;">{{ $message }}</p> @enderror
                            </div>
                        </fieldset>
                        <fieldset class="column column-1-3">
                            <div class="cost-calculator-box clearfix">
                                <label>VEHICLE MODEL</label>
                                <input class="cost-slider-input big" name="model" type="text" value="{{ old('model') }}" placeholder="Model * — e.g. Corolla">
                                @error('model') <p class="template-bullet" style="color:#B4432B;">{{ $message }}</p> @enderror
                            </div>
                        </fieldset>
                    </div>

                    <div class="row page-margin-top">
                        <fieldset class="column column-1-3">
                            <div class="cost-calculator-box clearfix">
                                <label>VEHICLE YEAR (OPTIONAL)</label>
                                <input class="cost-slider-input big" name="year" type="number" min="1980" max="{{ date('Y') + 1 }}" value="{{ old('year') }}" placeholder="e.g. 2018">
                            </div>
                        </fieldset>
                        <fieldset class="column column-1-3">
                            <div class="cost-calculator-box clearfix">
                                <label>FUEL TYPE (OPTIONAL)</label>
                                <select name="fuel_type" class="cost-dropdown">
                                    <option value="" selected>Choose...</option>
                                    <option value="petrol" @selected(old('fuel_type') === 'petrol')>Petrol</option>
                                    <option value="diesel" @selected(old('fuel_type') === 'diesel')>Diesel</option>
                                    <option value="hybrid" @selected(old('fuel_type') === 'hybrid')>Hybrid</option>
                                    <option value="electric" @selected(old('fuel_type') === 'electric')>Electric (EV)</option>
                                </select>
                            </div>
                        </fieldset>
                    </div>

                    <div class="row page-margin-top">
                        <fieldset class="column column-1-2">
                            <div class="cost-calculator-box clearfix">
                                <label>PREFERRED DATE OF APPOINTMENT</label>
                                <input class="cost-slider-input big" name="requested_date" type="date" min="{{ date('Y-m-d') }}" value="{{ old('requested_date') }}">
                                @error('requested_date') <p class="template-bullet" style="color:#B4432B;">{{ $message }}</p> @enderror
                            </div>
                            <div class="cost-calculator-box page-margin-top clearfix">
                                <label>PREFERRED TIME FRAME</label>
                                <select name="requested_time_slot" class="cost-dropdown">
                                    <option value="" selected>No preference</option>
                                    <option value="morning" @selected(old('requested_time_slot') === 'morning')>Morning</option>
                                    <option value="afternoon" @selected(old('requested_time_slot') === 'afternoon')>Afternoon</option>
                                </select>
                            </div>
                            <div class="cost-calculator-box page-margin-top clearfix">
                                <label>SELECT SERVICES NEEDED</label>
                                <ul class="checkboxes-list margin-top-20">
                                    @foreach ($serviceTypes as $key => $label)
                                        <li>
                                            <input type="checkbox" name="service_types[]" value="{{ $key }}"
                                                   id="service-{{ $key }}"
                                                @checked(collect(old('service_types', []))->contains($key))>
                                            <label for="service-{{ $key }}" class="checkbox-label template-bullet"><span class="checkbox-box"></span>{{ $label }}</label>
                                        </li>
                                    @endforeach
                                </ul>
                                @error('service_types') <p class="template-bullet" style="color:#B4432B;">{{ $message }}</p> @enderror
                                <input class="cost-slider-input big margin-top-20" name="other_service" type="text" value="{{ old('other_service') }}" placeholder="If 'Other', describe what you need">
                                @error('other_service') <p class="template-bullet" style="color:#B4432B;">{{ $message }}</p> @enderror
                            </div>
                        </fieldset>

                        <fieldset class="column column-1-2">
                            <label>CONTACT DETAILS</label>
                            <input class="text-input" name="full_name" type="text" value="{{ old('full_name') }}" placeholder="Your Name *">
                            @error('full_name') <p class="template-bullet" style="color:#B4432B;">{{ $message }}</p> @enderror
                            <input class="text-input" name="phone" type="text" value="{{ old('phone') }}" placeholder="Your Phone *  —  09XXXXXXXX">
                            @error('phone') <p class="template-bullet" style="color:#B4432B;">{{ $message }}</p> @enderror
                            <input class="text-input" name="email" type="text" value="{{ old('email') }}" placeholder="Your Email (optional)">
                            @error('email') <p class="template-bullet" style="color:#B4432B;">{{ $message }}</p> @enderror
                            <textarea class="margin-top-20" name="notes" placeholder="Additional Questions or Comments">{{ old('notes') }}</textarea>

                            <div class="cost-calculator-box page-margin-top clearfix">
                                <label>WANT AN ACCOUNT? (OPTIONAL)</label>
                                <p class="description margin-top-0" style="text-align: left;">
                                    You don't need one to book. With an account you can see your service history and track this appointment.
                                    Skip it, and we'll still email you login details automatically once the service is complete.
                                </p>
                                <ul class="checkboxes-list margin-top-20">
                                    <li>
                                        <input type="checkbox" name="want_account" value="1" class="cost-slider-input type-checkbox" id="want_account" @checked(old('want_account'))>
                                        <label for="want_account" class="checkbox-label template-bullet"><span class="checkbox-box"></span>Yes, set up my account now</label>
                                    </li>
                                </ul>
                                <div id="password_fields" style="display: {{ old('want_account') ? 'block' : 'none' }};">
                                    <input class="cost-slider-input big margin-top-20" name="password" type="password" placeholder="Password">
                                    @error('password') <p class="template-bullet" style="color:#B4432B;">{{ $message }}</p> @enderror
                                    <input class="cost-slider-input big" name="password_confirmation" type="password" placeholder="Confirm Password">
                                </div>
                            </div>

                            <button type="submit" class="more margin-top-20 display-block" style="border: none; cursor: pointer; width: 100%;">
                                <span>REQUEST APPOINTMENT</span>
                            </button>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Force normal POST — prevents theme's AJAX handler from hijacking this form
            document.getElementById('booking-form').addEventListener('submit', function (e) {
                e.stopImmediatePropagation();
            }, true);
            document.addEventListener('DOMContentLoaded', function () {
                var checkbox = document.getElementById('want_account');
                var fields = document.getElementById('password_fields');
                checkbox.addEventListener('change', function () {
                    fields.style.display = checkbox.checked ? 'block' : 'none';
                });
            });
        </script>

    @endpush
@endsection
