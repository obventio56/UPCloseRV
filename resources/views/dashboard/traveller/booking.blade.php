@extends('layouts.app')

@section('content')

<section id="dash">
	<div class="grid">
		<div class="content rv">
			@component('components.breadcrumbs.dashboard')
				  Booking
			@endcomponent
			<h1 class="h6">{{ date('M d, Y', strtotime($booking->start_date)) }} - {{ date('M d, Y', strtotime($booking->end_date)) }}</h1>
			<a href="{{ route('write', [$listing->user_id]) }}" class="button brown round">Message Host</a> 
			<a data-fancybox="" data-src="#cancel" data-modal="true" href="javascript:;" class="button brown round">Cancel Reservation</a> 

			<div class="main-sec dasher">

	
				
			@guest
			@else
				@if(isset($listing->favorite))
					<a class="button love unfavorite-listing"> Saved </a>
				 @else
					<a class="button love favorite-listing"> Save for later </a>
				@endif
			@endguest
				<div class="rating">
					<a href="#review-section">
					@component('components.misc.rating', ['rating' => $listing->rating]) @endcomponent
					{{ $listing->reviews }}
					</a>
				</div><br/>
				<h1 class="h3">{{ $listing->name }}</h1>@if($listing->verified)<span class="verified">Verified</span>@endif
				<p class="h11">
					@if($listing->day_rental)
						${{ $listing->day_pricing }} per night 
					@endif
					@if($listing->day_rental && $listing->month_rental)
					|
					@endif
					@if($listing->month_rental)
						${{ $listing->month_pricing }} per month
					@endif
				</p>
				
				<div class="slide-cont">
				  <div class="owl-carousel">
					  @foreach($listing->images as $image)
				     <div class="slid" style="background-image: url({{ $image->url }})"></div>
					  @endforeach
				  </div>
				</div>
				
				<div class="about">
					<h2 class="h10">About This Property</h2>
					<p>{{ $listing->description }}</p>
					
				@if($listing->instruct_find)
					<br /><br />
					<h2 class="h10">Directions</h2>
					<p>{{ $listing->instruct_find }}</p>
				@endif
					
				@if($listing->instruct_parking)
					<br /><br />
					<h2 class="h10">Where to Park</h2>
					<p>{{ $listing->instruct_parking }}</p>
				@endif
				
					
				@if($listing->checkin_rules)
					<br /><br />
					<h2 class="h10">Check-in</h2>
					<p>{{ $listing->checkin_rules }}</p>
				@endif
				</div>
				<div class="amenities">
					<h3 class="h10">Property Amenities</h3>
					<div class="grid">
						<p>
							@foreach($amenities as $amenity)
								@if($amenity->type == "Convenience")
									<span class="{{ $amenity->active }}">{{ $amenity->name }}</span>
								@endif
							@endforeach
						</p>
						
						<p>
							@foreach($amenities as $amenity)
								@if($amenity->type == "Activity")
									<span class="{{ $amenity->active }}">{{ $amenity->name }}</span>
								@endif
							@endforeach
						</p>
						
						<p>
							@foreach($amenities as $amenity)
								@if($amenity->type == "Locale")
									<span class="{{ $amenity->active }}">{{ $amenity->name }}</span>
								@endif
							@endforeach
						</p>
					</div>
				</div>
				
				<div class="rv-amenities">
					<h3 class="h10">RV Amenities</h3>
					<div class="grid">
						<div>
							<p id="electric">
								Electric Hookup:
								<span>
									@if(!isset($listing->electric_hookup))
									 No
									@elseif($listing->electric_hookup == 1)
									 110 V
									@elseif($listing->electric_hookup == 2)
									 30 AMP
									@elseif($listing->electric_hookup == 3)
									 50 AMP
									@endif
								</span>
							</p>
						</div>
						
						<div>
							<p id="sewer">
								Sewer Hookup:
								<span>{{ ($listing->sewer_hookup? 'Yes' : 'No') }}</span>
							</p>
						</div>
						
						<div>
							<p id="water">
								Water Hookup:
								<span>{{ ($listing->water_hookup? 'Yes' : 'No') }}</span>
							</p>
						</div>
					</div>
				</div>
				
				<div class="unique-list wizy">
					<h3 class="h10">Unique To This Property</h3>
					<p>
						{{ $listing->other_amenities }}
					</p>
				</div>
				
				<div class="about-owner">
					<h4 class="h10">About The Owner</h4>
					<div class="grid">
						<div class="g">
							<div class="profile-pic" style="background-image: url({{ $listing->host_url }});"></div>
							<p class="name h11">{{ $listing->host_name }}</p>
							<a class="message h12" href="{{ route('write', [$listing->user_id])}}">Message Owner</a>
						</div>
						
						<div class="g">
							<p>{{ $listing->host_description }}</p>
						</div>
					</div>
				</div>
				
				@if($other_listings->count())
				<div class="other-props">
					<h4 class="h10">See other properties owned by this person</h4>
					@foreach($other_listings as $other_listing)
					<div class="propss">
						<div class="grid">
							<div class="ft-img" style="background-image: url({{ $other_listing->url }});"></div>
							<div class="prop-det">
								<p class="h10">{{ $other_listing->city }}, {{ $other_listing->state }}</p>
								<p class="h2">{{ $other_listing->name }}</p>
								<p class="h11">@if($other_listing->property_type_id == 1) Private @else Public @endif <span>Fits {{ $other_listing->max_vehicle_length }}' RV or smaller</span></p>
								<a href="" class="button listing">View</a>
							</div>
						</div>
					</div>
					@endforeach
				</div>
				@endif
				@if($reviews->count())
				<div class="reviews" id="review-section">
					<h5 class="h10">Reviews</h5>
					
						@foreach($reviews as $review)
							<div class="review">
								<div class="rating">
									@component('components.misc.rating', ['rating' => $review->stars]) @endcomponent
								</div>
								<p class="rev-content">{{ $review->review }}<span>-{{ $review->name }}</span></p>
							</div>
						@endforeach

					
					<!--<a class="button listing more" id="loadMore">More</a>-->
				</div>
				@endif
				
			</div>
		</div>
			@component('components.sidebars.dashboard')
		
			@endcomponent  
		</div>
	</div>
	
