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
						<p class="lead">We want to know about your latest booking!</p>
						<p>
							You recently hosted a traveler at {{ $listing->name }} on {{ $booking->end_date }} and we wanted to provide you with the opportunity to rate the traveler who stayed with you!
						</p>
						
						<p>
							Simply click on the link below to rate your experience. 
						</p>
						<a href="{{ route('review-user') }}" class="btn">Review</a>
												
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