<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css">
	<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.print.css">-->
    <style>
html, body {
  margin: 0;
  padding: 0;
  font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
  font-size: 14px;
}

#calendar {
  max-width: 900px;
  margin: 40px auto;
}
</style>
</head>

<body>
    


<section id="admin-dash">
	<div class="grid">
		<div class="content wizy">	
            <div id="cal">
                <div id="calendar"></div>

                <div class="key">
                    <div class="grey">Reserved</div><div class="black">Unavailable</div><div class="teal">Selected</div>

                    <a href="#" class="brown button round">Save</a>
                </div>
            </div>
        </div>    
	</div>
</section> 


 <div id="test-calendar"></div>


<script src='{{ URL::to('/') }}/js/moment.js'></script>
<script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
<script src='{{ URL::to('/') }}/js/fullcalendar.min.js'></script>

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
      alert('clicked ' + date.format());
    },
    select: function(startDate, endDate) {
      alert('selected ' + startDate.format() + ' to ' + endDate.format());
    }
  });

});
    
</script>
</body>