@extends('layouts.app')

@section('content')

<section id="admin-dash">
	<div class="grid">
		<div class="content">		
			<div class="write-review">
				<h1 class="h2">Your Review</h1>
				<h2 class="h2">
					
				</h2>
				<div class="wizy">

					<form method="POST" action="{{ route('submit-review-user') }}" class="style">
						{{ csrf_field() }}
						<input type="hidden" name="listing" value="{{ $user->id }}" />
						
					<h3 class="h2">How many stars do you rate this property?</h3>
						
					<div class="rating submit"><span></span><span></span><span></span><span></span><span></span></div>
					  <label>
						<input type="radio" name="stars" value="1" />
						<span class="icon">★</span>
					  </label>
					  <label>
						<input type="radio" name="stars" value="2" />
						<span>★</span>
						<span>★</span>
					  </label>
					  <label>
						<input type="radio" name="stars" value="3" />
						<span class="icon">★</span>
						<span class="icon">★</span>
						<span class="icon">★</span>   
					  </label>
					  <label>
						<input type="radio" name="stars" value="4" />
						<span class="icon">★</span>
						<span class="icon">★</span>
						<span class="icon">★</span>
						<span class="icon">★</span>
					  </label>
					  <label>
						<input type="radio" name="stars" value="5" />
						<span class="icon">★</span>
						<span class="icon">★</span>
						<span class="icon">★</span>
						<span class="icon">★</span>
						<span class="icon">★</span>
					  </label>

					<h3 class="h2">Share a review with other travelers</h3>
						<textarea name="review" placeholder="Detail what you did or didn't like about your trip..."></textarea>
						<button class="button brown round">Submit Review</button>
					</form>
				</div>


			</div>
		</div>
		@component('components.sidebars.dashboard')
		
		@endcomponent
	</div>
</section>
	@endsection

@section('scripts')

@endsection