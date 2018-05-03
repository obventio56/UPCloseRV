@extends('layouts.app')

@section('content')
<section id="admin-dash">
	<div class="grid">
		<div class="content rv">
			@component('components.breadcrumbs.dashboard')
			  Listings
			@endcomponent
			<br />
			@component('components.menus.dashboard.listing', ['listing' => $listing]) @endcomponent
            <div id="cal">
                <div id="calendar"></div>

                <div class="key">
                    <div class="grey">Reserved</div><div class="black">Unavailable</div><div class="teal">Selected</div>

                    <a href="#" class="brown button round">Save</a>
                </div>
            </div>
        </div>    
        @component('components.sidebars.dashboard')
		
		@endcomponent  
	</div>
</section> 


 <div id="test-calendar"></div>

@endsection

@section('scripts')
<script>
$(function() {
    $('#calendar').fullCalendar({
		    selectable: true,
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'month,agendaWeek,agendaDay'
    },
    dayClick: function(date) {
      
    },
    select: function(startDate, endDate) {
    	$('#exception-link').click();
		$('#startDate').val(startDate.format());
		$('#endDate').val(endDate.format());
    },
	eventClick: function(calEvent, jsEvent, view) {
		if(calEvent.type == 'booking'){
			// Redirect to booking link... which doesn't exist yet :D
		} else {
			@foreach($listingExceptions as $exception)
				if('{{ $exception->id }}' == calEvent.id){
					$('#startDateEdit').val(calEvent.start.format());
					$('#endDateEdit').val(calEvent.end.format());
					$('#exceptionIdRemove').val(calEvent.id);
					$('#exceptionIdEdit').val(calEvent.id);
					
					$('#exceptionListingIdEdit').val({{ $listing->id }});
					$('#exceptionListingId').val({{ $listing->id }});
					@if(!$exception->available)
						$('#changePrice').hide();
					@else
						$('#specialPriceEdit').val('{{ $exception->price }}');
					@endif
					$('#exception-edit-link').click();
				}
			@endforeach
		}
	},
    events: [
	@foreach($listingExceptions as $exception)
    {
    	title  : '{{ $exception->title }}',
		type   : 'condition',
		@if($exception->title == 'Special Price')
		className : 'price',
		@else
		className : 'availability',
		@endif
		id	   : '{{ $exception->id }}',
    	start  : '{{ $exception->start_date }}',
		end    : '{{ $exception->end_date }}',
		allDay : true
    },
	@endforeach
	@foreach($bookings as $booking)
    {
    	title  : 'Booking',
		type   : 'booking',
		className : 'booking',
		id	   : '{{ $booking->id }}',
    	start  : '{{ $booking->start_date }}',
		end    : '{{ $booking->end_date }}',
		allDay : true
    },
	@endforeach
  ]
    });
});
    
</script>

<a data-fancybox="" data-src="#exception" data-modal="true" href="javascript:;" id="exception-link"></a>

<div id="exception" class="p-5 fancybox-content" style="display: none;max-width: 900px;">
	<a class="close" data-fancybox-close><img src="{{ URL::to('/') }}/img/x.svg"></a>
	<p class="h4">Add Special Condition</p>
	<p>Modify the availability or price for this date or date range.</p>
	<form class="style" method="POST" action="{{ route('add-exception') }}">
		{{ csrf_field() }}
		<input type="hidden" name="id" value="{{ Request::segment(4) }}">
	<div class="one">
		<p class="h">Dates</p>
		<div class="grid">

			<div class="two">
				<label>Start Date</label>
				<input type="text" id="startDate" name="startDate" placeholder="" class="datepicker">
			</div>

			<div class="two">
				<label>End Date</label>
				<input type="text" id="endDate" name="endDate" placeholder="">
			</div>
		</div>
	</div>
	<div class="one">
		<p class="h">Condition</p>
		<p class="labs">
			<input type="radio" id="na" name="condition" value="not-available" checked>
			<label for="na">Not Available</label>
		  </p>
		  <p class="labs">
			<input type="radio" id="special" name="condition" value="special-price">
			<label for="special">Special Price</label>
		  </p>
		<div class="two">
			<label>Price</label>
			$<input type="text" id="specialprice" class="two" name="price" placeholder="25.00">
		</div>
	</div>
	<div class="grid"><a class="two button white round" data-fancybox-close>Nevermind</a> 
		<button type="submit" class="two button brown round">Add Condition</button></div>
	</form>
</div>


<a data-fancybox="" data-src="#exception-edit" data-modal="true" href="javascript:;" id="exception-edit-link"></a>

<div id="exception-edit" class="p-5 fancybox-content" style="display: none;max-width: 900px;">
	<a class="close" data-fancybox-close><img src="{{ URL::to('/') }}/img/x.svg"></a>
	<p class="h4">Modify Special Condition</p>
	<p>Modify the availability or price for this date or date range.</p>
	<form class="style" method="POST" action="{{ route('edit-exception') }}">
		{{ csrf_field() }}
		<input type="hidden" name="id" id="exceptionIdEdit">
		<input type="hidden" name="listing_id" id="exceptionListingIdEdit">
		<div class="one">
			<p class="h">Dates</p>
			<div class="grid">

				<div class="two">
					<label>Start Date</label>
					<input type="text" id="startDateEdit" name="startDateEdit" placeholder="" class="datepicker">
				</div>

				<div class="two">
					<label>End Date</label>
					<input type="text" id="endDateEdit" name="endDateEdit" placeholder="">
				</div>
			</div>
		</div>
		<div class="one" id="changePrice">
			<p class="h">Condition</p>
			<div class="two">
				<label>Price</label>
				$<input type="text" id="specialPriceEdit" class="two" name="price" placeholder="25.00">
			</div>
		</div>
		<div class="grid">
			<a class="two button white round" data-fancybox-close>Nevermind</a> 
			<button type="submit" class="two button brown round">Save Condition</button>
		</div>
	</form>
	<form class="style" method="POST" action="{{ route('remove-exception') }}">
		{{ csrf_field() }}
		<input type="hidden" id="exceptionIdRemove" name="id">
		<input type="hidden" name="listing_id" id="exceptionListingId">
		<button type="submit" class="two button brown round">Remove Condition</button></div>
	</form>
</div>
@endsection