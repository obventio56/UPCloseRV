@extends('layouts.app')

@section('content')
<section id="profile-listing">
	
	<div class="container">
		<div class="grid">
			<div class="main-sec step wizy">
				
				@if(Auth::guest())
				<h2>
					Login
				</h2>
				<form class="style" method="POST" action="{{ route('login') }}">
					{{ csrf_field() }}
					<input type="hidden" name="redirectUrl" value="{{ Request::url() }}">
					<div class="grid">
						@if ($errors->has('email'))
							<span class="help-block">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
						@endif
						<input type="email" class="two" name="email" value="{{ old('email') }}" placeholder="Email" required>

						@if ($errors->has('password'))
							<span class="help-block">
								<strong>{{ $errors->first('password') }}</strong>
							</span>
						@endif
						<input type="password" class="two" name="password" placeholder="Password" required>
						<button class="button brown round">Login</button>
					</div>
				</form>
				
				<h3>
					or Register for a new account
				</h3>
					<form class="style" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}
					<input type="hidden" name="redirectUrl" value="{{ Request::url() }}">

						<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Name" required>

						@if ($errors->has('name'))
							<span class="help-block">
								<strong>{{ $errors->first('name') }}</strong>
							</span>
						@endif

                            
						<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required>

						@if ($errors->has('email'))
							<span class="help-block">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
						@endif

              
						<input id="password" type="password" class="form-control" name="password" placeholder="Password" required>

						@if ($errors->has('password'))
							<span class="help-block">
								<strong>{{ $errors->first('password') }}</strong>
							</span>
						@endif
      
						<input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>

						<button type="submit" class="button brown round">
							Register
						</button>
                    </form>
				
				@else
					<h2>Lot Rules</h2>

					<p> {{ $listing->rules }} </p>		

					<h3>Cancellation Policy</h3>

					<p>{{ $language['cancel_'.$listing->cancel_policy] }}</p>

					<form method="POST" action="{{ route('confirm-booking') }}">
						{{ csrf_field() }}
						<input type="hidden" name="booking" value="{{ $booking->id }}" />
						<button class="button round brown long">Agree & Continue</button>
					</form>
				@endif
				
			</div>
			
		@component('components.sidebars.booking', ['booking' => $booking, 'listing' => $listing])
		
		@endcomponent 
			
			
		</div>
	</div>
	
</section>


@endsection