</section>

@endsection


@section('scripts')
<script>
   
   
	
	$(document).ready(function(){

		// Save listing is all ajaxy!!
        $('.favorite-listing').click(function(e){
               e.preventDefault();
               $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
		$.ajax({
                  url: "{{ url('/account/listing/favorite') }}",
                  method: 'post',
                  data: {
                    // listing_id: {{ $listing->id }},
					_token: '{{ csrf_token() }}'
                  },
                  success: function(result){
                     console.log(result);
					$('.favorite-listing').html('Saved');
					$('.favorite-listing').addClass('unfavorite-listing');
					$('.favorite-listing').removeClass('favorite-listing');
                  }});
		
            });
	
		$('.unfavorite-listing').click(function(e){
               e.preventDefault();
               $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
		$.ajax({
                  url: "{{ url('/account/listing/favorite') }}",
                  method: 'post',
                  data: {
                     listing_id: {{ $listing->id }},
					_token: '{{ csrf_token() }}'
                  },
                  success: function(result){
                     console.log(result);
					$('.unfavorite-listing').html('Save for later');
					$('.unfavorite-listing').addClass('favorite-listing');
					$('.unfavorite-listing').removeClass('unfavorite-listing');
                  }});
		
            });
     });

    </script>
@endsection

@section('popup')
<div id="cancel" class="p-5 fancybox-content" style="display: none;max-width: 900px;">
	<a class="close" data-fancybox-close><img src="/img/x.svg"></a>
	<p class="h4">Are you sure you want to cancel this reservation?</p>
	<p>Pommy ipsum a diamond geezer chips some mothers do 'ave 'em beefeater oopsy-daisies plum pudding, a cuppa hadn't done it in donkey's years terribly bowler hat conkers pompous.</p>
	
	<h2 class="h6">Send a message to your host.</h2>
	<form class="style" method="POST" action="{{ route('cancel-booking') }}">
		{{ csrf_field() }}
		<input type="hidden" name="booking_id" value="{{ Request::segment(3) }}">
		<textarea name="message" placeholder="This is my message..."></textarea>
	
	<div class="grid"> <input type="submit" class="two button brown round" value="confirm cancellation" /></div>
	</form>
	<a class="two button white round">never-mind, take me back</a>
</div>
@endsection