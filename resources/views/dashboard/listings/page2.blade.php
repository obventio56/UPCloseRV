@extends('layouts.app')

@section('content')
<section id="admin-dash">
	<div class="grid">
		<div class="content rv">		
			@component('components.breadcrumbs.dashboard')
				  Listings
			@endcomponent
			<br />
			@component('components.menus.dashboard.listing', ['listing' => $listing]) @endcomponent		
            <h1 class="h2">Amenities</h1>
            <p>Share what your property has to offer! What amenities do you have available? What makes your property unique? We've provided some prompts below for you to check off and space for you to provide additional details. </p>
             
                <form class="style lister" method="POST" action="{{ route('store-listing-p2') }}">
                    {{ csrf_field() }}
					<input type="hidden" name="id" value="{{ $listing->id }}"> 
      
                   <div class="one">
                        <p class="h">Outdoor Property Type</p>
                            <p class="labs">
                            <input type="radio" id="pt1" name="propertyType" value="1"
                                   @if($listing->outdoor_property_type_id == 1) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="pt1">My property is only available for parking</label>
                          </p>
                          <p class="labs">
                            <input type="radio" id="pt2" name="propertyType" value="2"                                
                                   @if($listing->outdoor_property_type_id == 2) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="pt2">My property is available for travellers to enjoy</label>
                          </p>
                           @if ($errors->has('propertyType'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('propertyType') }}</strong>
                                </span>
                           @endif
                    </div>
                    
                                    <div class="one">
                    <p class="h">Amenities</p>

                        <div>
							@foreach($amenities as $amenity)
                            <p class="labs">
								<input type="checkbox" name="amenities[]" id="a{{ $amenity->id }}" value="{{ $amenity->id }}"
									   @if(isset($listing->amenities) && in_array($amenity->id, $listing->amenities))
									   checked
										@endif
										>
								<label for="a{{ $amenity->id }}">{{ $amenity->name }}</label>
                          	</p>
							@endforeach
                        </div>

                </div>
					
				<div class="one">
                        <p class="h">Are there any other items on the property that the traveller can make use of?</p>

                        <textarea name="otherAmenities" placeholder="We have a hot tub on the back..." >{{ $listing->other_amenities }}</textarea>

                        @if ($errors->has('otherAmenities'))
                            <span class="help-block">
                                <strong>{{ $errors->first('otherAmenities') }}</strong>
                            </span>
                        @endif
                    </div>
                    
                    
                    
                    <div class="one">
                        <p class="h">Electric Hookup</p>
                            <p class="labs">
                            <input type="radio" id="eh1" name="electricHookup" value="1"
                                   @if($listing->electric_hookup == 1) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="eh1">110 V</label>
                          </p>
                          <p class="labs">
                            <input type="radio" id="eh2" name="electricHookup" value="2"                                
                                   @if($listing->electric_hookup == 2) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="eh2">30 AMP</label>
                          </p>
					      <p class="labs">
                            <input type="radio" id="eh3" name="electricHookup" value="3"                                
                                   @if($listing->electric_hookup == 3) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="eh3">50 AMP</label>
                          </p>
						  <p class="labs">
                            <input type="radio" id="eh4" name="electricHookup" value="0"                                
                                   @if($listing->electric_hookup == 0) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="eh4">No electric hookup</label>
                          </p>
                           @if ($errors->has('electricHookup'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('electricHookup') }}</strong>
                                </span>
                           @endif
                    </div>
                    
					
					<div class="one">
                        <p class="h">Water Hookup</p>
                            <p class="labs">
                            <input type="radio" id="wh1" name="waterHookup" value="1"
                                   @if($listing->water_hookup == 1) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="wh1">Yes</label>
                          </p>
						  <p class="labs">
                            <input type="radio" id="wh4" name="waterHookup" value="0"                                
                                   @if($listing->water_hookup == 0) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="wh4">No water hookup</label>
                          </p>
                           @if ($errors->has('waterHookup'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('waterHookup') }}</strong>
                                </span>
                           @endif
                    </div>
					
					
					<div class="one">
                        <p class="h">Sewer Hookup</p>
                            <p class="labs">
                            <input type="radio" id="sh1" name="sewerHookup" value="1"
                                   @if($listing->sewer_hookup == 1) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="sh1">Yes</label>
                          </p>
						  <p class="labs">
                            <input type="radio" id="sh4" name="sewerHookup" value="0"                                
                                   @if($listing->sewer_hookup == 0) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="sh4">No sewer hookup</label>
                          </p>
                           @if ($errors->has('sewerHookup'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('sewerHookup') }}</strong>
                                </span>
                           @endif
                    </div>
					
                    
                  <div class="one">  
                    <button class="button round brown">Save</button>
                  </div> 
                    
                    
                </form>
        </div>    
        @component('components.sidebars.dashboard')
			@component('components.sidebars.listings')
		
			@endcomponent  
		@endcomponent  
	</div>
</section> 
@endsection
