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
						<h2>Someone wants to talk to you!</h2>
						<p class="lead">You have a new message from {{ Auth::user()->name }}.</p>

						
						<p>
							Sign in to your account to reply!
						</p>
						<a href="{{ route('messages') }}" class="btn">Go to messages</a>
												
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