$(function() {
  $("#login").submit(function(e) {
    e.preventDefault()
    var data = {
      username: $("#username").val()
    , pw: $("#pw").val() }

    $.get("/users/login", data)
    .success(function(dat) {
      if(JSON.parse(dat) === false) {
        alert("couldn't log in")
      } else {
        console.log(data.username)
        $("#logged-in-as").show().html(data.username) 
        $("#log-out").show()
        display()
      }
    })
  })
  $("#log-out").click(function() {
    $.get("/users/logout")
    $("#logged-in-as").hide().html("") 
    $("#log-out").hide()
  })
})
