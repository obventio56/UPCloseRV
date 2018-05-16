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
            <h1 class="h2">Address & Directions</h1>
            <p>Are there any special instructions to help your visitors get to your property? Let them know below. Provide your address and explain where they should go when arriving.
</p>
             
                <form class="style lister" method="POST" action="{{ route('store-listing-p5') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ Request::segment(5) }}">
                    
                    <div class="one">
						<p class="h">Address</p>
						<div class="grid">

							<div class="two">
								<label>Address</label>
								<input type="text" name="address" placeholder="123 Appleseed Road" value="{{ (isset($listingAddress->address)? $listingAddress->address : '') }}">
							</div>

							<div class="two">
								<label>City</label>
								<input type="text" name="city" placeholder="Bumblebee" value="{{ (isset($listingAddress->city)? $listingAddress->city : '' ) }}">
							</div>
						</div>
						<div class="grid">

							<div class="two">
								<label>State</label>
								<input type="text" name="state" placeholder="PA" value="{{ (isset($listingAddress->state)?  $listingAddress->state : '') }}">
							</div>

							<div class="two">
								<label>ZIP</label>
								<input type="text" name="zip" placeholder="17000" value="{{ (isset($listingAddress->zipcode)? $listingAddress->zipcode : '') }}">
							</div>
						</div>
					</div>
                    
                    
                    <div class="one">
                        <p class="h">How can travellers find your property?</p>
                        <p></p>

                        <textarea name="drections" placeholder="Take a left off the Route 83 exit..." >{{ $listing->instruct_find }}</textarea>

                        @if ($errors->has('drections'))
                            <span class="help-block">
                                <strong>{{ $errors->first('drections') }}</strong>
                            </span>
                        @endif
                    </div>

                    

                    
                    <div class="one">
                        <p class="h">Where should travellers park upon arrival?</p>
                        <p></p>

                        <textarea name="parkingDirections" placeholder="Park on the left side beside the..." >{{ $listing->instruct_parking }}</textarea>

                        @if ($errors->has('parkingDirections'))
                            <span class="help-block">
                                <strong>{{ $errors->first('parkingDirections') }}</strong>
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
