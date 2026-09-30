@extends('layouts.site')

@section('title', 'AutoTrack Ethiopia — Digital Garage Management')
@section('meta_description', 'AutoTrack Ethiopia — digital garage management and vehicle service history')

@section('content')
    <!-- Slider Revolution -->
    <div class="revolution-slider-container">
        <div class="revolution-slider" data-version="5.4.8" style="display: none;">
            <ul style="display: none;">
                <!-- SLIDE 1 -->
                <li data-transition="fade" data-masterspeed="500" data-slotamount="1" data-delay="6000">
                    <img src="{{ asset('frontend/assets/images/slider/image_01.jpg') }}" alt="slidebg1" data-bgfit="cover">
                    <div class="tp-caption"
                         data-frames='[{"delay":500,"speed":1200,"from":"y:-40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
                         data-x="center"
                         data-y="97"
                    >
                        <div class="hexagon"><div class="sl-small-car-oil"></div></div>
                    </div>
                    <div class="tp-caption"
                         data-frames='[{"delay":900,"speed":1200,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
                         data-x="center"
                         data-y="262"
                    >
                        <h2>KNOW YOUR CAR'S FULL SERVICE HISTORY</h2>
                    </div>
                    <div class="tp-caption"
                         data-frames='[{"delay":1100,"speed":1200,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
                         data-x="center"
                         data-y="343"
                    >
                        <p class="description">Every job card, every part, every inspection — tracked digitally from check-in to check-out.</p>
                    </div>
                    <div class="tp-caption"
                         data-frames='[{"delay":1300,"speed":1200,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
                         data-x="center"
                         data-y="441"
                    >
                        <div class="align-center">
                            <a class="more simple" href="{{ route('booking.create') }}" title="Book a Service"><span>BOOK A SERVICE</span></a>
                        </div>
                    </div>
                </li>
                <!-- SLIDE 2 -->
                <li data-transition="fade" data-masterspeed="500" data-slotamount="1" data-delay="6000">
                    <img src="{{ asset('frontend/assets/images/slider/image_02.jpg') }}" alt="slidebg2" data-bgfit="cover">
                    <div class="tp-caption customin customout"
                         data-frames='[{"delay":500,"speed":1200,"from":"y:-40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
                         data-x="center"
                         data-y="97"
                    >
                        <div class="hexagon"><div class="sl-small-car"></div></div>
                    </div>
                    <div class="tp-caption customin customout"
                         data-frames='[{"delay":900,"speed":1200,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
                         data-x="center"
                         data-y="262"
                    >
                        <h2>A DIGITAL PASSPORT FOR EVERY VEHICLE</h2>
                    </div>
                    <div class="tp-caption customin customout"
                         data-frames='[{"delay":1100,"speed":1200,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
                         data-x="center"
                         data-y="343"
                    >
                        <p class="description">Scan a QR code and see a vehicle's real service history — no more guesswork, no more lost paper records.</p>
                    </div>
                    <div class="tp-caption customin customout"
                         data-frames='[{"delay":1300,"speed":1200,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
                         data-x="center"
                         data-y="441"
                    >
                        <div class="align-center">
                            <a class="more simple" href="{{ route('booking.create') }}" title="Book a Service"><span>BOOK A SERVICE</span></a>
                        </div>
                    </div>
                </li>
                <!-- SLIDE 3 -->
                <li data-transition="fade" data-masterspeed="500" data-slotamount="1" data-delay="6000">
                    <img src="{{ asset('frontend/assets/images/slider/image_03.jpg') }}" alt="slidebg3" data-bgfit="cover">
                    <div class="tp-caption customin customout"
                         data-frames='[{"delay":500,"speed":1200,"from":"y:-40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
                         data-x="center"
                         data-y="97"
                    >
                        <div class="hexagon"><div class="sl-small-car-checklist"></div></div>
                    </div>
                    <div class="tp-caption customin customout"
                         data-frames='[{"delay":900,"speed":1200,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
                         data-x="center"
                         data-y="262"
                    >
                        <h2>BUILT FOR ETHIOPIAN GARAGES</h2>
                    </div>
                    <div class="tp-caption customin customout"
                         data-frames='[{"delay":1100,"speed":1200,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
                         data-x="center"
                         data-y="343"
                    >
                        <p class="description">Fast, reliable, and designed to work even when the connection isn't.</p>
                    </div>
                    <div class="tp-caption customin customout"
                         data-frames='[{"delay":1300,"speed":1200,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
                         data-x="center"
                         data-y="441"
                    >
                        <div class="align-center">
                            <a class="more simple" href="{{ route('booking.create') }}" title="Book a Service"><span>BOOK A SERVICE</span></a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="theme-page">
        <div class="clearfix">
            <div class="row gray full-width">
                <div class="announcement clearfix">
                    <ul class="columns no-width">
                        <li class="column column-2-3">
                            <div class="vertical-align">
                                <div class="vertical-align-cell">
                                    <h3>BOOK YOUR SERVICE APPOINTMENT ONLINE</h3>
                                </div>
                            </div>
                        </li>
                        <li class="column column-1-3">
                            <div class="vertical-align">
                                <div class="vertical-align-cell">
                                    <a class="more" href="{{ route('booking.create') }}" title="Book Appointment"><span>BOOK APPOINTMENT</span></a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row page-margin-top-section">
                <div class="row">
                    <h2 class="box-header">WHY CHOOSE US?</h2>
                    <p class="description align-center">We're one of Addis Ababa's leading digitally-tracked garages.<br>Every service is logged, every part is accounted for, every job card is transparent.</p>
                    <div class="row page-margin-top">
                        <div class="column column-1-3">
                            <ul class="features-list big">
                                <li>
                                    <div class="hexagon"><div class="sl-small-user-chat"></div></div>
                                    <h4 class="box-header page-margin-top">EVERY JOB IS PERSONAL</h4>
                                    <p>You get the quality of a dealership service with the personal attention of a neighborhood garage.</p>
                                </li>
                            </ul>
                        </div>
                        <div class="column column-1-3">
                            <ul class="features-list big">
                                <li>
                                    <div class="hexagon"><div class="sl-small-wrench-screwdriver"></div></div>
                                    <h4 class="box-header page-margin-top">DIGITAL RECORD-KEEPING</h4>
                                    <p>Your vehicle's full history — parts, labor, inspections — is recorded and available whenever you need it.</p>
                                </li>
                            </ul>
                        </div>
                        <div class="column column-1-3">
                            <ul class="features-list big">
                                <li>
                                    <div class="hexagon"><div class="sl-small-truck-tow"></div></div>
                                    <h4 class="box-header page-margin-top">PROFESSIONAL STANDARDS</h4>
                                    <p>We only do the work that's actually needed to fix your problem, and we can show you why.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="align-center margin-top-67 padding-bottom-20">
                        <a class="more" href="{{ route('about') }}" title="Read More"><span>READ MORE</span></a>
                    </div>
                </div>
            </div>
            <div class="row full-width page-padding-top-section">
                <div class="row">
                    <h2 class="box-header">OUR SERVICES</h2>
                    <p class="description align-center">We offer a full range of garage services to vehicle owners in Addis Ababa.<br>Every job is logged in your vehicle's digital history the moment it's done.</p>
                    <ul class="services-list clearfix page-margin-top">
                        <li>
                            <a href="#" title="Engine Diagnostics">
                                <img src="{{ asset('frontend/assets/images/samples/390x260/image_01.jpg') }}" alt="">
                            </a>
                            <h4 class="box-header"><a href="#" title="Engine Diagnostics">ENGINE DIAGNOSTICS<span class="template-arrow-menu"></span></a></h4>
                        </li>
                        <li>
                            <a href="#" title="Lube, Oil and Filters">
                                <img src="{{ asset('frontend/assets/images/samples/390x260/image_02.jpg') }}" alt="">
                            </a>
                            <h4 class="box-header"><a href="#" title="Lube, Oil and Filters">LUBE, OIL AND FILTERS<span class="template-arrow-menu"></span></a></h4>
                        </li>
                        <li>
                            <a href="#" title="Belts and Hoses">
                                <img src="{{ asset('frontend/assets/images/samples/390x260/image_03.jpg') }}" alt="">
                            </a>
                            <h4 class="box-header"><a href="#" title="Belts and Hoses">BELTS AND HOSES<span class="template-arrow-menu"></span></a></h4>
                        </li>
                    </ul>
                    <div class="align-center margin-top-40 padding-bottom-87">
                        <a class="more" href="#" title="View All Services"><span>VIEW ALL SERVICES</span></a>
                    </div>
                </div>
            </div>
            <div class="row page-margin-top-section">
                <div class="row">
                    <h2 class="box-header">WHAT WE HANDLE</h2>
                    <p class="description align-center">From an oil change to a full engine job — logged, tracked, and available in your vehicle's record.</p>
                </div>
                <div class="row page-margin-top-section">
                    <div class="column column-1-3">
                        <ul class="features-list">
                            <li>
                                <h5>DIGITAL JOB CARDS</h5>
                                <div class="icon sl-small-car-audio"></div>
                                <p>Every check-in creates a digital job card — no more paper slips that get lost.</p>
                            </li>
                            <li>
                                <h5>PARTS TRACKING</h5>
                                <div class="icon sl-small-air-conditioning"></div>
                                <p>See exactly which parts were used on your vehicle, and when.</p>
                            </li>
                        </ul>
                    </div>
                    <div class="column column-1-3">
                        <ul class="features-list">
                            <li>
                                <h5>TRANSPARENT INVOICING</h5>
                                <div class="icon sl-small-car-oil"></div>
                                <p>Clear, itemized invoices generated straight from the work that was actually done.</p>
                            </li>
                            <li>
                                <h5>SERVICE REMINDERS</h5>
                                <div class="icon sl-small-parking-sensor"></div>
                                <p>Know when your next service is due, based on mileage and time.</p>
                            </li>
                        </ul>
                    </div>
                    <div class="column column-1-3">
                        <ul class="features-list">
                            <li>
                                <h5>VEHICLE INSPECTIONS</h5>
                                <div class="icon sl-small-signal-warning"></div>
                                <p>Thorough inspections logged and available for you to review anytime.</p>
                            </li>
                            <li>
                                <h5>MECHANIC ACCOUNTABILITY</h5>
                                <div class="icon sl-small-car-battery"></div>
                                <p>Every job is assigned to a specific mechanic, so quality stays consistent.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row page-margin-top-section">
                <div class="column column-1-2">
                    <h3 class="box-header">OUR MISSION</h3>
                    <p class="description">We offer a full range of garage services to vehicle owners in Addis Ababa, backed by digital record-keeping that keeps your service history accurate and available.</p>
                    <p>Whether you drive a passenger car, a truck, or an SUV, our mechanics work to make sure your vehicle performs at its best before it leaves our shop — and we keep a full record of exactly what was done, every time.</p>
                    <div class="page-margin-top">
                        <a class="more" href="{{ route('about') }}" title="Read More"><span>READ MORE</span></a>
                    </div>
                </div>
                <div class="column column-1-2">
                    <h3 class="box-header">POPULAR QUESTIONS</h3>
                    <ul class="accordion margin-top-40 clearfix">
                        <li>
                            <div id="accordion-service-history">
                                <h4>How can I see my vehicle's service history?</h4>
                            </div>
                            <p>Once we service your vehicle, its record is stored digitally. Ask us for your vehicle's QR passport to look it up anytime.</p>
                        </li>
                        <li>
                            <div id="accordion-parts-replacements">
                                <h4>What parts should be replaced at what intervals?</h4>
                            </div>
                            <p>It depends on your vehicle's make, model, and mileage — our mechanics will flag anything due during your service.</p>
                        </li>
                        <li>
                            <div id="accordion-track-routine">
                                <h4>How do I keep track of routine maintenance?</h4>
                            </div>
                            <p>We track it for you — every visit is logged, and we'll let you know when your next service is coming up.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
