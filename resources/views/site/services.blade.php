@extends('layouts.site')

@section('title', 'Our Services — AutoTrack Ethiopia')
@section('meta_description', 'Garage services in Addis Ababa — from basic checkups to EV diagnostics')

@section('content')
<div class="theme-page padding-bottom-70">
	<div class="row gray full-width page-header vertical-align-table">
		<div class="row full-width padding-top-bottom-50 vertical-align-cell">
			<div class="row">
				<div class="page-header-left">
					<h1>OUR SERVICES</h1>
				</div>
				<div class="page-header-right">
					<div class="bread-crumb-container">
						<label>YOU ARE HERE:</label>
						<ul class="bread-crumb">
							<li><a title="Home" href="{{ url('/') }}">HOME</a></li>
							<li class="separator">&#47;</li>
							<li>OUR SERVICES</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix">
		<div class="row">
			@if ($services->isEmpty())
				<p class="description align-center padding-top-70">Our service list is being updated — please check back soon.</p>
			@else
				<ul class="services-list clearfix padding-top-70">
					@foreach ($services as $service)
						<li>
							<a href="{{ route('services.show', $service) }}" title="{{ $service->title }}">
								@if ($service->image)
									<img src="{{ asset('uploads/' . $service->image) }}" alt="{{ $service->title }}">
								@else
									<img src="{{ asset('frontend/assets/images/samples/390x260/image_01.jpg') }}" alt="{{ $service->title }}">
								@endif
							</a>
							<h4 class="box-header">
								<a href="{{ route('services.show', $service) }}" title="{{ $service->title }}">
									{{ strtoupper($service->title) }}<span class="template-arrow-menu"></span>
								</a>
							</h4>
						</li>
					@endforeach
				</ul>
			@endif
			<p class="description align-center margin-top-30">
				Not sure which service you need? <a href="{{ route('booking.create') }}">Request an appointment</a> and describe the problem — we'll figure out the rest.
			</p>
		</div>
	</div>
</div>
@endsection
