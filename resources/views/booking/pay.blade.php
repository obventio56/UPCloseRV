@extends('layouts.app')

@section('content')
<section id="profile-listing">
	
	<div class="container">
		<div class="grid">
			<div class="main-sec step wizy">
				<h2>Payment</h2>
				<p>
					You're almost done! Pay for your listing to finalize your booking.
				</p>
				
				<form action="{{ route('finish-payment') }}" method="POST">
					{{ csrf_field() }}
					<input type="hidden" name="booking" value="{{ $booking->id }}">
				  <script
					src="https://checkout.stripe.com/checkout.js" class="stripe-button"
					data-key="pk_test_5Yied8wP8j8xcKIBMTmjwv3u"
					data-amount="{{ ($booking->total * 100) }}"
					data-name="upClose-RV"
					data-description="Widget"
					data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
					data-locale="auto">
				  </script>
				</form>
			</div>
			
			@component('components.sidebars.booking', ['booking' => $booking, 'listing' => $listing])
		
			@endcomponent 
			
			
		</div>
	</div>
	
</section>


@endsection


