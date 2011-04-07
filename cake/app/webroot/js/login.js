$(function() {

  function doLogin() {
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
        $("#logout").show()
        display()
      }
    })
  }

  $("#login").dialog({
    autoOpen: false
  , height: 260
  , width: 300
  , modal: true
  , buttons: { "login": doLogin
             , "Cancel": function() { 
                 $("#login").dialog("close") 
               }
             }
  })  

  $("#login-button").click(function() {
    $("#login").dialog("open") 
  })

  $("#logout-button").click(function() {
    $.get("/users/logout")
    $("#logged-in-as").hide().html("") 
    $("#logout-button").hide()
    $("#login-button").show()
  })
})
