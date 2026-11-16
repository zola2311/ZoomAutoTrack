@extends('layouts.site')

@section('title', 'Get a Repair & Price Quotation — Zoom EV')
@section('meta_description', 'Request a professional diagnostic assessment and transparent cost estimate for vehicle repairs, defects, or bodywork at Zoom EV.')

@push('styles')
<style>
    /* Website Theme Colors: #1E69B8 (Brand Blue), #EBF3FA (Light Blue BG), #C6DCF1 (Light Blue Border) */
    .quote-card-container {
        background: #ffffff;
        border: 1px solid #C6DCF1;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(30, 105, 184, 0.06);
        padding: 35px 30px;
        margin-bottom: 40px;
        box-sizing: border-box;
    }
    .quote-section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #EBF3FA;
    }
    .quote-section-badge {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #1E69B8;
        color: #ffffff;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }
    .quote-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #1E3A5F;
        margin-bottom: 6px;
        letter-spacing: 0.01em;
    }
    .quote-label span.req {
        color: #D9381E;
        margin-left: 2px;
    }
    .quote-help-text {
        font-size: 12px;
        color: #64748b;
        margin: 2px 0 0 0;
    }
    /* Unified Form Controls for Consistent Dimensions & Styling */
    .quote-form-control {
        width: 100% !important;
        height: 48px !important;
        box-sizing: border-box !important;
        padding: 10px 14px !important;
        border: 1px solid #C6DCF1 !important;
        border-radius: 6px !important;
        font-size: 14px !important;
        font-family: 'Open Sans', Arial, sans-serif !important;
        color: #25282A !important;
        background-color: #FFFFFF !important;
        line-height: 24px !important;
        transition: all 0.2s ease !important;
        margin: 0 !important;
        outline: none !important;
    }
    .quote-form-control::placeholder {
        color: #94A3B8 !important;
        opacity: 1 !important;
    }
    .quote-form-control:focus {
        border-color: #1E69B8 !important;
        box-shadow: 0 0 0 3px rgba(30, 105, 184, 0.18) !important;
    }
    select.quote-form-control {
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231E69B8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e") !important;
        background-repeat: no-repeat !important;
        background-position: right 14px center !important;
        background-size: 16px 16px !important;
        padding-right: 38px !important;
        cursor: pointer !important;
    }
    textarea.quote-form-control-area {
        width: 100% !important;
        min-height: 120px !important;
        box-sizing: border-box !important;
        padding: 12px 14px !important;
        border: 1px solid #C6DCF1 !important;
        border-radius: 6px !important;
        font-size: 14px !important;
        font-family: 'Open Sans', Arial, sans-serif !important;
        color: #25282A !important;
        background-color: #FFFFFF !important;
        line-height: 1.5 !important;
        transition: all 0.2s ease !important;
        margin: 0 !important;
        outline: none !important;
        resize: vertical;
    }
    textarea.quote-form-control-area:focus {
        border-color: #1E69B8 !important;
        box-shadow: 0 0 0 3px rgba(30, 105, 184, 0.18) !important;
    }
    /* Radio Category Cards */
    .category-radio-card {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border: 1px solid #D4E5F5;
        background: #FFFFFF;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }
    .category-radio-card:hover {
        border-color: #1E69B8;
        background: #F4F8FC;
    }
    .category-radio-card.active {
        border-color: #1E69B8;
        background: #EBF3FA;
        box-shadow: 0 2px 8px rgba(30, 105, 184, 0.12);
    }
    .category-radio-card input[type="radio"] {
        margin-top: 3px;
        accent-color: #1E69B8;
        width: 16px;
        height: 16px;
        cursor: pointer;
    }
    /* Symptoms Checkbox Cards */
    .symptom-checkbox-card {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13.5px;
        color: #334155;
        transition: all 0.15s ease;
        box-sizing: border-box;
    }
    .symptom-checkbox-card:hover {
        border-color: #1E69B8;
        background: #F4F8FC;
    }
    .symptom-checkbox-card.active {
        border-color: #1E69B8;
        background: #EBF3FA;
        color: #1E3A5F;
        font-weight: 600;
    }
    .symptom-checkbox-card input[type="checkbox"] {
        accent-color: #1E69B8;
        width: 16px;
        height: 16px;
        cursor: pointer;
    }
    /* Status Radio Options */
    .condition-radio-option {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border: 1px solid #C6DCF1;
        background: #FFFFFF;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }
    .condition-radio-option:hover {
        background: #F4F8FC;
        border-color: #1E69B8;
    }
    .condition-radio-option.active {
        border-color: #1E69B8;
        background: #EBF3FA;
    }
    .condition-radio-option input[type="radio"] {
        accent-color: #1E69B8;
        width: 16px;
        height: 16px;
    }
    /* Upload Dropzone */
    .quote-upload-dropzone {
        background: #F8FAFC;
        border: 2px dashed #94BCE0;
        border-radius: 10px;
        padding: 28px 20px;
        text-align: center;
        position: relative;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .quote-upload-dropzone:hover {
        border-color: #1E69B8;
        background: #F0F7FD;
    }
    /* Submit Button */
    .quote-submit-btn {
        background: #1E69B8;
        color: #ffffff !important;
        border: none;
        padding: 14px 34px;
        font-size: 15px;
        font-weight: 700;
        font-family: 'Open Sans', Arial, sans-serif;
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 14px rgba(30, 105, 184, 0.28);
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .quote-submit-btn:hover {
        background: #155294;
        box-shadow: 0 6px 18px rgba(30, 105, 184, 0.35);
        transform: translateY(-1px);
    }
    /* Voice Note Component */
    .quote-voice-card {
        background: #F8FAFC;
        border: 1px solid #C6DCF1;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 22px;
        transition: all 0.2s ease;
    }
    .quote-voice-card:hover {
        border-color: #1E69B8;
        background: #F4F8FD;
    }
    .voice-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        border-radius: 25px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        border: 1.5px solid #1E69B8;
        background: #EBF3FA;
        color: #1E69B8;
        transition: all 0.2s ease;
    }
    .voice-action-btn:hover {
        background: #1E69B8;
        color: #FFFFFF;
    }
    .voice-action-btn.recording {
        background: #FEE2E2 !important;
        color: #DC2626 !important;
        border-color: #DC2626 !important;
        animation: recPulse 1.4s infinite;
    }
    @keyframes recPulse {
        0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4); }
        70% { box-shadow: 0 0 0 8px rgba(220, 38, 38, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
    }
    /* Photo Preview Item */
    .photo-preview-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #C6DCF1;
        height: 105px;
        background: #1e293b;
        box-shadow: 0 2px 6px rgba(30,105,184,0.12);
    }
    .photo-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .photo-remove-btn {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: rgba(220, 38, 38, 0.9);
        color: #ffffff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        cursor: pointer;
        line-height: 1;
        transition: background 0.15s;
    }
    .photo-remove-btn:hover {
        background: #b91c1c;
    }
</style>
@endpush

@section('content')
<div class="theme-page padding-bottom-66">
    <div class="row gray full-width page-header vertical-align-table">
        <div class="row full-width padding-top-bottom-50 vertical-align-cell">
            <div class="row">
                <div class="page-header-left">
                    <h1>GET A PRICE QUOTATION</h1>
                </div>
                <div class="page-header-right">
                    <div class="bread-crumb-container">
                        <label>YOU ARE HERE:</label>
                        <ul class="bread-crumb">
                            <li><a title="HOME" href="{{ url('/') }}">HOME</a></li>
                            <li class="separator">&#47;</li>
                            <li>PRICE QUOTATION & DEFECT ASSESSMENT</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix">
        <div class="row page-margin-top">
            <div class="column column-1-1">
                <div class="align-center" style="max-width: 860px; margin: 0 auto 35px auto;">
                    <h2 class="box-header" style="font-size: 26px; font-weight: 700; color: #1E3A5F; margin-bottom: 10px;">
                        Automotive Repair Assessment & Cost Estimation
                    </h2>
                    <p class="description" style="font-size: 15px; color: #556B82; line-height: 1.6;">
                        Have a vehicle defect, noise, warning light, or bodywork damage? Detail your problem below and attach pictures of the affected parts. 
                        Our engineering team will assess your issue and email you a transparent quotation report with itemized labor and parts prices.
                    </p>
                    
                    @if (auth('customer')->check())
                        <div style="background: #EBF3FA; border: 1px solid #C6DCF1; border-radius: 8px; padding: 10px 18px; display: inline-flex; align-items: center; gap: 8px; font-size: 13.5px; color: #1E69B8; margin-top: 8px;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Signed in as <strong>{{ auth('customer')->user()->full_name }}</strong>. You can quick-select from your registered vehicles below.
                        </div>
                    @else
                        <div style="font-size: 13.5px; color: #64748b; margin-top: 6px;">
                            Looking for standard periodic maintenance? Visit our <a href="{{ route('booking.create') }}" style="color: #1E69B8; font-weight: 700; text-decoration: underline;">Book a Service</a> page. 
                            Have an account? <a href="{{ route('portal.login') }}" style="color: #1E69B8; font-weight: 700; text-decoration: underline;">Sign in</a> to auto-fill your vehicles.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="row">
                <div class="column column-1-1">
                    <div style="background: #FFF5F5; border: 1px solid #FED7D7; border-radius: 8px; padding: 16px 20px; margin-bottom: 25px;">
                        <div style="font-weight: 700; color: #C53030; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Please correct the following fields before submitting:
                        </div>
                        <ul style="color: #E53E3E; font-size: 13.5px; margin-left: 20px; line-height: 1.5;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="column column-1-1">
                <form action="{{ route('quotation.store') }}" method="POST" enctype="multipart/form-data" id="quotationForm" class="quote-card-container">
                    @csrf

                    <!-- ========================================== -->
                    <!-- SECTION 1: CUSTOMER CONTACT DETAILS        -->
                    <!-- ========================================== -->
                    <div style="margin-bottom: 35px;">
                        <div class="quote-section-title">
                            <div class="quote-section-badge">1</div>
                            <div>
                                <h3 style="font-size: 18px; font-weight: 700; color: #1E3A5F; margin: 0;">Customer Contact Information</h3>
                                <p class="quote-help-text">Where our service advisors will send your official quotation report and estimate.</p>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 18px;">
                            <div>
                                <label class="quote-label">Full Name <span class="req">*</span></label>
                                <input type="text" name="customer_name" class="quote-form-control" value="{{ old('customer_name', $customer?->full_name) }}" required placeholder="e.g. Abebe Kebede">
                            </div>

                            <div>
                                <label class="quote-label">Phone Number <span class="req">*</span></label>
                                <input type="tel" name="phone" class="quote-form-control" value="{{ old('phone', $customer?->phone) }}" required placeholder="e.g. 0911234567 or +251 9...">
                            </div>

                            <div>
                                <label class="quote-label">Email Address (For Report Delivery) <span class="req">*</span></label>
                                <input type="email" name="email" class="quote-form-control" value="{{ old('email', $customer?->email) }}" required placeholder="your.email@example.com">
                            </div>

                            <div>
                                <label class="quote-label">City / Location</label>
                                <input type="text" name="city" class="quote-form-control" value="{{ old('city', $customer?->address ?? 'Addis Ababa') }}" placeholder="e.g. Addis Ababa (Bole, Kazanchis, etc.)">
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- SECTION 2: VEHICLE SPECIFICATIONS          -->
                    <!-- ========================================== -->
                    <div style="margin-bottom: 35px;">
                        <div class="quote-section-title">
                            <div class="quote-section-badge">2</div>
                            <div>
                                <h3 style="font-size: 18px; font-weight: 700; color: #1E3A5F; margin: 0;">Vehicle Identification & Specifications</h3>
                                <p class="quote-help-text">Accurate vehicle drivetrain specifications ensure precise parts catalog pricing and labor hours.</p>
                            </div>
                        </div>

                        @if ($customerVehicles->isNotEmpty())
                            <div style="background: #EBF3FA; border: 1px dashed #C6DCF1; border-radius: 8px; padding: 14px 18px; margin-bottom: 20px;">
                                <label style="display: block; font-size: 13px; font-weight: 700; color: #1E69B8; margin-bottom: 6px;">
                                    ⚡ Quick Select from Your Saved Vehicles:
                                </label>
                                <select id="saved_vehicle_picker" class="quote-form-control" style="background-color: #fff !important;">
                                    <option value="">-- Choose one of your registered cars, or enter below --</option>
                                    @foreach ($customerVehicles as $v)
                                        <option value="{{ $v->id }}"
                                            data-make="{{ $v->make }}"
                                            data-model="{{ $v->model }}"
                                            data-year="{{ $v->year }}"
                                            data-plate="{{ $v->plate_number }}"
                                            data-transmission="{{ $v->transmission }}"
                                            data-fuel="{{ $v->fuel_type }}"
                                            data-vin="{{ $v->chassis_number }}"
                                            data-mileage="{{ $v->current_mileage }}">
                                            {{ $v->year }} {{ $v->make }} {{ $v->model }} (Plate: {{ $v->plate_number ?: 'Not recorded' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <input type="hidden" name="vehicle_id" id="selected_vehicle_id" value="{{ old('vehicle_id') }}">

                        <!-- Row 1: Make, Model, Year, Plate (All matching height & styling) -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 16px;">
                            <div>
                                <label class="quote-label">Make / Brand <span class="req">*</span></label>
                                <input type="text" name="make" id="vehicle_make" class="quote-form-control" value="{{ old('make') }}" required placeholder="e.g. Toyota, Hyundai, Suzuki">
                            </div>

                            <div>
                                <label class="quote-label">Model <span class="req">*</span></label>
                                <input type="text" name="model" id="vehicle_model" class="quote-form-control" value="{{ old('model') }}" required placeholder="e.g. Corolla, RAV4, Tucson">
                            </div>

                            <div>
                                <label class="quote-label">Model Year</label>
                                <input type="number" name="year" id="vehicle_year" class="quote-form-control" value="{{ old('year') }}" min="1960" max="{{ date('Y') + 1 }}" placeholder="e.g. 2021">
                            </div>

                            <div>
                                <label class="quote-label">License Plate Number</label>
                                <input type="text" name="plate_number" id="vehicle_plate" class="quote-form-control" value="{{ old('plate_number') }}" placeholder="e.g. ET 3-12345 or AA 2...">
                            </div>
                        </div>

                        <!-- Row 2: Mileage, Transmission, Fuel, VIN (Unified height, font, and border) -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 16px; margin-top: 16px;">
                            <div>
                                <label class="quote-label">Current Mileage (km)</label>
                                <input type="number" name="mileage" id="vehicle_mileage" class="quote-form-control" value="{{ old('mileage') }}" placeholder="e.g. 75,000 km">
                            </div>

                            <div>
                                <label class="quote-label">Transmission Type</label>
                                <select name="transmission" id="vehicle_transmission" class="quote-form-control">
                                    <option value="" {{ old('transmission') == '' ? 'selected' : '' }}>-- Select Transmission --</option>
                                    <option value="Automatic" {{ old('transmission') == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                                    <option value="Manual" {{ old('transmission') == 'Manual' ? 'selected' : '' }}>Manual</option>
                                    <option value="CVT" {{ old('transmission') == 'CVT' ? 'selected' : '' }}>CVT</option>
                                    <option value="Other" {{ old('transmission') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div>
                                <label class="quote-label">Fuel / Power Type</label>
                                <select name="fuel_type" id="vehicle_fuel" class="quote-form-control">
                                    <option value="" {{ old('fuel_type') == '' ? 'selected' : '' }}>-- Select Fuel Type --</option>
                                    <option value="Petrol" {{ old('fuel_type') == 'Petrol' ? 'selected' : '' }}>Petrol / Benzine</option>
                                    <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="Hybrid" {{ old('fuel_type') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    <option value="Electric" {{ old('fuel_type') == 'Electric' ? 'selected' : '' }}>Full Electric (EV)</option>
                                </select>
                            </div>

                            <div>
                                <label class="quote-label">Chassis / VIN (Optional)</label>
                                <input type="text" name="vin" id="vehicle_vin" class="quote-form-control" value="{{ old('vin') }}" placeholder="17-digit Chassis #">
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- SECTION 3: DEFECT DIAGNOSTIC QUESTIONNAIRE -->
                    <!-- ========================================== -->
                    <div style="margin-bottom: 35px;">
                        <div class="quote-section-title">
                            <div class="quote-section-badge">3</div>
                            <div>
                                <h3 style="font-size: 18px; font-weight: 700; color: #1E3A5F; margin: 0;">Diagnostic & Defect Questionnaire</h3>
                                <p class="quote-help-text">Select the category of the defect and any symptoms you are currently experiencing.</p>
                            </div>
                        </div>

                        <!-- A. Repair Category -->
                        <div style="margin-bottom: 22px;">
                            <label class="quote-label" style="font-size: 14px; margin-bottom: 8px;">
                                A. Primary Defect or Repair Category <span class="req">*</span>
                            </label>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 10px;">
                                @foreach ($serviceCategories as $catName => $catDesc)
                                    <label class="category-radio-card {{ old('service_category', 'Engine & Diagnostics') == $catName ? 'active' : '' }}" onclick="selectCategoryCard(this)">
                                        <input type="radio" name="service_category" value="{{ $catName }}" {{ old('service_category', 'Engine & Diagnostics') == $catName ? 'checked' : '' }} required onchange="handleCategoryChange(this.value)">
                                        <div>
                                            <div style="font-weight: 700; font-size: 13.5px; color: #1E3A5F;">{{ $catName }}</div>
                                            <div style="font-size: 12px; color: #64748B; margin-top: 3px; line-height: 1.4;">{{ $catDesc }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Dynamic Input if "Other / Problem Not Listed" is selected -->
                            <div id="otherCategoryContainer" style="display: {{ old('service_category') == 'Other / Problem Not Listed' ? 'block' : 'none' }}; margin-top: 14px; background: #EBF3FA; border: 1px solid #C6DCF1; border-radius: 8px; padding: 14px 18px;">
                                <label class="quote-label" style="color: #1E69B8;">Please specify your defect or problem not listed above:</label>
                                <input type="text" name="other_category_text" id="other_category_text" class="quote-form-control" value="{{ old('other_category_text') }}" placeholder="e.g. Sunroof leaking water, power window stuck down, custom exhaust modification, etc.">
                            </div>
                        </div>

                        <!-- B. Symptoms Checklist -->
                        <div style="margin-bottom: 22px;">
                            <label class="quote-label" style="font-size: 14px; margin-bottom: 8px;">
                                B. Symptoms Observed (Check all that apply)
                            </label>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 8px;">
                                @foreach ($commonSymptoms as $symptom)
                                    @php
                                        $isChecked = in_array($symptom, old('symptoms', []));
                                        $isOtherSymptom = ($symptom === 'Other symptom not listed above');
                                    @endphp
                                    <label class="symptom-checkbox-card {{ $isChecked ? 'active' : '' }}" onclick="toggleSymptomCard(this)">
                                        <input type="checkbox" name="symptoms[]" value="{{ $symptom }}" {{ $isChecked ? 'checked' : '' }} onchange="handleSymptomChange(this)">
                                        <span>{{ $symptom }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Dynamic Input if "Other symptom not listed above" is checked -->
                            <div id="otherSymptomContainer" style="display: {{ in_array('Other symptom not listed above', old('symptoms', [])) ? 'block' : 'none' }}; margin-top: 12px; background: #EBF3FA; border: 1px solid #C6DCF1; border-radius: 8px; padding: 12px 16px;">
                                <label class="quote-label" style="color: #1E69B8;">Please specify the symptom you are experiencing:</label>
                                <input type="text" name="other_symptom_text" id="other_symptom_text" class="quote-form-control" value="{{ old('other_symptom_text') }}" placeholder="e.g. Smell of burnt rubber when reversing, headlights flickering on bumps, etc.">
                            </div>
                        </div>

                        <!-- C. Drivability & Urgency (Light Blue Accent Cards) -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 22px;">
                            <div>
                                <label class="quote-label" style="font-size: 13.5px; margin-bottom: 8px;">
                                    C. Can the car be safely driven right now? <span class="req">*</span>
                                </label>
                                <div style="display: flex; flex-direction: column; gap: 8px;">
                                    <label class="condition-radio-option {{ old('drivable_status', 'drivable') == 'drivable' ? 'active' : '' }}" onclick="selectConditionCard(this)">
                                        <input type="radio" name="drivable_status" value="drivable" {{ old('drivable_status', 'drivable') == 'drivable' ? 'checked' : '' }} required>
                                        <span style="font-size: 13.5px; color: #15803d; font-weight: 700;">✅ Yes, vehicle is fully drivable</span>
                                    </label>
                                    <label class="condition-radio-option {{ old('drivable_status') == 'drivable_with_care' ? 'active' : '' }}" onclick="selectConditionCard(this)">
                                        <input type="radio" name="drivable_status" value="drivable_with_care" {{ old('drivable_status') == 'drivable_with_care' ? 'checked' : '' }}>
                                        <span style="font-size: 13.5px; color: #b45309; font-weight: 700;">⚠️ Drives with caution / reduced power</span>
                                    </label>
                                    <label class="condition-radio-option {{ old('drivable_status') == 'not_drivable' ? 'active' : '' }}" onclick="selectConditionCard(this)">
                                        <input type="radio" name="drivable_status" value="not_drivable" {{ old('drivable_status') == 'not_drivable' ? 'checked' : '' }}>
                                        <span style="font-size: 13.5px; color: #b91c1c; font-weight: 700;">⛔ No, not drivable (Needs Towing)</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="quote-label" style="font-size: 13.5px; margin-bottom: 8px;">
                                    D. Urgency & Preferred Timeline <span class="req">*</span>
                                </label>
                                <div style="display: flex; flex-direction: column; gap: 8px;">
                                    <label class="condition-radio-option {{ old('urgency') == 'urgent_asap' ? 'active' : '' }}" onclick="selectConditionCard(this)">
                                        <input type="radio" name="urgency" value="urgent_asap" {{ old('urgency') == 'urgent_asap' ? 'checked' : '' }} required>
                                        <span style="font-size: 13.5px; color: #dc2626; font-weight: 700;">🔥 Urgent — Needs inspection & repair ASAP</span>
                                    </label>
                                    <label class="condition-radio-option {{ old('urgency') == 'within_week' ? 'active' : '' }}" onclick="selectConditionCard(this)">
                                        <input type="radio" name="urgency" value="within_week" {{ old('urgency') == 'within_week' ? 'checked' : '' }}>
                                        <span style="font-size: 13.5px; color: #1E69B8; font-weight: 700;">📅 Within this week</span>
                                    </label>
                                    <label class="condition-radio-option {{ old('urgency', 'flexible') == 'flexible' ? 'active' : '' }}" onclick="selectConditionCard(this)">
                                        <input type="radio" name="urgency" value="flexible" {{ old('urgency', 'flexible') == 'flexible' ? 'checked' : '' }}>
                                        <span style="font-size: 13.5px; color: #475569; font-weight: 700;">⏱️ Flexible timeline / Inquiring price</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- D. Voice Note Recording (Optional) -->
                        <div class="quote-voice-card">
                            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; margin-bottom: 6px;">
                                <label class="quote-label" style="font-size: 14px; margin: 0; color: #1E3A5F; display: flex; align-items: center; gap: 6px;">
                                    <span>🎙️</span> D. Record or Attach a Voice Note (Optional)
                                </label>
                                <span style="font-size: 11.5px; font-weight: 600; color: #1E69B8; background: #EBF3FA; padding: 2px 8px; border-radius: 4px; border: 1px solid #C6DCF1;">
                                    Voice Diagnostics
                                </span>
                            </div>
                            <p class="quote-help-text" style="margin-bottom: 12px;">
                                Hearing a strange squeak, engine knock, or rattling sound? Record a voice message or upload an audio clip explaining the sound. Our certified mechanics will listen to it directly.
                            </p>

                            <!-- Voice Recording Controls -->
                            <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 12px;">
                                <button type="button" id="recordVoiceBtn" class="voice-action-btn">
                                    <span id="recIcon">🎙️</span>
                                    <span id="recLabel">Start Voice Recording</span>
                                </button>

                                <div id="recTimer" style="display: none; align-items: center; gap: 6px; font-weight: 700; color: #DC2626; font-size: 13px;">
                                    <span style="display: inline-block; width: 10px; height: 10px; background: #DC2626; border-radius: 50%; animation: recPulse 1s infinite;"></span>
                                    <span id="recTimeText">00:00</span>
                                    <span style="font-size: 11px; color: #64748B; font-weight: normal;">(Max 3 min)</span>
                                </div>

                                <div style="font-size: 12px; color: #64748B;">
                                    — OR —
                                </div>

                                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; color: #1E69B8; cursor: pointer; text-decoration: underline; margin: 0;">
                                    <span>📁 Upload Audio File</span>
                                    <input type="file" id="audioFileUpload" accept="audio/*" style="display: none;">
                                </label>
                            </div>

                            <!-- Hidden file input holding the final audio file submitted with form -->
                            <input type="file" name="voice_note" id="voiceNoteFinalInput" style="display: none;" accept="audio/*">

                            <!-- Live Audio Playback Box -->
                            <div id="audioPlaybackBox" style="display: none; margin-top: 14px; background: #FFFFFF; border: 1px solid #C6DCF1; border-radius: 8px; padding: 12px 16px; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 10px; flex: 1; min-width: 240px;">
                                    <span style="font-size: 20px;">🔊</span>
                                    <audio id="audioPreview" controls preload="metadata" style="flex: 1; height: 38px; outline: none;"></audio>
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span id="audioFileName" style="font-size: 11.5px; color: #64748B; font-weight: 500;">voice_note.webm</span>
                                    <button type="button" id="removeAudioBtn" style="background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; border-radius: 6px; padding: 6px 12px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                        ✕ Remove
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- E. Detailed Problem Description (Optional) -->
                        <div>
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                                <label class="quote-label" style="font-size: 14px; margin: 0;">
                                    E. Detailed Description of the Defect or Requested Work (Optional)
                                </label>
                                <span style="font-size: 11.5px; color: #64748B; font-style: italic;">Optional</span>
                            </div>
                            <p class="quote-help-text" style="margin-bottom: 8px;">
                                Optional if you have recorded a voice note above or selected symptoms. Mention when the issue occurs (e.g. while cold starting, turning left, applying brakes) or any specific parts you need quoted.
                            </p>
                            <textarea name="description" rows="4" class="quote-form-control-area" placeholder="Describe the defect, symptoms, or requested repairs in your own words (Optional)...">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- SECTION 4: DEFECT & DAMAGE PHOTOS UPLOAD   -->
                    <!-- ========================================== -->
                    <div style="margin-bottom: 35px;">
                        <div class="quote-section-title">
                            <div class="quote-section-badge">4</div>
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                                    <h3 style="font-size: 18px; font-weight: 700; color: #1E3A5F; margin: 0;">Upload Pictures of Defect / Vehicle</h3>
                                    <span style="font-size: 11.5px; font-weight: 600; color: #1E69B8; background: #EBF3FA; padding: 3px 10px; border-radius: 12px; border: 1px solid #C6DCF1;">
                                        Accepts Multiple Images
                                    </span>
                                </div>
                                <p class="quote-help-text">Visual photos of dents, scratches, dashboard warning lights, or mechanical leaks help us quote accurately.</p>
                            </div>
                        </div>

                        <div class="quote-upload-dropzone" id="uploadDropzone">
                            <input type="file" name="photos[]" id="photosInput" multiple accept="image/jpeg,image/png,image/jpg,image/webp,image/heic" style="position: absolute; top:0; left:0; width:100%; height:100%; opacity:0; cursor:pointer; z-index: 5;">
                            
                            <div style="width: 54px; height: 54px; margin: 0 auto 12px auto; background: #EBF3FA; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #1E69B8;">
                                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>

                            <div style="font-size: 15px; font-weight: 700; color: #1E3A5F; margin-bottom: 4px;">
                                Click to select multiple photos or drag & drop images here
                            </div>
                            <div style="font-size: 12.5px; color: #64748B; margin-bottom: 12px;">
                                Supported formats: JPG, PNG, WEBP, HEIC &bull; Select as many photos as needed &bull; Max 15MB per file
                            </div>
                            <div style="display: inline-block; font-size: 12px; font-weight: 600; color: #1E69B8; background: #EBF3FA; border-radius: 6px; padding: 5px 14px; border: 1px solid #C6DCF1;">
                                💡 Tip: You can select multiple images at once (dents, scratches, dashboard icons, undercarriage)
                            </div>
                        </div>

                        <!-- Photos Summary & Live Preview Grid -->
                        <div id="photosSummaryBar" style="display: none; align-items: center; justify-content: space-between; margin-top: 14px; padding: 8px 12px; background: #EBF3FA; border-radius: 6px; font-size: 12.5px; color: #1E3A5F; font-weight: 600;">
                            <span id="photosCountText">0 photos selected</span>
                            <button type="button" id="clearAllPhotosBtn" style="background: none; border: none; color: #DC2626; font-size: 12px; cursor: pointer; text-decoration: underline;">
                                Clear All Photos
                            </button>
                        </div>
                        <div id="photosPreviewContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(115px, 1fr)); gap: 12px; margin-top: 12px;"></div>
                    </div>

                    <!-- ========================================== -->
                    <!-- SUBMIT SECTION                             -->
                    <!-- ========================================== -->
                    <div style="border-top: 2px solid #EBF3FA; padding-top: 25px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 15px;">
                        <div style="font-size: 12.5px; color: #64748B; max-width: 480px; line-height: 1.5;">
                            Upon submission, your inquiry will be reviewed by the diagnostic engineering team at Zoom EV Bole. You will receive an official quotation report with transparent parts and labor pricing via email.
                        </div>

                        <button type="submit" id="submitBtn" class="quote-submit-btn">
                            <span>Submit Quotation Request</span>
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Saved Vehicle Selector Auto-populate
    const vehiclePicker = document.getElementById('saved_vehicle_picker');
    if (vehiclePicker) {
        vehiclePicker.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (!opt.value) {
                document.getElementById('selected_vehicle_id').value = '';
                return;
            }
            document.getElementById('selected_vehicle_id').value = opt.value;
            if (opt.dataset.make) document.getElementById('vehicle_make').value = opt.dataset.make;
            if (opt.dataset.model) document.getElementById('vehicle_model').value = opt.dataset.model;
            if (opt.dataset.year) document.getElementById('vehicle_year').value = opt.dataset.year;
            if (opt.dataset.plate) document.getElementById('vehicle_plate').value = opt.dataset.plate;
            if (opt.dataset.mileage) document.getElementById('vehicle_mileage').value = opt.dataset.mileage;
            if (opt.dataset.transmission) document.getElementById('vehicle_transmission').value = opt.dataset.transmission;
            if (opt.dataset.fuel) document.getElementById('vehicle_fuel').value = opt.dataset.fuel;
            if (opt.dataset.vin) document.getElementById('vehicle_vin').value = opt.dataset.vin;
        });
    }

    // 2. Client-side Multiple Photos Management with DataTransfer & Per-Item Remove
    const photosInput = document.getElementById('photosInput');
    const previewContainer = document.getElementById('photosPreviewContainer');
    const photosSummaryBar = document.getElementById('photosSummaryBar');
    const photosCountText = document.getElementById('photosCountText');
    const clearAllPhotosBtn = document.getElementById('clearAllPhotosBtn');

    let photosDataTransfer = new DataTransfer();

    function renderPhotosPreview() {
        previewContainer.innerHTML = '';
        const files = Array.from(photosDataTransfer.files);

        if (files.length === 0) {
            photosSummaryBar.style.display = 'none';
            photosInput.files = photosDataTransfer.files;
            return;
        }

        photosSummaryBar.style.display = 'flex';
        photosCountText.textContent = `${files.length} photo${files.length > 1 ? 's' : ''} selected (${(files.reduce((a,b)=>a+b.size,0)/1024/1024).toFixed(1)} MB total)`;
        photosInput.files = photosDataTransfer.files;

        files.forEach((file, index) => {
            if (!file.type.startsWith('image/')) return;

            const card = document.createElement('div');
            card.className = 'photo-preview-item';

            const img = document.createElement('img');
            const reader = new FileReader();
            reader.onload = function (e) {
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'photo-remove-btn';
            removeBtn.innerHTML = '&times;';
            removeBtn.title = 'Remove this image';
            removeBtn.onclick = function (e) {
                e.stopPropagation();
                const newDT = new DataTransfer();
                Array.from(photosDataTransfer.files).forEach((f, i) => {
                    if (i !== index) newDT.items.add(f);
                });
                photosDataTransfer = newDT;
                renderPhotosPreview();
            };

            const badge = document.createElement('div');
            badge.style.cssText = 'position: absolute; bottom: 4px; left: 4px; background: rgba(30,105,184,0.85); color: #fff; font-size: 10px; font-weight: 600; padding: 1px 6px; border-radius: 4px;';
            badge.textContent = (file.size / 1024 / 1024).toFixed(1) + ' MB';

            card.appendChild(img);
            card.appendChild(removeBtn);
            card.appendChild(badge);
            previewContainer.appendChild(card);
        });
    }

    if (photosInput) {
        photosInput.addEventListener('change', function (e) {
            const newFiles = Array.from(e.target.files);
            newFiles.forEach(file => {
                // Check if not already in dataTransfer
                let alreadyExists = false;
                for (let i = 0; i < photosDataTransfer.files.length; i++) {
                    if (photosDataTransfer.files[i].name === file.name && photosDataTransfer.files[i].size === file.size) {
                        alreadyExists = true;
                        break;
                    }
                }
                if (!alreadyExists) {
                    photosDataTransfer.items.add(file);
                }
            });
            renderPhotosPreview();
        });
    }

    if (clearAllPhotosBtn) {
        clearAllPhotosBtn.addEventListener('click', function () {
            photosDataTransfer = new DataTransfer();
            renderPhotosPreview();
        });
    }

    // 3. Voice Note Audio Recorder & Audio File Upload
    const recordVoiceBtn = document.getElementById('recordVoiceBtn');
    const recIcon = document.getElementById('recIcon');
    const recLabel = document.getElementById('recLabel');
    const recTimer = document.getElementById('recTimer');
    const recTimeText = document.getElementById('recTimeText');
    const audioPlaybackBox = document.getElementById('audioPlaybackBox');
    const audioPreview = document.getElementById('audioPreview');
    const audioFileName = document.getElementById('audioFileName');
    const removeAudioBtn = document.getElementById('removeAudioBtn');
    const audioFileUpload = document.getElementById('audioFileUpload');
    const voiceNoteFinalInput = document.getElementById('voiceNoteFinalInput');

    let mediaRecorder = null;
    let audioChunks = [];
    let recordInterval = null;
    let recordSeconds = 0;

    function formatTime(secs) {
        const m = Math.floor(secs / 60).toString().padStart(2, '0');
        const s = (secs % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    }

    function setFinalAudioFile(file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        voiceNoteFinalInput.files = dt.files;

        const url = URL.createObjectURL(file);
        audioPreview.src = url;
        audioFileName.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
        audioPlaybackBox.style.display = 'flex';
    }

    function clearAudio() {
        voiceNoteFinalInput.value = '';
        audioPreview.src = '';
        audioPlaybackBox.style.display = 'none';
        if (audioFileUpload) audioFileUpload.value = '';
    }

    if (removeAudioBtn) {
        removeAudioBtn.addEventListener('click', clearAudio);
    }

    if (audioFileUpload) {
        audioFileUpload.addEventListener('change', function (e) {
            if (e.target.files.length > 0) {
                setFinalAudioFile(e.target.files[0]);
            }
        });
    }

    if (recordVoiceBtn) {
        recordVoiceBtn.addEventListener('click', async function () {
            // If already recording, stop recording
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
                return;
            }

            // Start new recording
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert('Audio recording is not supported in this browser. You can use the "Upload Audio File" button to attach a recording.');
                return;
            }

            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                audioChunks = [];
                
                const mimeType = MediaRecorder.isTypeSupported('audio/webm') ? 'audio/webm' :
                                 MediaRecorder.isTypeSupported('audio/ogg') ? 'audio/ogg' : 'audio/mp4';

                mediaRecorder = new MediaRecorder(stream, { mimeType: mimeType });

                mediaRecorder.ondataavailable = function (e) {
                    if (e.data.size > 0) audioChunks.push(e.data);
                };

                mediaRecorder.onstop = function () {
                    clearInterval(recordInterval);
                    recTimer.style.display = 'none';
                    recordVoiceBtn.classList.remove('recording');
                    recIcon.textContent = '🎙️';
                    recLabel.textContent = 'Record Again / Replace';

                    // Stop mic tracks
                    stream.getTracks().forEach(track => track.stop());

                    const extension = mimeType.includes('webm') ? 'webm' : (mimeType.includes('ogg') ? 'ogg' : 'mp4');
                    const audioBlob = new Blob(audioChunks, { type: mimeType });
                    const audioFile = new File([audioBlob], `voicenote_${Date.now()}.${extension}`, { type: mimeType });

                    setFinalAudioFile(audioFile);
                };

                mediaRecorder.start();
                recordSeconds = 0;
                recTimeText.textContent = '00:00';
                recTimer.style.display = 'inline-flex';
                recordVoiceBtn.classList.add('recording');
                recIcon.textContent = '⏹️';
                recLabel.textContent = 'Stop Recording';

                recordInterval = setInterval(() => {
                    recordSeconds++;
                    recTimeText.textContent = formatTime(recordSeconds);
                    if (recordSeconds >= 180) { // Max 3 minutes
                        mediaRecorder.stop();
                    }
                }, 1000);

            } catch (err) {
                console.error('Microphone access error:', err);
                alert('Could not access microphone. Please ensure microphone permissions are granted in your browser, or upload an audio file instead.');
            }
        });
    }

    // 4. Form Submit Loading State
    const form = document.getElementById('quotationForm');
    const submitBtn = document.getElementById('submitBtn');
    if (form && submitBtn) {
        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.75';
            submitBtn.innerHTML = '<span>Uploading Audio & Photos... Please wait</span>';
        });
    }
});

// Category Radio Selection Highlighter & Dynamic "Other" Field
function selectCategoryCard(labelEl) {
    document.querySelectorAll('.category-radio-card').forEach(c => c.classList.remove('active'));
    labelEl.classList.add('active');
    const radio = labelEl.querySelector('input[type="radio"]');
    if (radio) {
        radio.checked = true;
        handleCategoryChange(radio.value);
    }
}

function handleCategoryChange(val) {
    const container = document.getElementById('otherCategoryContainer');
    if (val === 'Other / Problem Not Listed') {
        container.style.display = 'block';
        document.getElementById('other_category_text').focus();
    } else {
        container.style.display = 'none';
    }
}

// Symptom Checkbox Card Highlighter & Dynamic "Other" Field
function toggleSymptomCard(labelEl) {
    const cb = labelEl.querySelector('input[type="checkbox"]');
    setTimeout(() => {
        if (cb.checked) {
            labelEl.classList.add('active');
        } else {
            labelEl.classList.remove('active');
        }
    }, 10);
}

function handleSymptomChange(checkbox) {
    if (checkbox.value === 'Other symptom not listed above') {
        const container = document.getElementById('otherSymptomContainer');
        if (checkbox.checked) {
            container.style.display = 'block';
            document.getElementById('other_symptom_text').focus();
        } else {
            container.style.display = 'none';
        }
    }
}

// Condition & Urgency Radio Options Highlighter
function selectConditionCard(labelEl) {
    const parent = labelEl.parentElement;
    parent.querySelectorAll('.condition-radio-option').forEach(c => c.classList.remove('active'));
    labelEl.classList.add('active');
    const radio = labelEl.querySelector('input[type="radio"]');
    if (radio) {
        radio.checked = true;
    }
}
</script>
@endsection
