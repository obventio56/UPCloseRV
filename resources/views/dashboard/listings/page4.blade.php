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
            <h1 class="h2">Pricing</h1>
            <p>Your property, your pricing. When setting your pricing, consider the value of your property (what's near by, what's available on your property, etc) as well as how much profit you'd like to make. You can set nightly and monthly rates as well as discounts below. </p>
             
                <form class="style lister" method="POST" action="{{ route('store-listing-p4') }}">
                    {{ csrf_field() }}
                     <input type="hidden" name="id" value="{{ $listing->id }}">  
                    
                    <div class="one">
                    <p class="h">Stay Lengths</p>

                        <div>
                            <p class="labs">
                            <input type="checkbox" name="dayRental" id="sl1" value="1"
								@if($listing->day_rental == 1)
									checked
								@endif
								>
                            <label for="sl1">Nightly</label>
                          </p>
                          <p class="labs">
                            <input type="checkbox" name="monthRental" id="sl2" value="1"
								@if($listing->month_rental == 1)
									checked
								@endif
								>
                            <label for="sl2">Monthly</label>
                          </p>
                        </div>

                </div>
                    
                <div class="one">
                    <p class="h">Max Stay Length</p>
                    <label>in days</label>
		            <input type="text" class="two" name="maxStayLength" placeholder="45" value="{{ $listing->max_stay_length }}">
                        @if ($errors->has('maxStayLength'))
                            <span class="help-block">
                                <strong>{{ $errors->first('maxStayLength') }}</strong>
                            </span>
                        @endif
                </div>
                    
  
                    <div class="one">
                        <p class="h">How much is your listing for 1 night?</p>
                        <div class="grid">

                            <div class="two">
                                <label>Price</label>
                                $<input type="text" name="dayPricing" value="{{ $listing->day_pricing }}" placeholder="25">
                            </div>

                            <div class="two">
                                <label>For up to how many renters?</label>
                                <input type="text" name="dayGuests" value="{{ $listing->day_guests }}" placeholder="6">
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="one">
                        <p class="h">How much is your listing for 1 month?</p>
                        <div class="grid">

                            <div class="two">
                                <label>Price</label>
                                $<input type="text" name="monthPricing" value="{{ $listing->month_pricing }}" placeholder="500">
                            </div>

                            <div class="two">
                                <label>For up to how many renters?</label>
                                <input type="text" name="monthGuests" value="{{ $listing->month_guests }}" placeholder="6">
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="one">
                        <p class="h">Is there a discount for weeknights?</p>
						<p>
							Weeknights are Sunday night through Thursday night.
						</p>
                        <select name="weeknightDiscount">
                          <option value="0" @if($listing->weeknight_discount == 0) selected @endif >No Discount</option>
                          <option value="5" @if($listing->weeknight_discount == 5) selected @endif >5% Discount</option>
                          <option value="10" @if($listing->weeknight_discount == 10) selected @endif >10% Discount</option>
                          <option value="15" @if($listing->weeknight_discount == 15) selected @endif >15% Discount</option>
                        </select>

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
