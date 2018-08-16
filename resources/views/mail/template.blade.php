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
						<h2>Large header text</h2>
						<p class="lead">Slightly larger intro text.</p>
						
						
						
						<!-- Callout Panel -->
						<p class="callout">This is a call to action box! Add call to action things in here to make theme stand out! <a href="#">Do it Now! &raquo;</a></p><!-- /Callout Panel -->
						
						<h3>Smaller header text</h3>
						<p>This is the typical paragraph text. Normal content or text will go here.</p>
						<a href="{{ route('home') }}" class="btn">Button!</a>
												
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