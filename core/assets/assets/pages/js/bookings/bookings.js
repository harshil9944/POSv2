const fullCalendarElement = document.querySelector('full-calendar')

fullCalendarElement.options = {
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,dayGridWeek,dayGridDay'
  },
  events: [
    {
      title: 'Event 1',
      start: '2023-04-16T10:00:00',
      end: '2023-04-16T12:00:00',
    },
    {
      title: 'Event 2',
      start: '2023-04-17T14:00:00',
      end: '2023-04-17T16:00:00',
    },
    {
      title: 'Event 3',
      start: '2023-04-18T08:00:00',
      end: '2023-04-18T10:00:00',
    },
  ],
}
$(document).ready(function () {
  $('#frm-calendar-event').on('submit', function (e) {
    e.preventDefault();

  });
});
