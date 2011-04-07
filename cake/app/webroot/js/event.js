$(function() {
  $("#event-start-date").datepicker()
  $("#event-end-date").datepicker()
  $("#event-start-date").datepicker("option", {dateFormat: "yy-mm-dd"})
  $("#event-end-date").datepicker("option", {dateFormat: "yy-mm-dd"})

  $("#event-start-time").timepicker()
  $("#event-end-time").timepicker()
 
  function doSave() {
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
    .success(function(ret) {
      var id = $("#event-id").val() || JSON.parse(ret)
      $.get("/tags/add",{id: id, tags: $("#event-tags").val()})
      switchToMonth(currentYear, currentMonth)
      switchToWeek(currentYear, currentWeek)
    })
  }

  $("#event-create").dialog({
    autoOpen: false,
    height: 593,
    width: 400,
    modal: true,
    buttons: { "Save event": doUpdate
             , "Cancel": function() { 
                 $("#event-create").dialog("close") 
               }
             }
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

    $("#event-create").dialog("open") 
  })

  $("#create-event-button").live("click", function() {
    $("#event-start-date").val("")
    $("#event-start-time").val("")
    $("#event-end-date").val("")
    $("#event-end-time").val("")
    $("#event-name").val("")
    $("#event-description").val("")
    $("#event-location").val("") 
    $("#event-id").val("") 

    $("#event-create").dialog("open")  
  })

  $(".cancel-event-button").live("click", function() {
    var id = $(this).parent().attr("data-event")
    $.get("/events/delete/" + id)
    .success(function() {
      $(this).parent().remove()
      switchToMonth(currentYear, currentMonth)
      switchToWeek(currentYear, currentWeek)
    }) 
  })

  $(".attend-event-button").live("click", function() {
    var id = $(this).parent().attr("data-event")
    $.get("/events/attend/" + id)
    .success(function() {
      showView("month")
    })
  }) 
})
