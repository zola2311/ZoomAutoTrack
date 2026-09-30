<!DOCTYPE html>
<html>

<head>
    <title>@yield('title', 'AutoTrack Ethiopia')</title>
    <!--meta-->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.2" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="keywords" content="Mechanic, Garage, Auto Repair, Addis Ababa" />
    <meta name="description" content="@yield('meta_description', 'AutoTrack Ethiopia — digital garage management and vehicle service history')" />
    <!--slider revolution-->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/vendor/revolution-slider/css/settings.css') }}">
    <!--style-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,600,700,800&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/reset.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/superfish.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/prettyPhoto.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/jquery.qtip.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/animations.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/odometer-theme-default.css') }}">
    <!--fonts-->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/fonts/streamline-small/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/fonts/template/styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/fonts/social/styles.css') }}">
    <link rel="shortcut icon" href="{{ asset('frontend/assets/images/favicon.ico') }}">
    @stack('styles')
</head>

<body class="">
<div class="site-container">
    <div class="header-top-bar-container clearfix">
        <div class="header-top-bar">
            <ul class="contact-details clearfix">
                <li class="template-phone">
                    +251 XX XXX XXXX
                </li>
                <li class="template-mail">
                    <a href="mailto:info@autotrack.et">info@autotrack.et</a>
                </li>
                <li class="template-clock">
                    Mon - Sat: 8:00am - 6:00pm
                </li>
            </ul>
            <div class="search-container">
                <a class="template-search" href="#" title="Search"></a>
                <form class="search">
                    <input type="text" name="s" placeholder="Search..." value="Search..." class="search-input hint">
                    <fieldset class="search-submit-container">
                        <span class="template-search"></span>
                        <input type="submit" class="search-submit" value="">
                    </fieldset>
                    <input type="hidden" name="page" value="search">
                </form>
            </div>
        </div>
        <a href="#" class="header-toggle template-arrow-up"></a>
    </div>
    <div class="header-container">
        <div class="vertical-align-table column-1-1">
            <div class="header clearfix">
                <div class="logo vertical-align-cell">
                    <h1><a href="{{ url('/') }}" title="AutoTrack Ethiopia">AutoTrack Ethiopia</a></h1>
                </div>
                <a href="#" class="mobile-menu-switch vertical-align-cell">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </a>
                <div class="menu-container clearfix vertical-align-cell">
                    <nav>
                        <ul class="sf-menu">
                            <li class="{{ request()->is('/') ? 'selected' : '' }}">
                                <a href="{{ url('/') }}" title="Home">
                                    Home
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('about') ? 'selected' : '' }}">
                                <a href="{{ route('about') }}" title="About">
                                    About
                                </a>
                            </li>
                            <li>
                                {{-- TODO: swap for route('services') once the Services page is built --}}
                                <a href="#" title="Services">
                                    Services
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}" title="Contact">
                                    Contact
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('portal.login') }}" title="Track My Vehicle">
                                    Track My Vehicle
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="mobile-menu-container">
                        <div class="mobile-menu-divider"></div>
                        <nav>
                            <ul class="mobile-menu collapsible-mobile-submenus">
                                <li>
                                    <a href="{{ url('/') }}" title="Home">
                                        Home
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('about') }}" title="About">
                                        About
                                    </a>
                                </li>
                                <li>
                                    <a href="#" title="Services">
                                        Services
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('contact') }}" title="Contact">
                                        Contact
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('portal.login') }}" title="Track My Vehicle">
                                        Track My Vehicle
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>						</div>
            </div>
        </div>
    </div>

    @yield('content')

    <div class="row dark-gray footer-row full-width padding-top-30 padding-bottom-50">
        <div class="row padding-bottom-30">
            <div class="column column-1-3">
                <ul class="contact-details-list">
                    <li class="sl-small-location-map">
                        <p>Addis Ababa, Ethiopia</p>
                    </li>
                </ul>
            </div>
            <div class="column column-1-3">
                <ul class="contact-details-list">
                    <li class="sl-small-phone-circle">
                        <p>Feel Free to Call Us Now<br>
                            +251 XX XXX XXXX</p>
                    </li>
                </ul>
            </div>
            <div class="column column-1-3">
                <ul class="contact-details-list">
                    <li class="sl-small-truck-tow">
                        <p>Roadside Assistance<br>
                            +251 XX XXX XXXX</p>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row row-4-4 top-border page-padding-top">
            <div class="column column-1-4">
                <h6 class="box-header">ABOUT US</h6>
                <ul class="list simple margin-top-20">
                    <li>Addis Ababa, Ethiopia</li>
                    <li><span>Mobile:</span>+251 XX XXX XXXX</li>
                    <li><span>E-mail:</span><a href="mailto:info@autotrack.et">info@autotrack.et</a></li>
                </ul>
            </div>
            <div class="column column-1-4">
                <h6 class="box-header">OUR SERVICES</h6>
                <ul class="list margin-top-20">
                    <li class="template-bullet">Engine Diagnostics</li>
                    <li class="template-bullet">Lube, Oil and Filters</li>
                    <li class="template-bullet">Belts and Hoses</li>
                    <li class="template-bullet">Brake Repair</li>
                    <li class="template-bullet">Tire and Wheel Services</li>
                </ul>
            </div>
            <div class="column column-1-4">
                <h6 class="box-header">RESOURCES</h6>
                <ul class="taxonomies margin-top-30">
                    <li><a href="{{ route('portal.login') }}" title="Track My Vehicle">Track My Vehicle</a></li>
                    <li><a href="{{ route('booking.create') }}" title="Book a Service">Book a Service</a></li>
                </ul>
            </div>
            <div class="column column-1-4">
                <h6 class="box-header">HOURS</h6>
                <ul class="list simple margin-top-20">
                    <li><span>Mon - Fri:</span>8:00am - 6:00pm</li>
                    <li><span>Saturday:</span>8:00am - 4:00pm</li>
                    <li><span>Sunday:</span>Closed</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row align-center padding-top-bottom-30">
        <span class="copyright">© {{ date('Y') }} AutoTrack Ethiopia</span>
    </div>
</div>
<a href="#top" class="scroll-top animated-element template-arrow-up" title="Scroll to top"></a>
<div class="background-overlay"></div>
<!--js-->
<script type="text/javascript" src="{{ asset('frontend/assets/js/jquery-3.6.0.min.js') }}"></script>
<!--slider revolution-->
<script type="text/javascript" src="{{ asset('frontend/vendor/revolution-slider/js/jquery.themepunch.tools.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/vendor/revolution-slider/js/jquery.themepunch.revolution.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/vendor/revolution-slider/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/vendor/revolution-slider/js/extensions/revolution.extension.layeranimation.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/vendor/revolution-slider/js/extensions/revolution.extension.navigation.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/jquery.ba-bbq.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/jquery-ui-1.12.1.custom.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/jquery.ui.touch-punch.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/jquery.isotope.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/jquery.easing.1.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/jquery.carouFredSel-6.2.1-packed.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/jquery.touchSwipe.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/jquery.transit.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/jquery.hint.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/jquery.costCalculator.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/jquery.prettyPhoto.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/jquery.qtip.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/jquery.blockUI.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/jquery.imagesloaded-packed.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/main.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/odometer.min.js') }}"></script>
@stack('scripts')
</body>

</html>
