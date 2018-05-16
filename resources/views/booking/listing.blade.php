@extends('layouts.app')

@section('content')
<section id="profile-listing">
	
	<div class="container">
		<div class="grid">
			<div class="main-sec">
			@guest
			@else
				@if(isset($listing->favorite))
					<a class="button love unfavorite-listing"> Saved </a>
				 @else
					<a class="button love favorite-listing"> Save for later </a>
				@endif
			@endguest
				<div class="rating">
					@component('components.misc.rating', ['rating' => $listing->rating]) @endcomponent
					{{ $listing->reviews }}
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
				@if($listing->other_amenities)
				<div class="unique-list wizy">
					<h3 class="h10">Unique To This Property</h3>
					<p>
						{{ $listing->other_amenities }}
					</p>
				</div>
				@endif
				<div class="about-owner">
					<h4 class="h10">About The Owner</h4>
					<div class="grid">
						<div class="g">
							<div class="profile-pic" style="background-image: url({{ $listing->host_url }});"></div>
							<p class="name h11">{{ $listing->host_name }}</p>
							<a class="message h12" href="{{ route('write', ['to' => $listing->host_id]) }}">Message Owner</a>
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
				<div class="reviews">
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
			
			<div class="sider">
				
				<div class="main-block">
					<a href="#" class="top cal-trigger">Reserve now <i class="fas fa-chevron-right"></i></a>
					
					<div class="check-in-out" style="display: none;">
						<div class="tabz">
							  <ul id="tabz-nav">
							    <li><a href="#tab1">Check In</a></li><!--
							    --><li><a href="#tab2">Check Out</a></li>
							  </ul> <!-- END tabz-nav -->
							  <div id="tabz-content">
							    <div id="tab1" class="tab-content">
								    
							     <div id="calendar-in"></div>
									
								<p>
									Check In: <span class="checkin-date-view"></span> {{ $listing->check_in }} <br />
									Check Out: <span class="checkout-date-view"></span> {{ $listing->check_out }}
								</p>
							    </div>
							    <div id="tab2" class="tab-content">
								    
							      <div id="calendar-out"></div>
									<p>
									 Check In: <span class="checkin-date-view"></span> {{ $listing->check_in }} <br />
									 Check Out: <span class="checkout-date-view"></span> {{ $listing->check_out }}
									</p>
									<form method="POST" action="{{ route('start-booking') }}">
										{{ csrf_field() }}
										<input type="hidden" id="checkout-date" name="checkout" required />
										<input type="hidden" id="checkin-date" name="checkin" required />
										<input type="hidden" name="listing" value="{{ $listing->id }}" />
										<input type="submit" class="button blue round" value="Continue" />
									</form>
							    </div>
							  </div> <!-- END tabz-content -->
							</div> <!-- END tabz -->
							
							<a class="cancel">Cancel</a>
							
							<p class="rates">$50 per night <span>|</span> $300 per month</p>
						
					</div>
					
					<div class="nearby wizy">
						<p class="h11">What's nearby:</p>
						<ul>
						@if(isset($listing->nearby_attractions))
							@foreach($listing->nearby_attractions as $item)
							<li>{{ $item->location }} from {{ $item->attraction }}</li>
							@endforeach
						@endif
						@if(isset($listing->nearby_conveniences))
							@foreach($listing->nearby_conveniences as $item)
							<li>{{ $item->location }} from {{ $item->attraction }}</li>
							@endforeach
						@endif
						</ul>
					</div>
					
					<div class="lot-det">
						<div class="grid">
							
							<p>
								Lot type:
								<span>
									@if($listing->property_type_id == 1)
									Private
									@else
									Public
									@endif
								</span>
							</p>
							
							<p>
								Stay term:
								<span>
									@if($listing->day_rental == 1) Short @endif 
									@if($listing->day_rental == 1 && $listing->month_rental == 1) & @endif 
									@if($listing->month_rental == 1) Long @endif
								</span>
							</p>
						</div>
					</div>
					
					
					<div class="lot-det">
						<p>
							Accommodates RV sizes:<br/>
							<span>{{ $listing->max_vehicle_length }}'</span>
						</p>
					</div>
				</div>
				
				
				
				
				<div class="map">
					<div id="map"></div>
					<p class="city-state">{{ $listing->city }}, {{ $listing->state }}</p>
				</div>
				
			</div>
		</div>
	</div>
	
</section>

@endsection


