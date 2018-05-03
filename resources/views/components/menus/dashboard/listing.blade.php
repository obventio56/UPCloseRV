<div class="edit-buttons">
	<a href="{{ route('manage-listing', $listing->id) }}" class="button green round">Preview</a>
	<a href="{{ route('edit-listing-p1', $listing->id) }}" class="button green round">Edit Listing</a> 
	<a href="" class="button green round">Manage Reservations</a> 
	<a href="{{ route('listing-availability', $listing->id) }}" class="button green round">Edit Availability</a> 
	@if($listing->published)
		<a href="" class="button green round">Deactivate Listing</a>
	@else
		
		<a href="" class="button green round" @if(!$listing->canPublish()) disabled="disabled" @endif >Activate Listing</a>
	@endif
</div>