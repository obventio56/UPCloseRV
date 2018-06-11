@extends('layouts.app')

@section('content')
<section id="admin-dash" class="photo-upload">
	<div class="grid">
		<div class="content rv">
			@component('components.breadcrumbs.dashboard')
				  Listings
			@endcomponent
			<br />
			
			@component('components.menus.dashboard.listing', ['listing' => $listing]) @endcomponent	

		<div class="content wizy">			
            <h1 class="h2">Show off your property with photos!</h1>
            <p>You'll want to share some quality photos of your property so your visitors know what to expect on their arrival. Share photos of where their RV will be parked as well as any special attributes about your property (farm, creek, sightseeing, etc). Please don't upload photos larger than 1MB. We encourage you to add 3-5 photos.</p>
            
			<div class="one photo-upload" id="multi-drop">   
				
				<form class="dropzone needsclick" method="POST" id="property-upload" action="{{ route('store-listing-p6') }}">
					{{ csrf_field() }}
<!-- 					<input type="file" name="file" multiple /> -->    
  					<input type="hidden" name="id" value="{{Request::segment(5)}}">
			      <div class="dz-message needsclick">    
			        Click or drag your photos here! Once done, click save & your photos will be added!
			      </div>
			    </form>
				
				
				
				
				
				
				
<!--
                <form class="style lister dropzone" method="POST" action="{{ route('store-listing-p6') }}" id="my-awesome-dropzone">
                    {{ csrf_field() }}
                    <p class="h">Upload Photos</p>
                    <input type="file" name="file" multiple />    
  					<input type="hidden" name="id" value="{{Request::segment(5)}}">
                </form>
-->

             </div>
			
				<div class="one">  
					<button class="button round brown" onclick="location.reload();">
						Save
					</button>
				</div>
			@foreach($images as $image)
			<div class="image-wrapper">
<!-- 				<img @if($image->primary) style="border: 4px solid #50CEBE;" @endif src="{{ $image->url }}" /> -->
				
				<div class="uploaded-img-container @if($image->primary) primary @endif" style="background-image: url({{ $image->url }})"></div>
			
				@if(!$image->primary)
					<div class="make-primary-image">
						<form class="style lister" method="POST" action="{{ route('make-primary-image') }}">
							{{ csrf_field() }}	
							<input type="hidden" name="listing_id" value="{{ $listing->id }}">
							<input type="hidden" name="id" value="{{ $image->id }}">
							<input type="submit" value="Make Primary Image">
						</form>
					</div>@endif<div class="remove-image">
					<form class="style lister" method="POST" action="{{ route('remove-listing-image') }}">
						{{ csrf_field() }}
						<input type="hidden" name="listing_id" value="{{ $listing->id }}">
						<input type="hidden" name="id" value="{{ $image->id }}">
						<input type="submit" value="Remove image" />
					</form>
				</div>
			</div>
			@endforeach
        </div>    
			
		</div>
        @component('components.sidebars.dashboard')
			@component('components.sidebars.listings', ['listing' => $listing])
		
			@endcomponent  
		@endcomponent  
	</div>
</section> 
@endsection

<script>
	var dropzone = new Dropzone('#property-upload', {
  previewTemplate: document.querySelector('#preview-template').innerHTML,
  parallelUploads: 2,
  thumbnailHeight: 120,
  thumbnailWidth: 120,
  maxFilesize: 1,
  filesizeBase: 1000,
  thumbnail: function(file, dataUrl) {
    if (file.previewElement) {
      file.previewElement.classList.remove("dz-file-preview");
      var images = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
      for (var i = 0; i < images.length; i++) {
        var thumbnailElement = images[i];
        thumbnailElement.alt = file.name;
        thumbnailElement.src = dataUrl;
      }
      setTimeout(function() { file.previewElement.classList.add("dz-image-preview"); }, 1);
    }
  }

});

	</script>
