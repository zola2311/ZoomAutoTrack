@extends('layouts.site')

@section('title', 'Gallery — AutoTrack Ethiopia')
@section('meta_description', 'Photos from the AutoTrack Ethiopia workshop in Addis Ababa')

@section('content')
<div class="theme-page padding-bottom-70">
	<div class="row gray full-width page-header vertical-align-table">
		<div class="row full-width padding-top-bottom-50 vertical-align-cell">
			<div class="row">
				<div class="page-header-left">
					<h1>GALLERY</h1>
				</div>
				<div class="page-header-right">
					<div class="bread-crumb-container">
						<label>YOU ARE HERE:</label>
						<ul class="bread-crumb">
							<li><a title="Home" href="{{ url('/') }}">HOME</a></li>
							<li class="separator">&#47;</li>
							<li>GALLERY</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix">
		<div class="row">
			@if ($gallery->isEmpty())
				<p class="description align-center padding-top-70">Photos coming soon.</p>
			@else
				<ul class="galleries-list clearfix padding-top-70">
					@foreach ($gallery as $item)
						<li>
							<a href="{{ asset('uploads/' . $item->image) }}" class="prettyPhoto re-preload" title="{{ $item->caption }}">
								<img src="{{ asset('uploads/' . $item->image) }}" alt="{{ $item->caption }}">
							</a>
							@if ($item->caption)
								<div class="view align-center">
									<div class="vertical-align-table">
										<div class="vertical-align-cell">
											<p class="description">{{ $item->caption }}</p>
										</div>
									</div>
								</div>
							@endif
						</li>
					@endforeach
				</ul>
			@endif
		</div>
		<div class="row page-margin-top-section align-center">
			<p class="description align-center">
				<a href="{{ route('booking.create') }}">Book a service</a> and see the workshop for yourself.
			</p>
		</div>
	</div>
</div>
@endsection
