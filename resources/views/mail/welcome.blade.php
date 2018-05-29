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
						<p>--Letter from Mike--</p>
						<p class="lead">
							Mike
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