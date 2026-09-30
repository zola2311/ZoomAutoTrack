@extends('layouts.site')

@section('title', 'Our Team — AutoTrack Ethiopia')
@section('meta_description', 'Meet the mechanics and staff at AutoTrack Ethiopia')

@section('content')
<div class="theme-page padding-bottom-70">
	<div class="row gray full-width page-header vertical-align-table">
		<div class="row full-width padding-top-bottom-50 vertical-align-cell">
			<div class="row">
				<div class="page-header-left">
					<h1>OUR TEAM</h1>
				</div>
				<div class="page-header-right">
					<div class="bread-crumb-container">
						<label>YOU ARE HERE:</label>
						<ul class="bread-crumb">
							<li><a title="Home" href="{{ url('/') }}">HOME</a></li>
							<li class="separator">&#47;</li>
							<li>OUR TEAM</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix">
		<div class="row">
			@if ($team->isEmpty())
				<p class="description align-center padding-top-70">Team details coming soon.</p>
			@else
				<ul class="team-list padding-top-70 clearfix">
					@foreach ($team as $member)
						<li class="team-box">
							@if ($member->photo)
								<img alt="{{ $member->name }}" src="{{ asset('uploads/' . $member->photo) }}">
							@endif
							<div class="team-content">
								<h4 class="box-header">
									{{ strtoupper($member->name) }}
									@if ($member->role)
										<span>{{ strtoupper($member->role) }}</span>
									@endif
								</h4>
								@if ($member->bio)
									<p>{{ $member->bio }}</p>
								@endif
							</div>
						</li>
					@endforeach
				</ul>
			@endif
		</div>
		<div class="row page-margin-top-section align-center">
			<h3 class="box-header">WORK WITH US</h3>
			<p class="description align-center">
				Looking for a garage that keeps a real record of every job?
				<a href="{{ route('booking.create') }}">Book a service</a> and see how we work.
			</p>
		</div>
	</div>
</div>
@endsection
