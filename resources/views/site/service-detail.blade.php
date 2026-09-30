@extends('layouts.site')

@section('title', $service->title . ' — AutoTrack Ethiopia')
@section('meta_description', Str::limit(strip_tags($service->overview), 150))

@section('content')
<div class="theme-page">
	<div class="row gray full-width page-header vertical-align-table">
		<div class="row full-width padding-top-bottom-50 vertical-align-cell">
			<div class="row">
				<div class="page-header-left">
					<h1>{{ strtoupper($service->title) }}</h1>
				</div>
				<div class="page-header-right">
					<div class="bread-crumb-container">
						<label>YOU ARE HERE:</label>
						<ul class="bread-crumb">
							<li><a title="HOME" href="{{ url('/') }}">HOME</a></li>
							<li class="separator">&#47;</li>
							<li><a title="Our Services" href="{{ route('services') }}">OUR SERVICES</a></li>
							<li class="separator">&#47;</li>
							<li>{{ strtoupper($service->title) }}</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix">
		<div class="row margin-top-70">
			<div class="column column-1-4">
				<ul class="vertical-menu">
					@foreach ($allServices as $item)
						<li class="{{ $item->id === $service->id ? 'selected' : '' }}">
							<a href="{{ route('services.show', $item) }}" title="{{ $item->title }}">
								{{ $item->title }}
								<span class="template-arrow-menu"></span>
							</a>
						</li>
					@endforeach
				</ul>
				<div class="call-to-action page-margin-top">
					<div class="hexagon small"><div class="sl-small-percent"></div></div>
					<h4 class="margin-top-58">ONLINE APPOINTMENT</h4>
					<p class="description">Book this service online — no account needed.</p>
					<a class="more" href="{{ route('booking.create') }}" title="MAKE APPOINTMENT"><span>MAKE APPOINTMENT</span></a>
				</div>
			</div>
			<div class="column column-3-4">
				@if ($service->image)
					<div class="row">
						<div class="column column-1-1">
							<img src="{{ asset('uploads/' . $service->image) }}" alt="{{ $service->title }}">
						</div>
					</div>
				@endif
				@if ($service->overview)
					<div class="row page-margin-top">
						<div class="column-1-1">
							<h3 class="box-header">SERVICE OVERVIEW</h3>
							<p class="margin-top-20">{{ $service->overview }}</p>
						</div>
					</div>
				@endif
				<div class="row page-margin-top padding-bottom-70">
					@if (!empty($service->points))
						<div class="column column-1-2">
							<h4 class="box-header">WHAT'S INCLUDED</h4>
							<ul class="list margin-top-20">
								@foreach ($service->points as $point)
									<li class="template-bullet">{{ $point }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					<div class="column column-1-2">
						<h4 class="box-header">PRICING</h4>
						<p class="margin-top-20">
							Pricing depends on your vehicle's make, model and condition.
							<a href="{{ route('booking.create') }}">Request an appointment</a> or
							<a href="{{ route('contact') }}">get in touch</a> and we'll give you a quote before any work starts.
						</p>
						<div class="page-margin-top">
							<a class="more" href="{{ route('booking.create') }}" title="Book This Service"><span>BOOK THIS SERVICE</span></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
