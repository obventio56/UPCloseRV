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
				
			<h1 class="h6">Your Reservations</h1>

			<div class="reservation-cards">
			@foreach($bookings as $booking)
				<div class="reservation">
					<p class="dates">{{ date('M dS Y', strtotime($booking->start_date)) }} - {{ date('M dS Y', strtotime($booking->end_date)) }}</p>
					<div class="card">

						<div class="grid">
							<div class="details">
								<p class="h10">Details:</p>
								<p class="dets"><span>Rented By:</span> <span>{{ $booking->name }}</span></p>
								<p class="dets"><span>Check in:</span> <span>{{ date('M dS Y', strtotime($booking->start_date)) }} {{ $listing->check_in }}</span></p>
								<p class="dets"><span>Check out:</span> <span>{{ date('M dS Y', strtotime($booking->end_date)) }} {{ $listing->check_out }}</span></p>
							</div>

							<div class="pricing">
								<p class="h10">Pricing:</p>
								<p class="dets"><span>Stay:</span> <span>5 nights x $50/night = $200.00</span></p>
								<p class="dets total"><span>Total (USD)</span> <span>{{ $booking->amount }}</span></p>
							</div>
						</div>

						<a data-fancybox="" data-src="#cancel-{{ $booking->booking_id }}" data-modal="true" href="javascript:;" class="button brown round">Cancel Reservation</a>
						<a href="{{ route('write', [$booking->user_id]) }}" class="button brown round">Message Traveller</a> 
					</div>
				</div>
			@endforeach
			</div>
		</div><!-- END OF LEFT HAND SIDE -->
		
      	@component('components.sidebars.dashboard')
		
		@endcomponent  
        
	</div>
</section>
@endsection


@section('popup')
	@foreach($bookings as $booking)
	<div id="cancel-{{ $booking->booking_id }}" class="p-5 fancybox-content" style="display: none;max-width: 900px;">
		<a class="close" data-fancybox-close><img src="/img/x.svg"></a>
		<p class="h4">Are you sure you want to cancel this reservation?</p>
		<p>Pommy ipsum a diamond geezer chips some mothers do 'ave 'em beefeater oopsy-daisies plum pudding, a cuppa hadn't done it in donkey's years terribly bowler hat conkers pompous.</p>

		<h2 class="h6">Send a message to your traveller.</h2>
		<form class="style" method="POST" action="{{ route('cancel-booking') }}">
			{{ csrf_field() }}
			<input type="hidden" name="booking_id" value="{{ $booking->booking_id }}">
			<textarea name="message" placeholder="This is my message..."></textarea>

		<div class="grid"> <input type="submit" class="two button brown round" value="confirm cancellation" /></div>
		</form>
		<a class="two button white round">never-mind, take me back</a>
	</div>
	@endforeach
@endsection