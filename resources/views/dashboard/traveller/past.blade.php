@extends('layouts.app')

@section('content')
<section id="dash">
	<div class="grid">
		<div class="content rv">		
			@component('components.breadcrumbs.dashboard')
				  Past Trips
			@endcomponent
			<h1 class="h6">Your Previous Trips</h1>
	<div class="prop-list">
			
		<div class="outer-prob">
			@if(isset($listings))

			@else
				<p>
					You do not currently have any past trips.
				</p>
			@endif
            @foreach($listings as $listing)
			
			<p class="date">{{ date("F jS Y", strtotime($listing->start_date)) }} - {{ date("F jS Y", strtotime($listing->end_date)) }}</p>
		
			<div class="propb">
				
				<div class="grid">
					<div class="prop-img" style="background-image: url({{ $listing->url }});"></div>
					<div class="big-deats">
						<p class="h10">{{ $listing->city }}, {{ $listing->state }}</p>
						<p class="h2">{{ $listing->name }}</p>
						<p class="h11">Privately Owned <span>Fits {{ $listing->max_vehicle_length }}' RV or smaller</span></p>
						<p class="checkinout">Check in time: {{ $listing->check_in }} <span>Check out time: {{ $listing->check_out }}</span></p>
					</div>
					<div class="small-deats">
                    @if(isset($listing->day_pricing))
						<p class="h8">${{ $listing->day_pricing }} per night</p>
                    @endif
                    @if(isset($listing->month_pricing))
                        <p class="h8">${{ $listing->month_pricing }} per month</p>
                    @endif

						<a href="{{ route('view-listing', $listing->id) }}" class="button listing">View</a>
						@if(!isset($listing->review))
							<a href="{{ route('review-listing', [$listing->id]) }}">Review This Listing</a>
						@endif
					</div>
				</div>
			</div>

            @endforeach
		</div>		
			</div>
		</div><!-- END OF LEFT HAND SIDE -->
		
      	@component('components.sidebars.dashboard')
		
		@endcomponent  
        
	</div>
</section>
@endsection