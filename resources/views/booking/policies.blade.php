@extends('layouts.app')

@section('content')
<section id="profile-listing">
	
	<div class="container">
		<div class="grid">
			<div class="main-sec step wizy">
				<h2>Lot Rules</h2>
							
				<p> {{ $listing->rules }} </p>		
			
				<h3>Cancellation Policy</h3>
				
				<p>{{ $language['cancel_'.$listing->cancel_policy] }}</p>
				
				<a href="" class="button round brown">Agree & Continue</a>
			</div>
			
			<div class="sider">
				<div class="main-block second">
					<span class="verified">Verified</span>
					<h3 class="h3">Woodsy Lot in Carlisle</h3>
					<img src="/assets/img/vw-rv.jpg" alt="rv pic">
					<h4 class="h10">Details</h4>
					<div class="grid">
						<div class="titles">
							<p><span>Guest:</span> <span>1</span></p>
							<p><span>Dates:</span> <span>June 6, 2018 - June 10, 2018</span></p>
							
							<h5 class="h10">Pricing</h5>
							<p><span>Stay:</span> <span>5 nights x$50/night</p>
							<p><span>Service Fee:</span> <span>$10.00</span></p>
						</div>
						
					</div>
					
				<p class="total"><span>Total (USD)</span> <span>$260.00</span></p>
				</div>
			</div>
			
			
		</div>
	</div>
	
</section>


@endsection