@section('scripts')
<script>
      function initMap() {
        var locations = [
       ['{{ $listing->name }}', {{ $listing->lat }}, {{ $listing->lng }}]
	  
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 12,
      center: new google.maps.LatLng({{ $listing->lat }}, {{ $listing->lng }}),
      mapTypeId: google.maps.MapTypeId.ROADMAP, 
      styles: [
    {
        "featureType": "administrative",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "lightness": 33
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "color": "#f2e5d4"
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#c5dac6"
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "all",
        "stylers": [
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#c5c6c6"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#e4d7c6"
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#fbfaf7"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#acbcc9"
            }
        ]
    }
]
    });
    
    
    var iconBase = 'https://upclose.developingpixels.com/img/';

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map, 
        icon: iconBase + 'map-icon.svg'
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }       
    
     
        
      }
	
	// Move to the general scripts... 
	
	$(document).ready(function(){
		
		// for checking against to prevent clicking a blocked off day
		var events = [
			@foreach($listingExceptions as $exception)
			{

				start  : new Date('{{ $exception->start_date }}'),
				end    : new Date('{{ $exception->end_date }}')
			},
			@endforeach
			@foreach($bookings as $booking)
			{
				start  : new Date('{{ $booking->start_date }}'),
				end    : new Date('{{ $booking->end_date }}')
			},
			  
			@endforeach
		];
		
		
		// https://momentjs.com/docs/#/displaying/format/
		$('#calendar-in').fullCalendar({
		  editable: false,
		  eventLimit: false, // allow "more" link when too many events
		  selectable: true,
		  selectOverlap: false,
		  dayClick: function(date, jsEvent, view) {
			  var dateCompare = new Date(date);
			  dateCompare.setHours(dateCompare.getHours()+4);
			  var flag = 0;
			  events.forEach(function(datepair){
				  console.log(datepair['start']);
				  console.log(dateCompare);
				  if(dateCompare < datepair['end'] && dateCompare >= datepair['start']){
					  flag = 1;
				  }
			  });
			  console.log(flag);
			  if(!flag){
				  $('#checkin-date').val(date.format());
				  $('.checkin-date-view').html(date.format('MMMM Do Y')+',');
			  }
			},
		  events: [
			@foreach($listingExceptions as $exception)
			{
				title  : '{{ $exception->title }}',
				type   : 'condition',
				@if($exception->title == 'Special Price')
				className : 'price',
				@else
				className : 'availability',
				@endif
				id	   : '{{ $exception->id }}',
				start  : '{{ $exception->start_date }}',
				end    : '{{ $exception->end_date }}',
				allDay : true,
			  	rendering: 'background',
			   backgroundColor: 'red',
			},
			@endforeach
			@foreach($bookings as $booking)
			{
				title  : 'Not available',
				type   : 'booking',
				className : 'booking',
				id	   : '{{ $booking->id }}',
				start  : '{{ $booking->start_date }}',
				end    : '{{ $booking->end_date }}',
				allDay : true,
			    rendering: 'background',
			  backgroundColor: 'red',
			},
			  
			@endforeach
		  ],
		});

		$('#calendar-out').fullCalendar({
		  editable: true,
		  eventLimit: true, // allow "more" link when too many events
		  selectable: true,
		  selectOverlap: false,
		  dayClick: function(date, jsEvent, view) {
			  var dateCompare = new Date(date);
			  dateCompare.setHours(dateCompare.getHours()+4);
			  var flag = 0;
			  events.forEach(function(datepair){
				  console.log(datepair['start']);
				  console.log(dateCompare);
				  if(dateCompare < datepair['end'] && dateCompare >= datepair['start']){
					  flag = 1;
				  }
			  });
			  console.log(flag);
			  if(!flag){
				  $('#checkout-date').val(date.format());
				  $('.checkout-date-view').html(date.format('MMMM Do Y')+',');
			  }
			},
			events: [
			@foreach($listingExceptions as $exception)
			{
				title  : '{{ $exception->title }}',
				type   : 'condition',
				@if($exception->title == 'Special Price')
				className : 'price',
				@else
				className : 'availability',
				@endif
				id	   : '{{ $exception->id }}',
				start  : '{{ $exception->start_date }}',
				end    : '{{ $exception->end_date }}',
				allDay : true,
			  	rendering: 'background',
			   backgroundColor: 'red',
			},
			@endforeach
			@foreach($bookings as $booking)
			{
				title  : 'Not available',
				type   : 'booking',
				className : 'booking',
				id	   : '{{ $booking->id }}',
				start  : '{{ $booking->start_date }}',
				end    : '{{ $booking->end_date }}',
				allDay : true,
			    rendering: 'background',
			  backgroundColor: 'red',
			},
			  
			@endforeach
		  ],
		});
		
		
		
		
		
		
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
                  listing_id: {{ $listing->id }},
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
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCyXHeiC9HRgVmhWkHPyBaM4bM7FC3TuGw&callback=initMap">
    </script>
@endsection