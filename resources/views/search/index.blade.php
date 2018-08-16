@extends('layouts.app')

@section('content')

<div class="new-filter">
	<div class="wrap">
	<h2 class="h3">Filter By:</h2>
				<form class="filt" id="filter-form" method="GET">
					<input type="hidden" name="search" placeholder="Search by city, zip code" value="{{ $request->search }}"><br />
					
					
					
					<div class="filter-block date-picker">
						<div class="input arr">
							<div class="result">Arrival: <input id="arrival" name="arrival" type="text" value="{{ (isset($request->arrival)? $request->arrival : "")}}"/>
								
							</div>
						</div>
						<div class="calendar ar"></div>
					</div>
					
					
					
					
					
					<div class="filter-block date-picker">
						<div class="input dpp">
							<div class="result">Departure: <input id="departure" name="departure" type="text" value="{{ (isset($request->departure)? $request->departure : "") }}"/>
					
							</div>
						</div>
						<div class="calendar dp"></div>
					</div>
					
					
					
					
					<div class="filter-block">
						<select name="rvTypes" onchange="this.form.submit()">
						  <option value="">Any Vehicle Type</option>
							@foreach($rvTypes as $rvType)
						  <option value="{{ $rvType->id }}" {{ ((isset($request->rvTypes) && $request->rvTypes == $rvType->id)? 'selected' : '') }}>{{ $rvType->name }}</option>
							@endforeach
						</select>
					</div>
					
<!--
					<div class="filter-block short" id="price">
						<div class="input">
							<div class="result">Price:
								<input class="price" onfocusout="this.form.submit()" name="price" value="{{ (isset($request->price)? $request->price : '')}}">
							</div>
						</div>
					</div>
-->
					
					<div class="filter-block" id="stay-length">
						<select name="rentalType" onchange="this.form.submit()">
						  <option value="0" {{ ((isset($request->rentalType) && $request->rentalType == 0)? 'selected' : '') }}>Rental Type</option>
						  <option value="1" {{ ((isset($request->rentalType) && $request->rentalType == 1)? 'selected' : '') }}>Daily</option>
						  <option value="2" {{ ((isset($request->rentalType) && $request->rentalType == 2)? 'selected' : '') }}>Monthly</option>
						</select>
					</div>
          
					<div class="filter-block" id="guest-count">
						<div class="input">
							<div class="result">Guests:
								<input placeholder="1+" class="guests" onfocusout="this.form.submit()" name="guestCount" value="{{ (isset($request->guestCount)? $request->guestCount : '')}}">
							</div>
						</div>
					</div>
					
					<div class="filter-block" id="lot-type">
						<select onchange="this.form.submit()" name="lotType">
						  <option value="">Lot Type</option>
						  <option value="1" {{ (isset($request->lotType) && $request->lotType == 1? 'selected' : '') }}>Privately Owned</option>
						  <option value="2" {{ (isset($request->lotType) && $request->lotType == 2? 'selected' : '') }}>Public Park</option>
						  <option value="3" {{ (isset($request->lotType) && $request->lotType == 3? 'selected' : '') }}>Commercially Owned</option>
						</select>
					</div>
					
				</form>
				</div>
</div><!-- Filter -->


<section id="listing-page">
	<div class="grid no-gap">
		<div class="map">
			<div id="map"></div>
		</div>
		
			<div class="property-list">
                    @if(!$location)

       <p>
            We could not find that location, please try again.
    </p>
    @endif
		        <listings-component v-bind:listings="locations" v-bind:selected-index="selectedIndex">
		        </listings-component>
			</div>
		</div>
</section>
@endsection


@section('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script>
	

  
  function initMap(locations, listingsApp) {

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 10,
      center: new google.maps.LatLng({{ $location['lat'] }}, {{ $location['lng'] }}),
      disableDefaultUI: true,
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
    
    
    var iconBase = 'http://upclose.developingpixels.com/img/';

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i].lat, locations[i].lng),
        map: map, 
        icon: iconBase + 'map-icon.svg',
        listIndex: i
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          console.log("clicked")
          listingsApp.selectedIndex = i
          infowindow.setContent(locations[i].name);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }       
    
     
}  
  
  
window.onload = function () {
    
    var locations = [
		  @foreach($listings as $listings_index => $listing)
      {
        'amenities': "{{ $listing->amenityList }}",
        'listingId': "{{$listing->listid}}",
        'listingsIndex': {{$listings_index}},
        'url': "{{ route('view-listing', ['id' => $listing->listid]) }}",
        'image_url': '{{ $listing->url }}',
        'city': '{{$listing->city}}',
        'state': '{{$listing->state}}',
        'name': "{!! $listing->name !!}",
        'property_type_id': {{$listing->property_type_id}},
        'max_vehicle_length': '{{$listing->max_vehicle_length}}',
        'month_rental': {{$listing->month_rental}},
        'month_pricing': '{{$listing->month_pricing}}',
        'day_rental': {{$listing->day_rental}},
        'day_pricing': '{{$listing->day_pricing}}',
        'accommodates': '{{max($listing->day_guests, $listing->month_guests)}}',
        'stars': {{ $listing->stars }},
        @if(isset($listing->total_reviews))
        'reviews': {{ $listing->total_reviews }},
        @endif
        'lat': {{$listing->lat}},
        'lng': {{$listing->lng}}
      },
		  @endforeach
    ];
  
  
    const listingsApp = new Vue({
      el: '#listing-page',
      data: {
        locations: locations, 
        selectedIndex: -1
      }
    }); 
  
    initMap(locations, listingsApp);  
}
$(document).ready(function(){
	$(function() {
	  $( ".calendar.dp" ).datepicker({
			dateFormat: 'mm/dd/yy',
			firstDay: 1
		});

		$(document).on('click', '.date-picker .input.dpp', function(e){
			var $me = $(this),
			$parent = $me.parents('.date-picker');
			$parent.toggleClass('open');
		});


		$(".calendar.dp").on("change",function(){
			$('input[name="departure"]').val($(this).val());
			$('#filter-form').submit();
		});

	  $( ".calendar.ar" ).datepicker({
			dateFormat: 'mm/dd/yy',
			firstDay: 1
		});

		$(document).on('click', '.date-picker .input.arr', function(e){
			var $me = $(this),
					$parent = $me.parents('.date-picker');
			$parent.toggleClass('open');
		});


		$(".calendar.ar").on("change",function(){
			$('input[name="arrival"]').val($(this).val());
			$('#filter-form').submit();
		});
	});
});


    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCyXHeiC9HRgVmhWkHPyBaM4bM7FC3TuGw">
    </script>
@endsection