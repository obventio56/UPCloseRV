<div class="sider">
				<div class="main-block second">
					@if($listing->verified)
					<span class="verified">Verified</span>
					@endif
					<h3 class="h3">{{ $listing->name }}</h3>
					<img src="{{ $listing->getPrimaryImage() }}" alt="rv pic">
					<h4 class="h10">Details</h4>
					<div class="grid">
						<div class="titles">
							<p><span>Guests:</span> <span>up to @if($booking->days < 30) {{ $listing->day_guests }} @else {{ $listing->month_guests }} @endif</span></p>
							<p><span>Check In:</span> <span>{{ $listing->check_in }} - {{ date('M dS, Y', strtotime($booking->start_date)) }}</span></p>
							<p><span>Check Out:</span> <span>{{ $listing->check_out }} - {{ date('M dS, Y', strtotime($booking->end_date)) }}</span></p>
							<h5 class="h10">Pricing</h5>
							<p><span>Stay:</span> <span>{{ $booking->days }} nights x ${{ $booking->pricing }}/night</p>
						
							@if($booking->discount > 0)
								<p><span>Weeknight Discount:</span> <span>${{ $booking->discount }}</p>
							@endif
							<p><span>Service Fee:</span> <span>${{ $booking->fee }}</span></p>
						</div>
						
					</div>
					
				<p class="total"><span>Total (USD)</span> <span>${{ sprintf("%0.2f",$booking->total) }}</span></p>
				</div>
			</div>