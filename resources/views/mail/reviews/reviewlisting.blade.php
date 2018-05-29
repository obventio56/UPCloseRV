@extends('layouts.mail')



<!-- BODY -->
<table class="body-wrap">
	<tr>
		<td></td>
		<td class="container" bgcolor="#FFFFFF">

			<div class="content">
			<table>
				<tr>
					<td>
						<!-- A Real Hero (and a real human being) -->
						<p><img src="{{ URL::to('/') }}/img/email.png" width="100%" height="auto" /></p><!-- /hero -->
						<h2>How did it go?</h2>
						<p class="lead">We want to know about your latest experience with upCLOSE-RV!</p>
						<p>
							You recently completed a rental experience at {{ $listing->name }} on {{ $booking->end_date }} and we wanted to provide you with the opportunity to rate the host and property for other users!
						</p>
						
						<p>
							Simply click on the link below and view your previous trips to rate your experience. 
						</p>
						<a href="{{ route('review-listing') }}" class="btn">Review your trip!</a>
												
						<br/>
						<br/>							
												
						
					
					
					</td>
				</tr>
			</table>
			</div>
									
		</td>
		<td></td>
	</tr>
</table><!-- /BODY -->