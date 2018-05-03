@extends('layouts.app')

@section('content')

	<section id="dash">
		<div class="grid">
			<div class="content rv">		
				@component('components.breadcrumbs.dashboard')
					  Listings
				@endcomponent
				<br />
				@component('components.menus.dashboard.listing', ['listing' => $listing]) @endcomponent
				
				<div class="main-sec dasher">
				<div class="rating"><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span></span> 49</div><br>
				<h1 class="h3">Woodsy Lot in Carlisle</h1>@if($listing->verified)<span class="verified">Verified</span>@endif
				<p class="h11">$50 per night | $300 per month</p>
				
				<div class="slide-cont">
				  <div class="owl-carousel owl-loaded owl-drag">
				    
				    
				    
				    
				  <div class="owl-stage-outer"><div class="owl-stage" style="transform: translate3d(0px, 0px, 0px); transition: 0s; width: 4006px;"><div class="owl-item slidenumber1 active" style="width: 1001.5px;"><div><img src="http://imew.co.uk/wp-content/uploads/2017/03/cav1-1.png"></div></div><div class="owl-item slidenumber2" style="width: 1001.5px;"><div><img src="http://imew.co.uk/wp-content/uploads/2017/03/cav2-1.png"></div></div><div class="owl-item slidenumber3" style="width: 1001.5px;"><div><img src="http://imew.co.uk/wp-content/uploads/2017/03/cav3-1.png"></div></div><div class="owl-item slidenumber4" style="width: 1001.5px;"><div><img src="http://imew.co.uk/wp-content/uploads/2017/03/cav4-1.png"></div></div></div></div><div class="owl-nav"><div class="owl-prev disabled"><i class="fa fa-chevron-left"></i></div><div class="owl-next"><i class="fa fa-chevron-right"></i></div></div><div class="owl-dots"><div class="owl-dot dotnumber1 active" data-info="1" style="background-image: url(&quot;http://imew.co.uk/wp-content/uploads/2017/03/cav1-1.png&quot;); width: 12.5%; height: 122.844px;"><span></span></div><div class="owl-dot dotnumber2" data-info="2" style="background-image: url(&quot;http://imew.co.uk/wp-content/uploads/2017/03/cav2-1.png&quot;); width: 12.5%; height: 122.844px;"><span></span></div><div class="owl-dot dotnumber3" data-info="3" style="background-image: url(&quot;http://imew.co.uk/wp-content/uploads/2017/03/cav3-1.png&quot;); width: 12.5%; height: 122.844px;"><span></span></div><div class="owl-dot dotnumber4" data-info="4" style="background-image: url(&quot;http://imew.co.uk/wp-content/uploads/2017/03/cav4-1.png&quot;); width: 12.5%; height: 122.844px;"><span></span></div></div></div>
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
							<div class="profile-pic" style="background-image: url(http://upclose-front.developingpixels.com/front-end/assets/img/vw-rv.jpg);"></div>
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
							<div class="ft-img" style="background-image: url(http://upclose-front.developingpixels.com/front-end/assets/img/vw-rv.jpg);"></div>
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
							<div class="ft-img" style="background-image: url(http://upclose-front.developingpixels.com/front-end/assets/img/vw-rv.jpg);"></div>
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
					
					<div class="review" style="display: block;">
						<div class="rating"><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span></span></div>
						<p class="rev-content">We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! <span>-Emily Bear</span></p>
					</div>
					
					
					<div class="review" style="display: block;">
						<div class="rating"><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span></span></div>
						<p class="rev-content">We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! We love living in central PA. Our house is sandwiched between a corn field and a large creek. Enjoy! <span>-Emily Bear</span></p>
					</div>
					
					
					<div class="review" style="display: block;">
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
				
				
				
		</div><!-- END OF LEFT HAND SIDE -->
		
      	@component('components.sidebars.dashboard')
		
		@endcomponent  
        
		</div>
	</section>
@endsection

