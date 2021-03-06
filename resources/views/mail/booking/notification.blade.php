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
						<h2>You listing has been booked!</h2>
						<p class="lead">The traveler's trip details are below:</p>
						
						<table>
							<tr>
								<td>Location: </td>
								<td>{{ $listing->name }}</td>
							</tr>
							<tr>
								<td> Check in: </td>
								<td>{{ $listing->check_in }} {{ date('M jS Y', strtotime($booking->start_date)) }}</td>
							</tr>
							<tr>
								<td> Check out: </td>
								<td>{{ $listing->check_out }} {{ date('M jS Y', strtotime($booking->end_date)) }}</td>
							</tr>
						</table>
						
						<p>Sign in to your account for more information.</p>
						<a href="{{ route('home') }}" class="btn">Sign In</a>
												
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