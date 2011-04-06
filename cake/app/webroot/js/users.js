$(function() {
  var userView = new LiveView("#calendar-user", {})
  $(".attendees .name, .ownername").live("click", function(e) {
    var id = $(this).attr("data-user")
      , user = userRegistry[id]

    userView.set(user)
    showView("user")
  })
})
