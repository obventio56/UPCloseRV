@extends('layouts.app')

@section('content')
<section id="dash">
	<div class="grid">
		<div class="content rv">		
			<div class="cookie-crisp"><a href="">Dashboard <i class="fas fa-chevron-right"></i></a>  Your Page Title</div>	
			
			<h1 class="h6" id="new-listing-inline">Your Listings</h1>
			<a href="{{ route('add-listing') }}" id="add-new-listing">Add A New Listing!</a>
	<div class="prop-list">
			
		<div class="outer-prob">
		@if($listings->count == '0')
			<p>
				You do not have any listings! <a href="{{ route('add-listing') }}">Get started creating one!</a>
			</p>
		@else 
		@foreach($listings as $listing)
		<div class="propb @if(!$listing->published) deactivated @endif">

			<div class="grid">
				<div class="prop-img" style="background-image: url({{ $listing->url }});"></div>
				<div class="big-deats">
					<p class="h10">{{ $listing->city }}, {{ $listing->state }}</p>
					<p class="h2">{{ $listing->name }}</p>
					<p class="h11">Privately Owned <span>Fits {{ $listing->max_vehicle_length }}' RV or smaller</span></p>
					<p class="deactivated-text">Deactived</p>
				</div>
				<div class="small-deats">
				@if(isset($listing->day_pricing))
					<p class="h8">${{ $listing->day_pricing }} per night</p>
				@endif
				@if(isset($listing->month_pricing))
					<p class="h8">${{ $listing->month_pricing }} per month</p>
				@endif
					<div class="rating">
						@component('components.misc.rating', ['rating' => $listing->stars]) @endcomponent
						{{ $listing->total_reviews }}
					</div>
					<a href="{{ route('manage-listing', ['id' => $listing->id]) }}" class="button listing">Manage</a>
				</div>
			</div>
		</div>
			
		@endforeach
	@endif
		</div>				
	</div>

		</div><!-- END OF LEFT HAND SIDE -->
		
      	@component('components.sidebars.dashboard')
		
		@endcomponent  
        
	</div>
</section>
@endsection