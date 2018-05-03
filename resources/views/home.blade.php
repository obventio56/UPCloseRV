@extends('layouts.app')

@section('content')
<section id="dash">
	<div class="grid">
		<div class="content rv">		
			<div class="cookie-crisp"><a href="">Dashboard <i class="fas fa-chevron-right"></i></a>  Your Page Title</div>	
			
			<h1 class="h6">Your Profile</h1>
			<!--		YOUR PROFILE -->
			<div class="dropper">
				<form action="{{ route('profile-photo') }}" class="dropzone"  id="my-awesome-dropzone"
					  @if(isset($user->traveller_photo))
						style="background-image: url({{ $user->traveller_photo }}); background-size: cover; background-position: center;"  	
					  @endif
					>
                    {{ csrf_field() }}
				</form>
			</div>
			
			<form method="POST" action="{{ route('update-profile') }}" class="style">
				{{ csrf_field() }}
				<h2>Tell us about yourself</h2>
				
				<div class="grid">
				
					<div class="one">
						<label>Your name</label>
						<input type="text" name="name" value="{{ Auth::user()->name }}">
					</div>
					
					<div class="two">
						<label>Your RV Type</label>
						<select name="rvType">
							@foreach($rvtypes as $type)
						  		<option value="{{ $type->id }}" 
										@if($user->traveller_rv_type_id == $type->id)
											selected
										@endif
									>{{ $type->name }}</option>
							@endforeach
						</select>
					</div>
					
					<div class="two">
						<label>Your RV Size in feet</label>
						<input type="text" name="rvSize" value="{{ $user->traveller_rv_size }}" />
					</div>
					
					<div class="one">
						<label>Let others know a little about you</label>
						<textarea name="description">{{ $user->host_description }}</textarea>
					</div>
				
				</div>
				<input type="submit" value="Save" class="button" >
			</form>

<!--		UPCOMING & PAST RESERVATIONS
		<div class="prop-list">
			
			<div class="outer-prob">
				<p class="date">January 12th 2020</p>
			<div class="propb">
				
				<div class="grid">
					<div class="prop-img" style="background-image: url(/assets/img/vw-rv.jpg);"></div>
					<div class="big-deats">
						<p class="h10">Carlisle, Pennsylvania</p>
						<p class="h2">Woodsy Lot in Carlisle</p>
						<p class="h11">Privately Owned <span>Fits 45' RV or smaller</span></p>
						<p class="checkinout">Check in time: 2:00pm <span>Check out time: 11:00am</span></p>
					</div>
					<div class="small-deats">
						<p class="h8">$150 per night</p>
						<div class="rating"><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span></span> 49</div>
						<a href="" class="button listing">View</a>
					</div>
				</div>
			</div>
			</div>
			
			<div class="outer-prob">
				<p class="date">January 12th 2020</p>
			<div class="propb">
				
				<div class="grid">
					<div class="prop-img" style="background-image: url(/assets/img/vw-rv.jpg);"></div>
					<div class="big-deats">
						<p class="h10">Carlisle, Pennsylvania</p>
						<p class="h2">Woodsy Lot in Carlisle</p>
						<p class="h11">Privately Owned <span>Fits 45' RV or smaller</span></p>
						<p class="checkinout">Check in time: 2:00pm <span>Check out time: 11:00am</span></p>
					</div>
					<div class="small-deats">
						<p class="h8">$150 per night</p>
						<div class="rating"><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span></span> 49</div>
						<a href="" class="button listing">View</a>
					</div>
				</div>
			</div>
			</div>
								
		</div>
-->



<!-- VIEW THE TRIP IN DASHBOARD
<a href="#" class="button brown round">Message Host</a> <a data-fancybox="" data-src="#cancel" data-modal="true" href="javascript:;" class="button brown round">Cancel Reservation</a> 

