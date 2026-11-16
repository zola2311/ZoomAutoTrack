@extends('layouts.site')

@section('title', 'Quotation Request Confirmed — Zoom EV')

@section('content')
<div class="theme-page padding-bottom-66">
    <div class="row gray full-width page-header vertical-align-table">
        <div class="row full-width padding-top-bottom-50 vertical-align-cell">
            <div class="row">
                <div class="page-header-left">
                    <h1>QUOTATION CONFIRMATION</h1>
                </div>
                <div class="page-header-right">
                    <div class="bread-crumb-container">
                        <label>YOU ARE HERE:</label>
                        <ul class="bread-crumb">
                            <li><a title="HOME" href="{{ url('/') }}">HOME</a></li>
                            <li class="separator">&#47;</li>
                            <li>CONFIRMATION #{{ $quotation->quotation_number }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix">
        <div class="row page-margin-top">
            <div class="column column-1-1">
                <div style="max-width: 820px; margin: 0 auto; background: #ffffff; border: 1px solid #C6DCF1; border-radius: 12px; box-shadow: 0 4px 18px rgba(30,105,184,0.08); overflow: hidden;">
                    <!-- Top Success Hero (Website Theme Blue Gradient) -->
                    <div style="background: linear-gradient(135deg, #1E69B8 0%, #155294 100%); padding: 36px 30px; text-align: center; color: #ffffff;">
                        <div style="width: 66px; height: 66px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto;">
                            <svg width="34" height="34" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h2 style="font-size: 24px; font-weight: 700; color: #ffffff; margin-bottom: 6px;">
                            Quotation Request Submitted Successfully!
                        </h2>
                        <p style="font-size: 14.5px; color: rgba(255,255,255,0.92); margin-bottom: 16px;">
                            Your vehicle inquiry has been received by the engineering team at Zoom EV Bole.
                        </p>
                        <div style="display: inline-block; background: rgba(255,255,255,0.18); border: 1px dashed rgba(255,255,255,0.45); border-radius: 8px; padding: 8px 24px;">
                            <span style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: rgba(255,255,255,0.85); display: block;">Tracking Reference Number:</span>
                            <span style="font-size: 21px; font-weight: 800; color: #ffffff; letter-spacing: 0.05em;">{{ $quotation->quotation_number }}</span>
                        </div>
                    </div>

                    <!-- Details Body -->
                    <div style="padding: 30px;">
                        <h3 style="font-size: 16px; font-weight: 700; color: #1E3A5F; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.03em;">
                            Summary of Submitted Request
                        </h3>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; background: #EBF3FA; border: 1px solid #C6DCF1; border-radius: 8px; padding: 18px; margin-bottom: 25px;">
                            <div>
                                <span style="font-size: 11px; text-transform: uppercase; color: #1E69B8; font-weight: 700; display: block;">Customer</span>
                                <span style="font-size: 14px; font-weight: 700; color: #1E3A5F;">{{ $quotation->customer_name }}</span>
                                <div style="font-size: 12.5px; color: #556B82;">{{ $quotation->phone }} &bull; {{ $quotation->email }}</div>
                            </div>

                            <div>
                                <span style="font-size: 11px; text-transform: uppercase; color: #1E69B8; font-weight: 700; display: block;">Vehicle</span>
                                <span style="font-size: 14px; font-weight: 700; color: #1E3A5F;">{{ $quotation->vehicle_display_name }}</span>
                                <div style="font-size: 12.5px; color: #556B82;">{{ $quotation->fuel_type ?: 'Fuel not specified' }} &bull; {{ $quotation->transmission ?: 'Transmission not specified' }}</div>
                            </div>

                            <div>
                                <span style="font-size: 11px; text-transform: uppercase; color: #1E69B8; font-weight: 700; display: block;">Category</span>
                                <span style="font-size: 14px; font-weight: 700; color: #1E69B8;">{{ $quotation->service_category }}</span>
                                <div style="font-size: 12.5px; color: #556B82;">Condition: {{ ucfirst(str_replace('_', ' ', $quotation->drivable_status)) }}</div>
                            </div>
                        </div>

                        <!-- Customer Problem Description & Voice Note -->
                        <div style="margin-bottom: 25px;">
                            <span style="font-size: 12.5px; font-weight: 700; color: #1E3A5F; text-transform: uppercase; display: block; margin-bottom: 6px;">
                                Problem Description & Requested Works:
                            </span>
                            <div style="background: #ffffff; border-left: 4px solid #1E69B8; border: 1px solid #C6DCF1; border-left-width: 4px; padding: 14px 18px; border-radius: 6px; font-size: 14px; color: #25282A; line-height: 1.55;">
                                @if(!empty($quotation->description))
                                    "{{ $quotation->description }}"
                                @else
                                    <span style="color: #64748B; font-style: italic;">No written description provided. Please refer to selected symptoms and attached voice recording.</span>
                                @endif
                            </div>
                        </div>

                        <!-- Voice Note Player if Present -->
                        @if ($quotation->hasVoiceNote())
                            <div style="margin-bottom: 25px; background: #F8FAFC; border: 1px solid #C6DCF1; border-radius: 8px; padding: 14px 18px;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                    <span style="font-size: 18px;">🎙️</span>
                                    <span style="font-size: 13px; font-weight: 700; color: #1E3A5F;">Submitted Voice Note Recording:</span>
                                </div>
                                <audio controls preload="metadata" style="width: 100%; max-width: 500px; height: 38px;">
                                    <source src="{{ $quotation->voice_note_url }}">
                                    Your browser does not support audio playback.
                                </audio>
                            </div>
                        @endif

                        <!-- Uploaded Photos Display -->
                        @if ($quotation->defectPhotos->isNotEmpty())
                            <div style="margin-bottom: 25px;">
                                <span style="font-size: 12.5px; font-weight: 700; color: #1E3A5F; text-transform: uppercase; display: block; margin-bottom: 8px;">
                                    Uploaded Defect Pictures ({{ $quotation->defectPhotos->count() }}):
                                </span>
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    @foreach ($quotation->defectPhotos as $photo)
                                        <div style="width: 84px; height: 84px; border-radius: 8px; overflow: hidden; border: 1px solid #C6DCF1; box-shadow: 0 1px 3px rgba(30,105,184,0.1);">
                                            <a href="{{ asset('uploads/' . $photo->path) }}" target="_blank">
                                                <img src="{{ asset('uploads/' . $photo->path) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Defect Photo">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Step-by-Step Roadmap (Website Light Blue Theme) -->
                        <div style="background: #EBF3FA; border: 1px solid #C6DCF1; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
                            <h4 style="font-size: 14.5px; font-weight: 700; color: #1E3A5F; margin-bottom: 12px;">What happens next?</h4>
                            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13.5px; color: #334155;">
                                <div style="display: flex; align-items: flex-start; gap: 10px;">
                                    <span style="width: 22px; height: 22px; border-radius: 50%; background: #1E69B8; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0;">1</span>
                                    <span><strong>Diagnostic Assessment:</strong> Our service advisors review your symptoms, diagnostic requirements, and uploaded photos.</span>
                                </div>
                                <div style="display: flex; align-items: flex-start; gap: 10px;">
                                    <span style="width: 22px; height: 22px; border-radius: 50%; background: #1E69B8; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0;">2</span>
                                    <span><strong>Quotation Report Delivered:</strong> An itemized estimate detailing labor hours, required spare parts, and pricing will be sent to <strong>{{ $quotation->email }}</strong>.</span>
                                </div>
                                <div style="display: flex; align-items: flex-start; gap: 10px;">
                                    <span style="width: 22px; height: 22px; border-radius: 50%; background: #1E69B8; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0;">3</span>
                                    <span><strong>One-Click Booking:</strong> You can accept the quotation and book your preferred check-in slot directly.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div style="display: flex; flex-wrap: wrap; gap: 12px; justify-content: space-between; align-items: center;">
                            <a href="{{ url('/') }}" style="color: #1E69B8; font-size: 13.5px; text-decoration: underline; font-weight: 700;">
                                &larr; Return to Home Page
                            </a>

                            <div style="display: flex; gap: 10px;">
                                <a href="tel:+251911000000" style="background: #EBF3FA; color: #1E69B8; padding: 10px 18px; border-radius: 6px; font-weight: 700; font-size: 13.5px; text-decoration: none; border: 1px solid #C6DCF1;">
                                    📞 Call Workshop
                                </a>
                                <a href="{{ route('booking.create') }}" style="background: #1E69B8; color: #ffffff; padding: 10px 20px; border-radius: 6px; font-weight: 700; font-size: 13.5px; text-decoration: none; box-shadow: 0 2px 8px rgba(30,105,184,0.3);">
                                    Book Direct Appointment &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
