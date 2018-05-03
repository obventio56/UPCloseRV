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
            <h1 class="h2">Rules & Policies</h1>
            <p> </p>
             
                <form class="style lister" method="POST" action="{{ route('store-listing-p3') }}">
                    {{ csrf_field() }}
                     <input type="hidden" name="id" value="{{ $listing->id }}">  
                    
                    <div class="one">
                        <p class="h">Do you have any rules or guidelines that renter should know about before booking?</p>
                        <p></p>

                        <textarea name="rules" placeholder="No running around the pool..." >{{ $listing->rules }}</textarea>

                        @if ($errors->has('rules'))
                            <span class="help-block">
                                <strong>{{ $errors->first('rules') }}</strong>
                            </span>
                        @endif
                    </div>

                    
                    <div class="one">
                        <p class="h">Check In & Check Out</p>
                        <div class="grid">

                            <div class="two">
                                <label>Check In</label>
                                <input type="text" name="checkin" value="{{ $listing->check_in }}" placeholder="3:00pm">
                            </div>

                            <div class="two">
                                <label>Check Out</label>
                                <input type="text" name="checkout" value="{{ $listing->check_out }}" placeholder="11:00am">
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    <div class="one">
                        <p class="h">Do you have any special instructions for check-in and check-out?</p>
                        <p></p>

                        <textarea name="cirules" placeholder="Please leave the keys on the table..." >{{ $listing->checkin_rules }}</textarea>

                        @if ($errors->has('cirules'))
                            <span class="help-block">
                                <strong>{{ $errors->first('cirules') }}</strong>
                            </span>
                        @endif
                    </div>
                    
                   <div class="one">
                        <p class="h">Do you allow pets?</p>
                            <p class="labs">
                            <input type="radio" id="p1" name="pets" value="1"
                                   @if($listing->pets_allowed == 1) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="p1">Yes</label>
                          </p>
                          <p class="labs">
                            <input type="radio" id="p2" name="pets" value="0"                                
                                   @if($listing->pets_allowed == 0) 
                                     checked="checked"
                                   @endif
                                >
                            <label for="p2">No</label>
                          </p>
                           @if ($errors->has('pets'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('pets') }}</strong>
                                </span>
                           @endif
                    </div>
                    
                    
                    

                <div class="one">
                    <p class="h">Please select a cancellation policy</p>
                        <p class="labs">
                        <input type="radio" id="cp1" name="cancelPolicy" value="1"
                               @if($listing->cancel_policy == 1) 
                                 checked="checked"
                               @endif
                            >
                        <label for="cp1">Strict</label>
                      </p>
                      <p class="labs">
                        <input type="radio" id="cp2" name="cancelPolicy" value="2"                                
                               @if($listing->cancel_policy == 2) 
                                 checked="checked"
                               @endif
                            >
                        <label for="cp2">Middle policy</label>
                      </p>
					  <p class="labs">
                        <input type="radio" id="cp3" name="cancelPolicy" value="3"                                
                               @if($listing->cancel_policy == 3) 
                                 checked="checked"
                               @endif
                            >
                        <label for="cp3">Relaxed policy</label>
                      </p>
                       @if ($errors->has('cancelPolicy'))
                            <span class="help-block">
                                <strong>{{ $errors->first('cancelPolicy') }}</strong>
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