<div class="main-sec dasher">
				<a class="button love">Save for later</a>
				<div class="rating"><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span></span> 49</div><br/>
				<h1 class="h3">Woodsy Lot in Carlisle</h1><span class="verified">Verified</span>
				<p class="h11">$50 per night | $300 per month</p>
				
				<div class="slide-cont">
				  <div class="owl-carousel">
				    <div><img src="http://imew.co.uk/wp-content/uploads/2017/03/cav1-1.png"></div>
				    <div><img src="http://imew.co.uk/wp-content/uploads/2017/03/cav2-1.png"></div>
				    <div><img src="http://imew.co.uk/wp-content/uploads/2017/03/cav3-1.png"></div>
				    <div><img src="http://imew.co.uk/wp-content/uploads/2017/03/cav4-1.png"></div>
				  </div>
				</div>
				
				<div class="about">
					<h2 class="h10">About This Property</h2>
					<p>All about my property will be written here. It is the best! You will love staying here. All about my property will be written here. It is the best! You will love staying here. All about my property will be written here. It is the best! You will love staying here. All about my property will be written here. It is the best! You will love staying here. All about my property will be written here. It is the best! You will love staying here. </p>
				</div>
				
				<div class="amenities">
					<h3 class="h10">Property Amenities</h3>
					<div class="grid">
						<p>
							<span class="yes">Trash</span>
							<span>WIFI</span>
							<span>Showers</span>
							<span class="yes">Restrooms</span>
						</p>
						
						<p>
							<span class="yes">Fire pit/Fire area</span>
							<span class="yes">Picnic area/Patio</span>
							<span>Playground</span>
							<span>Fishing</span>
							<span>Wildlife watching</span>
							<span>Forest</span>
							<span class="yes">Desert</span>
						</p>
						
						<p>
							<span>Lake</span>
							<span class="yes">Creek</span>
							<span class="yes">River</span>
							<span>Beach</span>
							<span>Swimming hole</span>
							<span class="yes">Swimming pool</span>
						</p>
					</div>
				</div>
				
				<div class="rv-amenities">
					<h3 class="h10">RV Amenities</h3>
					<div class="grid">
						<div>
							<p id="electric">
								Electric Hookup:
								<span>30 AMP</span>
							</p>
							
							<p id="parking">
								Parking Area: 
								<span>Yard/grass</span>
							</p>
						</div>
						
						<div>
							<p id="sewer">
								Sewer Hookup:
								<span>No</span>
							</p>
						</div>
						
						<div>
							<p id="water">
								Water Hookup:
								<span>No</span>
							</p>
						</div>
					</div>
				</div>
				
				<div class="unique-list wizy">
					<h3 class="h10">Unique To This Property</h3>
					<ul>
						<li>Fresh eggs every morning from our free-range chickens</li>
						<li>Fresh eggs every morning from our free-range chickens</li>
						<li>Fresh eggs every morning from our free-range chickens</li>
					</ul>
				</div>
				
				<div class="about-owner">
					<h4 class="h10">About The Owner</h4>
					<div class="grid">
						<div class="g">
							<div class="profile-pic" style="background-image: url(/assets/img/vw-rv.jpg);"></div>
							<p class="name h11">Holly Tritt</p>
							<a class="message h12" href="#">Message Owner</a>
						</div>
						
						<div class="g">
							<p>We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! </p>
						</div>
					</div>
				</div>
				
				
				<div class="other-props">
					<h4 class="h10">See other properties owned by this person</h4>
					<div class="propss">
						<div class="grid">
							<div class="ft-img" style="background-image: url(/assets/img/vw-rv.jpg);"></div>
							<div class="prop-det">
								<p class="h10">Carlisle, Pennsylvania</p>
								<p class="h2">Hamman's Scenic Point</p>
								<p class="h11">Privately Owned <span>Fits 45' RV or smaller</span></p>
								<a href="" class="button listing">View</a>
							</div>
						</div>
					</div>
					
					<div class="propss">
						<div class="grid">
							<div class="ft-img" style="background-image: url(/assets/img/vw-rv.jpg);"></div>
							<div class="prop-det">
								<p class="h10">Carlisle, Pennsylvania</p>
								<p class="h2">Hamman's Scenic Point</p>
								<p class="h11">Privately Owned <span>Fits 45' RV or smaller</span></p>
								<a href="" class="button listing">View</a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="reviews">
					<h5 class="h10">Reviews</h5>
					
					<div class="review">
						<div class="rating"><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span></span></div>
						<p class="rev-content">We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! <span>-Emily Bear</span></p>
					</div>
					
					
					<div class="review">
						<div class="rating"><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span></span></div>
						<p class="rev-content">We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! <span>-Emily Bear</span></p>
					</div>
					
					
					<div class="review">
						<div class="rating"><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span></span></div>
						<p class="rev-content">We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! <span>-Emily Bear</span></p>
					</div>
					
					
					<div class="review">
						<div class="rating"><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span></span></div>
						<p class="rev-content">We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! <span>-Emily Bear</span></p>
					</div>
					
					
					<div class="review">
						<div class="rating"><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span></span></div>
						<p class="rev-content">We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! <span>-Emily Bear</span></p>
					</div>
					
					
					<div class="review">
						<div class="rating"><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span></span></div>
						<p class="rev-content">We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! <span>-Emily Bear</span></p>
					</div>
					
					<div class="review">
						<div class="rating"><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span></span></div>
						<p class="rev-content">We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! <span>-Emily Bear</span></p>
					</div>
					
					
					<a class="button listing more" id="loadMore">More</a>
				</div>
				
				
			</div>
