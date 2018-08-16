@extends('layouts.app')

@section('content')
<section id="admin-dash">
	<div class="grid">
		<div class="content rv">
			<p class="h4">Reset Password</p>
				<p></p>
				@if (session('status'))
					<div class="alert alert-success">
						{{ session('status') }}
					</div>
				@endif
				<form class="style" method="POST" action="{{ route('password.email') }}">
					{{ csrf_field() }}
					<div class="grid">
						@if ($errors->has('email'))
							<span class="help-block">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
						@endif
						<input type="email" class="one" name="email" value="{{ $email or old('email') }}" placeholder="Email" required>
						
						
						<button class="button brown round">Send Password Reset Link</button>
					</div>
				</form>
		</div>
	</div>
</section>
@endsection
