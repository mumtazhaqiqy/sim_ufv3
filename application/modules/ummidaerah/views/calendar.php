<link href='<?=base_url('assets/plugins/fullcalendar/fullcalendar.min.css')?>' rel='stylesheet' />
<script src='<?=base_url('assets/plugins/fullcalendar/moment.js')?>'></script>
<script src='<?=base_url('assets/plugins/fullcalendar/fullcalendar.min.js')?>'></script>


<script>

  $(document).ready(function() {

    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,basicWeek,basicDay'
      },
      defaultDate: '2019-01-12',
      navLinks: true, // can click day/week names to navigate views
      editable: true,
      eventLimit: true, // allow "more" link when too many events
      events: [
        {
          title: 'Sertifikasi Guru | SDIT AL USWATUN HASANAH',
          start: '2019-01-01',
          end: '2019-01-03'
        },
        {
          title: 'Long Event',
          start: '2019-01-07',
          end: '2019-01-10'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2019-01-09T16:00:00'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2019-01-16T16:00:00'
        },
        {
          title: 'Conference',
          start: '2019-01-11',
          end: '2019-01-13'
        },
        {
          title: 'Meeting',
          start: '2019-01-12T10:30:00',
          end: '2019-01-12T12:30:00'
        },
        {
          title: 'Lunch',
          start: '2019-01-12T12:00:00'
        },
        {
          title: 'Meeting',
          start: '2019-01-12T14:30:00'
        },
        {
          title: 'Happy Hour',
          start: '2019-01-12T17:30:00'
        },
        {
          title: 'Dinner',
          start: '2019-01-12T20:00:00'
        },
        {
          title: 'Birthday Party',
          start: '2019-01-13T07:00:00'
        },
        {
          title: 'Click for Google',
          url: 'http://google.com/',
          start: '2019-01-28'
        }
      ]
    });

  });

</script>
<style>
  #calendar {
    max-width: 900px;
    margin: 0 auto;
  }

</style>


<div class="row">
  <div class="col-sm-12">
    <div class="box box-primary">
      <div class="box-body">
        <div id='calendar'></div>

      </div>

    </div>


  </div>

</div>
