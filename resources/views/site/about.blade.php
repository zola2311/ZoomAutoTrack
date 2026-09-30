@extends('layouts.site')

@section('title', 'About Us — AutoTrack Ethiopia')
@section('meta_description', 'About AutoTrack Ethiopia — a digitally-tracked garage in Addis Ababa')

@section('content')
    <div class="theme-page">
        <div class="row gray full-width page-header vertical-align-table">
            <div class="row full-width padding-top-bottom-50 vertical-align-cell">
                <div class="row">
                    <div class="page-header-left">
                        <h1>ABOUT US</h1>
                    </div>
                    <div class="page-header-right">
                        <div class="bread-crumb-container">
                            <label>YOU ARE HERE:</label>
                            <ul class="bread-crumb">
                                <li>
                                    <a title="HOME" href="{{ url('/') }}">
                                        HOME
                                    </a>
                                </li>
                                <li class="separator">
                                    &#47;
                                </li>
                                <li>
                                    ABOUT US
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix">
            <div class="row margin-top-70">
                <div class="column column-1-2">
                    <p class="description margin-top-0">We offer a full range of garage services to vehicle owners in Addis Ababa. All mechanic services are performed by qualified mechanics, and every job is logged digitally from check-in to completion.</p>
                    <p class="margin-top-10">Whether you drive a passenger car, a medium-sized truck, or an SUV, our mechanics strive to make sure your vehicle is performing at its best before it leaves our shop — and unlike a paper logbook, your vehicle's full history stays with it, not with a slip of paper that gets lost.</p>
                    <h4 class="box-header margin-top-26">WHY CHOOSE US</h4>
                    <ul class="list margin-top-30">
                        <li class="template-bullet">We make auto repair and maintenance more convenient for you</li>
                        <li class="template-bullet">We are a friendly, helpful, and professional group of people</li>
                        <li class="template-bullet">Our mechanics know how to handle a wide range of car services</li>
                        <li class="template-bullet">We get the job done right — the first time</li>
                        <li class="template-bullet">Every job card, part, and inspection is recorded digitally</li>
                    </ul>
                    <div class="page-margin-top">
                        {{-- TODO: swap for route('services') once the Services page is built --}}
                        <a class="more" href="#" title="OUR SERVICES"><span>OUR SERVICES</span></a>
                    </div>
                </div>
                <div class="column column-1-2">
                    <a href="{{ asset('frontend/assets/images/samples/870x580/image_05.jpg') }}" class="prettyPhoto re-preload" title="Brake Repair">
                        <img src="{{ asset('frontend/assets/images/samples/570x380/image_05.jpg') }}" alt="img">
                    </a>
                    <div class="row margin-top-30">
                        <div class="column column-1-2">
                            <a href="{{ asset('frontend/assets/images/samples/870x580/image_07.jpg') }}" class="prettyPhoto re-preload" title="Wheel Services">
                                <img src="{{ asset('frontend/assets/images/samples/570x380/image_07.jpg') }}" alt="img">
                            </a>
                        </div>
                        <div class="column column-1-2">
                            <a href="{{ asset('frontend/assets/images/samples/870x580/image_02.jpg') }}" class="prettyPhoto re-preload" title="Oil Change">
                                <img src="{{ asset('frontend/assets/images/samples/570x380/image_02.jpg') }}" alt="img">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gray full-width page-margin-top-section page-padding-top padding-bottom-66">
                <div class="row">
                    <div class="column column-1-4 align-center">
                        <span class="number animated-element" data-value="100"></span><span class="number sign">%</span>
                        <h5 class="margin-top-10">CUSTOMER<br>SATISFACTION</h5>
                    </div>
                    <div class="column column-1-4 align-center">
                        <span class="number animated-element" data-value="15"></span>
                        <h5 class="margin-top-10">CARS REPAIRED<br>PER DAY</h5>
                    </div>
                    <div class="column column-1-4 align-center">
                        <span class="number animated-element" data-value="702"></span>
                        <h5 class="margin-top-10">TIRES REPAIRED<br>A YEAR</h5>
                    </div>
                    <div class="column column-1-4 align-center">
                        <span class="number animated-element" data-value="5125"></span>
                        <h5 class="margin-top-10">TIGHTENED<br>BOLTS</h5>
                    </div>
                </div>
            </div>
            <div class="row page-margin-top-section padding-bottom-66">
                <div class="row">
                    <h2 class="box-header">COMPANY OVERVIEW</h2>
                    <p class="description align-center">We can help you with everything from an oil change to an engine change.<br>We can handle any problem on both foreign and domestic vehicles.</p>
                </div>
                <div class="row page-margin-top-section">
                    <div class="column column-1-3">
                        <ul class="features-list">
                            <li>
                                <h5>DIGITAL JOB CARDS</h5>
                                <div class="icon sl-small-car-audio"></div>
                                <p>Every check-in creates a digital job card, so nothing gets lost between check-in and check-out.</p>
                            </li>
                            <li>
                                <h5>A/C RECHARGE</h5>
                                <div class="icon sl-small-air-conditioning"></div>
                                <p>Full diagnostics and recharge service, logged to your vehicle's record.</p>
                            </li>
                        </ul>
                    </div>
                    <div class="column column-1-3">
                        <ul class="features-list">
                            <li>
                                <h5>OIL & FLUID SERVICE</h5>
                                <div class="icon sl-small-car-oil"></div>
                                <p>Every oil change is timestamped, so you always know when the next one is due.</p>
                            </li>
                            <li>
                                <h5>PARTS TRACKING</h5>
                                <div class="icon sl-small-parking-sensor"></div>
                                <p>See exactly which parts were used on your vehicle, and when.</p>
                            </li>
                        </ul>
                    </div>
                    <div class="column column-1-3">
                        <ul class="features-list">
                            <li>
                                <h5>ENGINE DIAGNOSTICS</h5>
                                <div class="icon sl-small-signal-warning"></div>
                                <p>Modern diagnostic tools tailored to the software running in your vehicle.</p>
                            </li>
                            <li>
                                <h5>BATTERY & ELECTRICAL</h5>
                                <div class="icon sl-small-car-battery"></div>
                                <p>Battery testing and electrical repairs, recorded in your service history.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
