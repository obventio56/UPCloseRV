@extends('layouts.app')

@section('content')


<section id="profile-listing">
	
	<div class="container">
		<div class="grid">
			<div class="main-sec step wizy">

			<h1 class="h2">Join upCloseRV</h1>
			<a data-fancybox="" data-src="#trueModal" data-modal="true" href="javascript:;" class="sign-under">Already an UpClose member? Click here to login.</a>
			
		
		<form class="style" method="POST" action="{{ route('register') }}">
			{{ csrf_field() }}
			<div class="grid">
				
				<div class="one{{ $errors->has('name') ? ' has-error' : '' }}">
					<label for="first">Name</label>
					<input type="text" placeholder="John Smith" name="name" value="{{ old('name') }}" required autofocus>
					@if ($errors->has('name'))
						<span class="help-block">
							<strong>{{ $errors->first('name') }}</strong>
						</span>
                    @endif
				</div>
				<div class="one{{ $errors->has('email') ? ' has-error' : '' }}">
					<label for="email">Email address</label>
					<input type="email" placeholder="rv@bobrossrv.com" name="email" value="{{ old('email') }}" required>
					@if ($errors->has('email'))
						<span class="help-block">
							<strong>{{ $errors->first('email') }}</strong>
						</span>
					@endif
				</div>
				<div class="two{{ $errors->has('password') ? ' has-error' : '' }}">
					<label for="password">Password</label>
					<input type="password" placeholder="********" name="password" required>
					@if ($errors->has('password'))
						<span class="help-block">
							<strong>{{ $errors->first('password') }}</strong>
						</span>
					@endif
				</div>
				
				<div class="two">
					<label for="comfirmpass">Confirm Password</label>
					<input type="password" placeholder="********" name="password_confirmation" required>
				</div>
				

			</div>
			
			<button class="button brown round">Join</button>
		</form>
			</div>
		</div>
	</div>
</section>
@endsection
