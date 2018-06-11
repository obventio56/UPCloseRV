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
            <h1 class="h2">Basic Info</h1>
            <p>Here you will share some basic information about your property regarding type and size. You will also provide a description of what your property is like.</p>
                <form class="style lister" method="POST" action="{{ route('store-listing-p1') }}">
                    {{ csrf_field() }}
                  <input type="hidden" name="id" value="{{ $listing->id }}">  
                    
                <div class="one">
                    <p class="h">Property Name</p>
		            <input type="text" class="two" name="name" placeholder="Wood Creek, Carlisle PA" value="{{ $listing->name }}" required>
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                </div>
                    
                    
                    
                   <div class="one">
                        <p class="h">Property Type</p>
                            <p class="labs">
                            <input type="radio" id="pt1" name="propertyType" value="1"
                                   @if($listing->property_type_id == 1) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="pt1">My property is on private land</label>
                          </p>
                          <p class="labs">
                            <input type="radio" id="pt2" name="propertyType" value="2"                                
                                   @if($listing->property_type_id == 2) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="pt2">My property is a public park</label>
                          </p>
                          <p class="labs">
                            <input type="radio" id="pt3" name="propertyType"  value="3"                                 
                                   @if($listing->property_type_id == 3) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="pt3">My property is commercial land and is managed by me</label>
                          </p>
                           @if ($errors->has('propertyType'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('propertyType') }}</strong>
                                </span>
                           @endif
                    </div>
                    
                    
                    
                    
                    
                    <div class="one">
                        <p class="h">What can renters expect from you?</p>
                            <p class="labs">
                            <input type="radio" id="ht1" name="hostType" value="1"
                                   @if($listing->host_type_id == 1) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="ht1">I am happy to host and be available to renters</label>
                          </p>
                          <p class="labs">
                            <input type="radio" id="ht2" name="hostType" value="2"                                
                                   @if($listing->host_type_id == 2) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="ht2">I would prefer to be hands off during the experience</label>
                          </p>
                           @if ($errors->has('hostType'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('hostType') }}</strong>
                                </span>
                           @endif
                    </div>
					
					
					<div class="one">
                    <p class="h">What types of RVs do you want to host?</p>

                        <div>
							@foreach($rvtypes as $rvtype)
							  <p class="labs">
								<input type="checkbox" id="sl{{ $rvtype->id }}" name="rvTypes[]" value="{{ $rvtype->id }}"
									   @if(isset($listing->rv_types) && in_array($rvtype->id, $listing->rv_types))
								  		checked
								  	   @endif
									   >
								<label for="sl{{ $rvtype->id }}">{{ $rvtype->name }}</label>
							  </p>
							@endforeach
                        </div>

                </div>
					
					
                    
                   <div class="one">
                        <p class="h">What length of vehicle do you want to allow on your property?</p>
                        <label>Enter the number of feet only, we'll take care of the rest</label>
                        <input type="text" class="two" name="vehicleLength" placeholder="45" value="{{ $listing->max_vehicle_length }}">
                            @if ($errors->has('vehicleLength'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('vehicleLength') }}</strong>
                                </span>
                            @endif
                    </div>
                    
                    

                    <div class="one">
                        <p class="h">Describe your property</p>
                        <label>Make sure to explain what a renter could expect when renting from you.</label>

                        <textarea name="description" placeholder="This is my message..." required>{{ $listing->description }} </textarea>

                        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                    </div>
                    
                  <div class="one">  
                    <button class="button round brown">Save</button>
                  </div> 
                    
                    
                </form>
        </div> 

        @component('components.sidebars.dashboard')
			@component('components.sidebars.listings', ['listing' => $listing])
		
			@endcomponent  
		@endcomponent  
	</div>
</section> 
@endsection
