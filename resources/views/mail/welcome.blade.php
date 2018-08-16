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
						<h2>Woohoo!</h2>
						<h2>
							Welcome to the upCLOSE-RV family.
						</h2>
						<p>People from all walks of life and generations are discovering the joys of vacationing, living, and traveling in Recreational Vehicles. Whether Guest or Host we are glad 
							that you have decided to join our ever growing family. If you have questions, please check out our helpful resource pages for 
							<a href="{{ URL::to('/') }}property-owners">hosts</a> or 
							<a href="{{ URL::to('/') }}for-travelers">travelers</a>. </p>
						<p>
							We look forward to seeing you on the road!
						</p>
						<p>
							As always, celebrate life and get upCLOSE.
						</p>
						<p class="lead">
							Mike Lockwood, CEO
						</p>
	
						<h3>What now?</h3>
						<p>Sign in to finish filling out your profile. Then, you're ready to either book your trip or list your property!</p>
						<a href="{{ route('home') }}" class="btn">Sign in</a>
												
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