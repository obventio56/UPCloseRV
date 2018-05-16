<?php echo file_get_contents('https://upclose.developingpixels.com/stemp/header'); ?>

<meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('content')


<!-- <a data-fancybox="" data-src="#areyousure" data-modal="true" href="javascript:;"></a> -->
<div id="trueModal" class="p-5 fancybox-content" style="display:none;max-width: 900px;">
	<a class="close" data-fancybox-close><img src="/img/x.svg"></a>
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
	<a id="forgot" href="">Don't have an account?</a>
	<a id="forgot" href="{{ route('password.request') }}">Forgot your password?</a>
</div>

<div id="areyousure" class="p-5 fancybox-content" style="display: none;max-width: 900px;">
	<a class="close" data-fancybox-close><img src="/img/x.svg"></a>
	<p class="h4">Are you sure?</p>
	<p>Pommy ipsum a diamond geezer chips some mothers do 'ave 'em beefeater oopsy-daisies plum pudding, a cuppa hadn't done it in donkey's years terribly bowler hat conkers pompous.</p>
	<div class="grid"><a class="two button white round">never-mind, take me back</a> <a class="two button brown round">delete forever</a></div>
</div>

@yield('popup')

@yield('scripts')

<?php echo file_get_contents('https://upclose.developingpixels.com/stemp/footer'); ?>