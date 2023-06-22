/*const fullCalendarElement = document.querySelector('full-calendar');
var timezone = _s("timezone");
fullCalendarElement.options = {
  timeZone: timezone.tz,

  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,dayGridWeek,dayGridDay'
  },
  events:_s('events'),
}
$(document).ready(function () {
  $('#frm-calendar-event').on('submit', function (e) {
    e.preventDefault();

  });
});*/

document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('full-calendar');
  var timezone = _s("timezone");
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    timeZone: timezone.tz,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,dayGridWeek,dayGridDay,listMonth'
    },
    events:_s('events'),
    eventClick: function(info) {
      info.jsEvent.preventDefault();
      Object.assign(document.createElement("a"), {
				target: "_blank",
				href: info.event.url,
			}).click();
    }
  });
  calendar.render();
});