-->

<!-- MESSAGES
	<div class="messages-user">
		<div class="indiv-message-con">
			<div class="grid">
				<div class="img" style="background-image: url(assets/img/vw-rv.jpg);"></div>
				<div class="message-preview">
					<p class="name-of-user h8">Dustin Hoffman <span class="h13">Hey! Have you got any news?</span></p>
				</div>
				
				<div class="details-message">
					<p class="latest-date h12">June 12, 2018 - 8:38am</p>
					<span class="notification">2</span>
					<a class="expand-message h12">Expand <img src="assets/img/down.svg"></a>
				</div>
			</div>
			<div class="convo-thread" style="display: none;">
				<div class="all-messages">
					<div>
						<p>Hi there Mike! We were wondering if it would be alright if we were able to bring our dogs to your property. They are two corgis with the most adorable corgi behinds. Let me know!</p>
						
					</div>
					
					<div class="current">
						<p>Hi there Mike! We were wondering if it would be alright if we were able to bring our dogs to your property. They are two corgis with the most adorable corgi behinds. Let me know!</p>
						<div class="me"><div class="img-off" style="background-image: url(assets/img/vw-rv.jpg);"></div><p>You</p></div>
					</div>
					
					<div>
						<p>Hi there Mike! We were wondering if it would be alright if we were able to bring our dogs to your property. They are two corgis with the most adorable corgi behinds. Let me know!</p>
						<div class="them"><div class="img-off" style="background-image: url(assets/img/vw-rv.jpg);"></div><p>Dustin</p></div>
					</div>
					
					<div class="current">
						<p>Hi there Mike! We were wondering if it would be alright if we were able to bring our dogs to your property. They are two corgis with the most adorable corgi behinds. Let me know!</p>
						<div class="me"><div class="img-off" style="background-image: url(assets/img/vw-rv.jpg);"></div><p>You</p></div>
					</div>
				</div>
				
				<div class="respond">
					<form class="style">
						<textarea placeholder="This is my response..."></textarea>
						<a class="button listing">Send</a>
					</form>
				</div>
				
				<a class="collapse">Collapse</a>
			</div>
		</div>
	</div>
-->

<!-- WRITE A REVIEW
	<div class="write-review">
		<h1 class="h2">Woodys lot in Carlisle</h1>
		<div class="rating"><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span></span> 49</div>
		
		<div class="wizy">
			<h2 class="h2">How many stars do you rate this property?</h2>
			
			<div class="rating submit"><span></span><span></span><span></span><span></span><span></span></div>
			
			<h3 class="h2">Share a review with other travelers</h3>
			
			<form class="style">
				<textarea placeholder="This is my message..."></textarea>
				<button class="button brown round">Submit Review</button>
			</form>
		</div>
		
		
	</div>
-->


