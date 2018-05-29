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
						<h2>You're booking has been cancelled...</h2>
						<p class="lead"> The booking for {{ $booking->start_date }} at {{ $listing->name }} has been cancelled.</p>
						<p>
							The booking was subject to the <b>{{ $booking->cancel_policy }}</b> policy. You can learn more about this policy <a href="https://upclose.developingpixels.com/policies/our-cancellation-policies">here</a>.
						</p>					
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