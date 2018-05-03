@extends('layouts.app')

@section('content')
<section id="listing-page">
	<div class="grid no-gap">
		<div class="map">
			<div id="map"></div>
		</div>
		
		<div class="property-list">
			<div class="filter">
				<form class="filt" id="filter-form" method="GET">
					<input type="text" name="search" placeholder="Search by city, zip code" value="{{ $request->search }}"><br />
					<div class="filter-block date-picker">
						<div class="input">
							<div class="result">Arrival: <input class="date" onupdate="this.form.submit()" name="arrival" type="text" value="{{ (isset($request->arrival)? $request->arrival : '')}}"/></div>
						</div>
						<div class="calendar"></div>
					</div>
					
					<div class="filter-block date-picker">
						<div class="input">
							<div class="result">Departure: <input class="date" onupdate="this.form.submit()" name="departure" type="text" value="{{ (isset($request->departure)? $request->departure : '')}}" /></div>
						</div>
						<div class="calendar"></div>
					</div>
					
					<div class="filter-block">
						<select name="rvTypes" onchange="this.form.submit()">
						  <option value="">Vehicle Type</option>
							@foreach($rvTypes as $rvType)
						  <option value="{{ $rvType->id }}">{{ $rvType->name }}</option>
							@endforeach
						</select>
					</div>
					
					<div class="filter-block short" id="price">
						<div class="input">
							<div class="result">Price:
								<input class="price" onfocusout="this.form.submit()" name="price" value="{{ (isset($request->price)? $request->price : '')}}">
							</div>
						</div>
					</div>
					
					<div class="filter-block short" id="amenities">
						<select name="amenities" onchange="location.reload()">
						  <option value="">Amenities</option>
						@foreach($amenities as $amenity)
							<option value="{{ $amenity->id }}">{{ $amenity->name }}</option>
						@endforeach
						</select>
					</div>
					
					<div class="filter-block" id="stay-length">
						<select name="rentalType" onchange="this.form.submit()">
						  <option value="0">Rental Type</option>
						  <option value="1">Daily</option>
						  <option value="2">Monthly</option>
						</select>
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
			</div><!-- Filter -->
			
			
			<div class="prop-list">
        
        
				@foreach($listings as $listing)
				<div class="prop">
					<div class="grid">
						<div class="prop-img" style="background-image: url({{ (isset($listing->url)? $listing->url : '') }});"></div>
						<div class="big-deats">
							<p class="h10">{{ $listing->city }}, {{ $listing->state }}</p>
							<p class="h2">{{ $listing->name }}</p>
							<p class="h11">
								@if($listing->property_type_id == 1)
									Privately Owned
								@elseif($listing->property_type_id == 2)
									Public Park
								@else
									Commercially Owned
								@endif
								<span>Fits {{ $listing->max_vehicle_length }}' RV or smaller</span>
							</p>
							<a href="" class="button love">Save for later</a>
						</div>
						<div class="small-deats">
							@if($listing->month_rental)
								<p class="h8">${{ $listing->month_pricing }} per month</p>
							@endif
							@if($listing->day_rental)
								<p class="h8">${{ $listing->day_pricing }} per night</p>
							@endif
							<div class="rating"><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span></span> 49</div>
							<a href="{{ route('view-listing', ['id' => $listing->listid]) }}" class="button listing">View</a>
						</div>
					</div>
				</div>
				@endforeach
   
    <listing-component
      v-for="listing in listingsList"
      v-bind:listing="listing"
      v-bind:key="listing.id">
    </listing-component>
			</div>
			
		</div>
	</div>
</section>
@endsection


@section('scripts')
<script>
  
    var locations = [
		  @foreach($listings as $listing)
      {
        'id': {{$listing->id}}, 
        'url': '{{ route('view-listing', ['id' => $listing->listid]) }}',
        'image_url': '{{ (isset($listing->url)? $listing->url : '') }}',
        'city': '{{$listing->city}}',
        'state': '{{$listing->state}}',
        'name': '{{$listing->name}}',
        'property_type_id': {{$listing->property_type_id}},
        'max_vehicle_length': '{{$listing->max_vehicle_length}}',
        'month_rental': {{$listing->month_rental}},
        'month_pricing': '{{$listing->month_pricing}}',
        'day_rental': {{$listing->day_rental}},
        'day_pricing': '{{$listing->day_pricing}}',
        'lat': {{$listing->lat}},
        'lng': {{$listing->lng}}
      }
		  @endforeach
    ];


    const listingsApp = new Vue({
        el: '#listing-page',
        data: {
          listingsList: locations
        }
    });
  
  
    function initMap() {

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
        icon: iconBase + 'map-icon.svg'
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i].name);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }       
    
     
        
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCyXHeiC9HRgVmhWkHPyBaM4bM7FC3TuGw&callback=initMap">
    </script>
@endsection