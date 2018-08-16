@extends('layouts.app')

@section('content')
<section id="dash" class="home-dash">
	<div class="grid">
		<div class="content rv">		
			@component('components.breadcrumbs.dashboard')
				  Your Profile
			@endcomponent
			
			<h1 class="h6">Your Profile</h1>
			<!--		YOUR PROFILE -->
			<div class="dropper">
				<form action="{{ route('profile-photo') }}" class="dropzone"  id="my-awesome-dropzone"
					  @if(isset(Auth::user()->traveller_photo))
						style="background-image: url({{ Auth::user()->traveller_photo }}); background-size: cover; background-position: center;"  	
					  @endif
					>
                    {{ csrf_field() }}
				</form>
			</div>
			
			<form method="POST" action="{{ route('update-profile') }}" class="style">
				{{ csrf_field() }}
				<h2>Tell us about yourself</h2>
				
				<div class="grid">
				
					<div class="one">
						<label>Your name</label>
						<input type="text" name="name" value="{{ Auth::user()->name }}" required>
					</div>
					
					<div class="two">
						<label>Your RV Type</label>
						<select name="rvType">
							@foreach($rvtypes as $type)
						  		<option value="{{ $type->id }}" 
										@if(Auth::user()->traveller_rv_type_id == $type->id)
											selected
										@endif
									>{{ $type->name }}</option>
							@endforeach
						</select>
					</div>
					
					<div class="two">
						<label>Your RV Size in feet</label>
						<input type="number" name="rvSize" placeholder="45" value="{{ Auth::user()->traveller_rv_size }}" />
					</div>
					
					<div class="one">
						<label>Let others know a little about you. This will be shown on your listing's pages if you are a host.</label>
						<textarea name="description">{{ Auth::user()->host_description }}</textarea>
					</div>
				
				</div>
				<input type="submit" value="Save" class="button brown round long" >
			</form>


		</div><!-- END OF LEFT HAND SIDE -->
		
      	@component('components.sidebars.dashboard')
		
		@endcomponent  
        
	</div>
</section>
@endsection