<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<?php $siteurl = "https://upclose.developingpixels.com"; ?>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="robots" content="noindex" />
	<meta name="googlebot" content="noindex" />
	
    <title>UpClose RV</title>

    <meta name="viewport" content="width=device-width">
	<link type="text/css" href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css" />
	
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css">
	<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.print.css">-->
	
	
	<link rel="stylesheet" href="<?php echo $siteurl; ?>/css/frames.css">
    <link rel="stylesheet" href="https://upclose-front.developingpixels.com/front-end/assets/css/main.css">
    
    <link rel="shortcut icon" href="<?php echo $siteurl; ?>/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo $siteurl; ?>/favicon.ico" type="image/x-icon">

    
</head>
<div class="error-browser">You are seeing a condensed version of this website. Our developers spent countless hours developing this website with the latest industry trends. To fully appreciate this website, we recommend using <a href="https://www.mozilla.org/en-US/firefox/" target="_blank">FireFox</a> or <a href="https://www.google.com/chrome/" target="_blank">Chrome</a> as your main browser. (FYI: IE11 is old & only <a href="https://www.w3counter.com/globalstats.php" target="_blank">2.9% of the world</a> uses it!)</div>

<body>
<header>
	<div class="grid">
		<div class="logo">
			<img src="<?php echo $siteurl; ?>/img/logo.svg" alt="UpClose RV Logo">
		</div>
		
		<nav class="no-mobile">
			<ul>
				<li><a href="#">Travelers</a>
					<ul>
						<li><a href="#" class="h8">How it works</a></li>
						<li><a href="#" class="h8">Travel guide</a></li>
					</ul>
				</li>
				<li><a href="#">Hosts</a></li>
				<li><a href="#">Our Story</a></li>
				@guest
				 <li><a data-fancybox="" data-src="#trueModal" data-modal="true" href="javascript:;" class="sign-under">Log In</a></li>
				@else
					<li><a href="{{ route('home') }}">Your Account</a></li>
				@endguest
				<li><a href="#">Support</a></li>
			</ul>
		</nav>
		
		<nav class="mobile-only">
			<a class="mobile-trigger menu-btn">
				<span></span>
				<span></span>
				<span></span>
			</a>
		</nav>
		
		<form action="{{ route('search') }}" method="GET" class="no-mobile">
			<input type="text" name="search" placeholder="Search by city, zip code">
			<button></button>
		</form>
	</div>
</header>

<!-- Pushy Menu -->
<nav class="pushy pushy-right">
    <div class="pushy-content">
	    <img src="<?php echo $siteurl; ?>/img/x.svg" alt="x" class="pushy-link">
        <ul>
            <!-- Submenu -->
            <li class="pushy-submenu">
                <button>Submenu</button>
                <ul>
                    <li class="pushy-link"><a href="#">Item 1</a></li>
                    <li class="pushy-link"><a href="#">Item 2</a></li>
                    <li class="pushy-link"><a href="#">Item 3</a></li>
                </ul>
            </li>
            <li class="pushy-link"><a href="#">Item 1</a></li>
            <li class="pushy-link"><a href="#">Item 2</a></li>
        </ul>
    </div>
</nav>

<!-- Site Overlay -->
<div class="site-overlay"></div>

    @yield('content')
    
    
    <img src="<?php echo $siteurl; ?>/img/flag.svg" class="flag" alt="Veteran Owned Business">

<footer role="contentinfo">
	<div class="container">
		<div class="grid">
			<div class="social-signin">
				<img src="<?php echo $siteurl; ?>/img/logo.svg" alt="UpClose RV Logo">
				<br/>
				<a href="" class="fab fa-twitter"></a>
				<a href="" class="fab fa-facebook-f"></a>
				<a href="" class="fab fa-youtube"></a>
				<br/>
				<a data-fancybox="" data-src="#trueModal" data-modal="true" href="javascript:;" class="sign-under button round green">Sign In</a>
			</div>
			
			<div class="col">
				<p class="title h10">Company</p>
				<a href="">Our Story</a>
				<a href="">Press</a>
			</div>
			
			<div class="col">
				<p class="title h10">Resources</p>
				<a href="">Support</a>
				<a href="">Host Resources</a>
				<a href="">Traveler Guides</a>
				<a href="">Partners</a>
			</div>
			
			<div class="col">
				<p class="title h10">Extras</p>
				<a href="">Privacy Policy</a>
				<a href="">Terms of Service</a>
			</div>
		</div>
	</div>
	<p class="copy">&copy; 2018 UpClose RV</p>
</footer>


<script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
<script src='<?php echo $siteurl; ?>/js/moment.js'></script>
<script src='<?php echo $siteurl; ?>/js/fullcalendar.min.js'></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
 <script src="<?php echo $siteurl; ?>/js/scripts.js"></script>
<script src="<?php echo $siteurl; ?>/js/dropzone.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.6/owl.carousel.min.js"></script>

@yield('scripts')


<!-- <a data-fancybox="" data-src="#areyousure" data-modal="true" href="javascript:;"></a> -->
<div id="trueModal" class="p-5 fancybox-content" style="display:none;max-width: 900px;">
	<a class="close" data-fancybox-close><img src="<?php echo $siteurl; ?>/img/x.svg"></a>
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


<div id="thank-you" class="p-5 fancybox-content" style="display: none;max-width: 900px;">
	<a class="close" data-fancybox-close><img src="<?php echo $siteurl; ?>/img/x.svg"></a>
	<p class="h4">Thank You!</p>
	<p>Pommy ipsum a diamond geezer chips some mothers do 'ave 'em beefeater oopsy-daisies plum pudding, a cuppa hadn't done it in donkey's years terribly bowler hat conkers pompous. Ridicule ponce bloke knee high to a grasshopper i'll be a monkey's uncle chaps, what a load of cobblers it's cracking flags meat and two veg, scones gravy cheese and chips tally-ho.</p>
</div>

<div id="areyousure" class="p-5 fancybox-content" style="display: none;max-width: 900px;">
	<a class="close" data-fancybox-close><img src="<?php echo $siteurl; ?>/img/x.svg"></a>
	<p class="h4">Are you sure?</p>
	<p>Pommy ipsum a diamond geezer chips some mothers do 'ave 'em beefeater oopsy-daisies plum pudding, a cuppa hadn't done it in donkey's years terribly bowler hat conkers pompous.</p>
	<div class="grid"><a class="two button white round">never-mind, take me back</a> <a class="two button brown round">delete forever</a></div>
</div>

<div id="cancel" class="p-5 fancybox-content" style="display: none;max-width: 900px;">
	<a class="close" data-fancybox-close><img src="<?php echo $siteurl; ?>/img/x.svg"></a>
	<p class="h4">Are you sure you want to cancel this reservation?</p>
	<p>Pommy ipsum a diamond geezer chips some mothers do 'ave 'em beefeater oopsy-daisies plum pudding, a cuppa hadn't done it in donkey's years terribly bowler hat conkers pompous.</p>
	
	<h2 class="h6">Send a message to your host.</h2>
	<form class="style">
		<textarea placeholder="This is my message..."></textarea>
	</form>
	<div class="grid"><a class="two button white round">never-mind, take me back</a> <a class="two button brown round">delete forever</a></div>
</div>

</body>
</html>