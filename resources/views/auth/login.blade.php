@extends('layouts.app')

@section('content')
<section id="admin-dash">
	<div class="grid">
		<div class="content rv">
			<p class="h4">Sign In</p>
				<p></p>
				<form class="style" method="POST" action="{{ route('login') }}">
					{{ csrf_field() }}
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
						<button class="button brown round">Submit</button>
					</div>
				</form>
				<a id="forgot" href="{{ route('register') }}">Don't have an account?</a>
				<a id="forgot" href="{{ route('password.request') }}">Forgot your password?</a>
		</div>
	</div>
</section>
@endsection