<!--  Complete your profile - p33 can be any number based on the completion of the steps. span % should equal p# class
		<div class="complete-your-profile">
			<div class="grid">
				<div class="circle">
					<div class="c100 p33 big">
	                    <span>33%</span>
	                    <div class="slice">
	                        <div class="bar"></div>
	                        <div class="fill"></div>
	                    </div>
	                </div>
				</div>
                <div class="steps">
	                <h2 class="h8">Wait! Remember to finish your profile!</h2>
	                <a class="custom-button h12 disable" href="">Make sure to add/finish your listing! <img src="assets/img/right-white.svg" alt="arrow-right"></a>
	                <a class="custom-button h12" href="">Make sure to add/finish your listing! <img src="assets/img/right-white.svg" alt="arrow-right"></a>
	                <a class="custom-button h12" href="">Make sure to add/finish your listing! <img src="assets/img/right-white.svg" alt="arrow-right"></a>
                </div>
			</div>
		</div>
-->






<!-- PROPERTY bookings dash
<div class="edit-buttons">
	<a href="" class="button green round">Edit Listing</a> <a href="" class="button green round">Manage Reservations</a> <a href="" class="button green round">Edit Availability</a> <a href="" class="button green round">Deactivate Listing</a>
</div>


		<div class="reservation-cards">
			
			<div class="reservation">
				<p class="dates">June 6, 2018 - June 10, 2018</p>
				<div class="card">
					<span class="verified">Verified</span>
					<p class="title h3">Woodsy Lot in Carlisle</p>
					
					<div class="grid">
						<div class="details">
							<p class="h10">Details:</p>
							<p class="dets"><span>Guest:</span> <span>1</span></p>
							<p class="dets"><span>Rented By:</span> <span>Thomas Doyle</span></p>
							<p class="dets"><span>Check in:</span> <span>2:00pm</span></p>
							<p class="dets"><span>Check out:</span> <span>11:00am</span></p>
						</div>
						
						<div class="pricing">
							<p class="h10">Pricing:</p>
							<p class="dets"><span>Stay:</span> <span>5 nights x $50/night = $200.00</span></p>
							<p class="dets"><span>Service Fee:</span> <span>$10.00</span></p>
							<p class="dets total"><span>Total (USD)</span> <span>$260.00</span></p>
						</div>
					</div>
					
					<a href="" class="button brown round">Cancel Listing</a><a href="" class="button brown round">Message Renter</a>
				</div>
			</div>
			
			
			<div class="reservation">
				<p class="dates">June 6, 2018 - June 10, 2018</p>
				<div class="card">
					<span class="verified">Verified</span>
					<p class="title h3">Woodsy Lot in Carlisle</p>
					
					<div class="grid">
						<div class="details">
							<p class="h10">Details:</p>
							<p class="dets"><span>Guest:</span> <span>1</span></p>
							<p class="dets"><span>Rented By:</span> <span>Thomas Doyle</span></p>
							<p class="dets"><span>Check in:</span> <span>2:00pm</span></p>
							<p class="dets"><span>Check out:</span> <span>11:00am</span></p>
						</div>
						
						<div class="pricing">
							<p class="h10">Pricing:</p>
							<p class="dets"><span>Stay:</span> <span>5 nights x $50/night = $200.00</span></p>
							<p class="dets"><span>Service Fee:</span> <span>$10.00</span></p>
							<p class="dets total"><span>Total (USD)</span> <span>$260.00</span></p>
						</div>
					</div>
					
					<a href="" class="button brown round">Cancel Listing</a><a href="" class="button brown round">Message Renter</a>
				</div>
			</div>
			
			
		</div>
-->

<!--
	<div id="cal">
		<div id="calendar"></div>
		
		<div class="key">
			<div class="grey">Reserved</div><div class="black">Unavailable</div><div class="teal">Selected</div>
			
			<a href="#" class="brown button round">Save</a>
		</div>
	</div>


-->



		</div><!-- END OF LEFT HAND SIDE -->
		
      	@component('components.sidebars.dashboard')
		
		@endcomponent  
        
	</div>
</section>
@endsection