<div class="edit-buttons">
	<a href="{{ route('manage-listing', $listing->id) }}" class="button green round">Preview</a>
	<a href="{{ route('edit-listing-p1', $listing->id) }}" class="button green round">Edit Listing</a> 
	<a href="{{ route('manage-reservations', $listing->id) }}" class="button green round">Manage Reservations</a> 
	<a href="{{ route('listing-availability', $listing->id) }}" class="button green round">Edit Availability</a> 
	
</div>

<div class="complete-your-profile">
	@if($listing->published)
	<div class="grid">
		<div class="steps">
			<h2 class="h2">
				Your listing is active!
			</h2>
		</div>
		<div class="circle">
			<form action="{{ route('unpublish-listing') }}" method="POST">
			{{ csrf_field() }}
			<input type="hidden" name="id" value="{{ $listing->id }}" />
			<button class="button green round">Deactivate Listing</button>
		</form>
		</div>

	</div>
	@elseif($listing->canPublish())
		<div class="grid">
		<div class="steps">
			<h2 class="h2">
				Your listing can be published!
			</h2>
		</div>
		<div class="circle">
			<form action="{{ route('publish-listing') }}" method="POST">
				{{ csrf_field() }}
				<input type="hidden" name="id" value="{{ $listing->id }}" />
				<button class="button green round" @if(!$listing->canPublish()) disabled="disabled" @endif>Activate Listing</button>
			</form>
		</div>

	</div>

	@else
	<?php 
		$reasons = $listing->whyCantPublish();
		$percentage = array_pop($reasons);
	?>
	
		<div class="grid">
			<div class="circle">
				<div class="c100 p{{ $percentage }} big">
					<span>{{ $percentage }}%</span>
					<div class="slice">
						<div class="bar"></div>
						<div class="fill"></div>
					</div>
				</div>
			</div>
			<div class="steps">
				<h2 class="h8">You are on your way! Here are some of the missing pieces:</h2>
				@foreach($reasons as $reason)
					{!! $reason !!}
				@endforeach
			</div>
		</div>
	@endif
</div>