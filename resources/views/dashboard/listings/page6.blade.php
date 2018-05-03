@extends('layouts.app')

@section('content')
<section id="admin-dash">
	<div class="grid">
		<div class="content rv">
			@component('components.breadcrumbs.dashboard')
				  Listings
			@endcomponent
			<br />
			
			@component('components.menus.dashboard.listing', ['listing' => $listing]) @endcomponent	
		</div>
		<div class="content wizy">			

            <h1 class="h2">Show off your property with photos!</h1>
            <p>All about my property will be written here. It is the best! You will love staying here. All about my property will be written here. It is the best! You will love staying here. All about my property will be written here. It is the best! You will love staying here. All about my property will be written here. It is the best! You will love staying here. All about my property will be written here. It is the best! You will love staying here.</p>
            
			<div class="one photo-upload">   
                <form class="style lister dropzone" method="POST" action="{{ route('store-listing-p6') }}" id="my-awesome-dropzone">
                    {{ csrf_field() }}
                    <p class="h">Upload Photos</p>
                    <input type="file" name="file" multiple />    
  					<input type="hidden" name="id" value="{{Request::segment(5)}}">
                </form>

             </div>
			
				<div class="one">  
					<button class="button round brown" onclick="location.reload();">
						Save
					</button>
				</div>
			
			@foreach($images as $image)
				<img @if($image->primary) style="border: 4px solid #50CEBE;" @endif src="{{ $image->url }}" />
			
				@if(!$image->primary)
					<div class="make-primary-image">
						<form class="style lister" method="POST" action="{{ route('make-primary-image') }}">
							{{ csrf_field() }}	
							<input type="hidden" name="listing_id" value="{{ $listing->id }}">
							<input type="hidden" name="id" value="{{ $image->id }}">
							<input type="submit" value="Make Primary Image">
						</form>
					</div>
				@endif
						
				<div class="remove-image">
					<form class="style lister" method="POST" action="{{ route('remove-listing-image') }}">
						{{ csrf_field() }}
						<input type="hidden" name="listing_id" value="{{ $listing->id }}">
						<input type="hidden" name="id" value="{{ $image->id }}">
						<input type="submit" value="Remove image" />
					</form>
				</div>
			@endforeach
        </div>    
        @component('components.sidebars.dashboard')
			@component('components.sidebars.listings')
		
			@endcomponent  
		@endcomponent  
	</div>
</section> 
@endsection
