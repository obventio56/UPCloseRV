@extends('layouts.app')

@section('content')
<section id="admin-dash">
	<div class="grid">
		<div class="content rv">
			<p class="h4">Reset Password</p>
				<p></p>
				<form class="style" method="POST" action="{{ route('password.request') }}">
					{{ csrf_field() }}
					<input type="hidden" name="token" value="{{ $token }}">
					<div class="grid">
						@if ($errors->has('email'))
							<span class="help-block">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
						@endif
						<input type="email" class="one" name="email" value="{{ $email or old('email') }}" placeholder="Email" required>

						@if ($errors->has('password'))
							<span class="help-block">
								<strong>{{ $errors->first('password') }}</strong>
							</span>
						@endif
						<input type="password" class="one" name="password" placeholder="Your New Password" required>
						
						@if ($errors->has('password_confirmation'))
							<span class="help-block">
								<strong>{{ $errors->first('password_confirmation') }}</strong>
							</span>
						@endif
						<input type="password" class="one" name="password_confirmation" placeholder="Confirm Password" required>
						
						
						<button class="button brown round">Reset Password</button>
					</div>
				</form>
		</div>
	</div>
</section>
@endsection

