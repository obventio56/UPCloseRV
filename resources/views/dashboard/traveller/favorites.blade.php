@extends('layouts.app')

@section('content')
<section id="dash">
	<div class="grid">
		<div class="content rv">		
			@component('components.breadcrumbs.dashboard')
				  Saved Listings
			@endcomponent
			<h1 class="h6">Saved Listings</h1>
	<div class="prop-list">
			
		<div class="outer-prob">
			
            @foreach($listings as $listing)
			<div class="propb">
				
				<div class="grid">
					<div class="prop-img" style="background-image: url(http://upclose-front.developingpixels.com/front-end/assets/img/vw-rv.jpg);"></div>
					<div class="big-deats">
						<p class="h10">Carlisle, Pennsylvania</p>
						<p class="h2">{{ $listing->name }}</p>
						<p class="h11">Privately Owned <span>Fits {{ $listing->max_vehicle_length }}' RV or smaller</span></p>
						<p class="checkinout"></p>
					</div>
					<div class="small-deats">
                    @if(isset($listing->day_pricing))
						<p class="h8">${{ $listing->day_pricing }} per night</p>
                    @endif
                    @if(isset($listing->month_pricing))
                        <p class="h8">${{ $listing->month_pricing }} per month</p>
                    @endif
						<div class="rating"><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span></span> 49</div>
						<a href="{{ route('view-listing', $listing->id) }}" class="button listing">View</a>
					</div>
				</div>
			</div>
            @endforeach
		</div>				
	</div>

		</div><!-- END OF LEFT HAND SIDE -->
		
      	@component('components.sidebars.dashboard')
		
		@endcomponent  
        
	</div>
</section>
@endsection