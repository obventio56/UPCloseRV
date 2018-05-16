
@for($i = 0; $i < 5; $i++)
	@if($i < $rating)
		<span class="star"></span>
	@else
		<span></span>
	@endif
@endfor