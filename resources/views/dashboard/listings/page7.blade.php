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

		<div class="content wizy">		
			<h1 class="h2">What's close by?</h1>
			<p>
				Let your visitors know what they can do near your property! Are there great areas for hiking? Delicious restaurants? Educational museums? 
				Family-friendly parks? Also include practical places like grocery stores, gas stations, etc so your visitors know what is available to them near by.
			</p>
		<form class="style lister" method="POST" action="{{ route('store-listing-p7') }}">
			{{ csrf_field() }}
            <input type="hidden" name="id" value="{{ Request::segment(5) }}">
			
			<div id="two_p_scents" class="one">
				<p class="h">Are there any attractions in your area?</p>

				<div id="conts">
					@if(isset($attractions))
						@foreach($attractions as $attraction)
							<input type="text" name="nearby[]" class="two" value="{{ $attraction->attraction }}" required>
							<input type="text" name="location[]" class="two" value="{{ $attraction->location }}" required>
						@endforeach
					@else
						<input type="text" name="nearby[]" class="two" placeholder="State Park" required>
						<input type="text" name="location[]" class="two" placeholder="10 Miles Away" required>
					@endif
				</div>

				<a class="addmore" id="addScnt-l"><span>+</span> Add another</a>
			</div>  
			
			
			<div id="two_p_scents" class="one">
				<p class="h">Are there any RV conveniences in your area?</p>

				<div id="conts2">
					@if(isset($conveniences))
						@foreach($conveniences as $convenience)
							<input type="text" name="nearby2[]" class="two" value="{{ $convenience->attraction }}" required>
							<input type="text" name="location2[]" class="two" value="{{ $convenience->location }}" required>
						@endforeach
					@else
						<input type="text" name="nearby2[]" class="two" placeholder="Dumping Station" required>
						<input type="text" name="location2[]" class="two" placeholder="10 Miles Away" required>
					@endif
				</div>

				<a class="addmore" id="addScnt-2"><span>+</span> Add another</a>
			</div> 
           
              <div class="one">  
                <button class="button round brown">Save</button>
              </div> 
		</form>
        </div> 
					</div>
        @component('components.sidebars.dashboard')
			@component('components.sidebars.listings')
		
			@endcomponent  
		@endcomponent  
	</div>
</section> 
@endsection

@section('scripts')
<script>
	$(document).ready(function(){
		$("#addScnt-l").click(
		  function () {
			 var someText = "<input type=\"text\"  name=\"nearby[]\" class=\"two\" placeholder=\"Another Park\" required> <input type=\"text\" name=\"location[]\" class=\"two\" placeholder=\"10 Miles Away\" required> <button class=\"removeFeature\"></button>";
			 var newDiv = $("<div>").append(someText);
			 $('#conts').append(newDiv);
		  }
		); 
		$("#addScnt-2").click(
		  function () {
			 var someText = "<input type=\"text\"  name=\"nearby2[]\" class=\"two\" placeholder=\"Dumping Station\" required> <input type=\"text\" name=\"location2[]\" class=\"two\" placeholder=\"10 Miles Away\" required> <button class=\"removeFeature\"></button>";
			 var newDiv = $("<div>").append(someText);
			 $('#conts2').append(newDiv);
		  }
		); 
    $("#conts .removeFeature, #conts2 .removeFeature").click( function() {
      console.log("above")
      $(this).parent().remove();
    });
	});
</script>
@endsection
