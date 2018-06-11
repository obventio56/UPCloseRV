@foreach($listings as $listing)
<div class="item verified">
	<div class="featured-image" style="background-image: url({{ $listing->url }});"></div>
	<p class="location h10">{{ $listing->city }}, {{ $listing->state }}</p>
	<p class="property-name h2">{{ $listing->name }}</p>
	<div class="grid">
		<div>
			<p>
				@if($listing->property_type_id == 1)
					Private
				@else
					Public
				@endif
				<span>Fits {{ $listing->max_vehicle_length }}' RV or Smaller</span>
			</p>
		</div>
		
		<div>
			@if($listing->day_rental)
				<p class="price">${{ $listing->day_pricing }} per night</p>
			@endif
			@if($listing->month_rental)
				<p class="price">${{ $listing->month_pricing }} per month</p>
			@endif
			<div class="rating">
				@component('components.misc.rating', ['rating' => $listing->rating]) @endcomponent
				{{ $listing->reviews }}
			</div>
			<a href="{{ route('view-listing', ['id' => $listing->listid]) }}" class="button listing">View</a>
		</div>
	</div>
</div>
@endforeach