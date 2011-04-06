$(function() {
  $("#event-start-date").datepicker()
  $("#event-end-date").datepicker()
  $("#event-start-date").datepicker("option", {dateFormat: "yy-mm-dd"})
  $("#event-end-date").datepicker("option", {dateFormat: "yy-mm-dd"})

  $("#event-start-time").timepicker()
  $("#event-end-time").timepicker()
 
  $("#create-form").submit(function(e) {
    e.preventDefault()
    var data = {
      start_time: $("#event-start-date").val() + " " + $("#event-start-time").val() + ":00"
    , end_time: $("#event-end-date").val() + " " + $("#event-end-time").val() + ":00"
    , name: $("#event-name").val()
    , description: $("#event-description").val()
    , "location": $("#event-location").val()
    }
    if($("#event-id").val()) {
      var url = "/events/update"
    } else {
      var url = "/events/create"
    }
    $.get(url, data)
    .success(function() {
      switchToMonth(currentYear, currentMonth)
      switchToWeek(currentYear, currentWeek)
    })
  })

  $(".edit-event-button").live("click", function() {
    var id = $(this).parent().attr("data-event")
      , evt = eventRegistry[id]
    start = evt.start_time.split(" ")
    end = evt.end_time.split(" ")
    $("#event-start-date").val(start[0])
    $("#event-start-time").val(start[1])
    $("#event-end-date").val(start[0])
    $("#event-end-time").val(start[1])
    $("#event-name").val(evt.name)
    $("#event-description").val(evt.description)
    $("#event-location").val(evt.location) 
    $("#event-id").val(evt.id) 
      
  })

})
