<?php 
echo file_get_contents('https://upclose.developingpixels.com/stemp/header?uri=' . urlencode($_SERVER['REQUEST_URI'])); ?>

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
@if(Session::has('error'))
<script>
	$(document).ready(function(){
		// Toasts
		$('#toast.error').fadeIn(1000);
		$('#toast.error').click(function(){
			$(this).fadeOut("slow");
		});
	});
</script>
	<div id="toast" class="error red" style="background-image: url(/img/rvicon.png);"><div id="desc">{{ Session::get('error') }}</div></div>
@endif
@if(Session::has('success'))
<script>
	$(document).ready(function(){
		// Toasts
		$('#toast.success').fadeIn(1000).delay(3000).fadeOut("slow");
	});
</script>
	<div id="toast" class="success green" style="background-image: url(/img/rvicon.png);"><div id="desc">{{ Session::get('success') }}</div></div>
@endif
@if(!$errors->isEmpty())
<script>
	$(document).ready(function(){
		// Toasts
		$('#toast.errors').fadeIn(1000);
		$('#toast.errors').click(function(){
			$(this).fadeOut("slow");
		});
	});
</script>
	<div id="toast" class="errors red" style="background-image: url(/img/rvicon.png);">
		<div id="desc">
		Whoops! Some issues happened:<br />
		@foreach($errors->all() as $message)
		 {{ $message }} <br />
		@endforeach
		</div>
	</div>
@endif