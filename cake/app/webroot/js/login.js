var currentUser
$(function() {
  function getUserInfo() {
    $.get("/users/id", function(data) {
      currentUser = JSON.parse(data)
      if(currentUser) {
        $(document.body).addClass("user-" + currentUser)
        $(document.body).addClass("logged-in")
        $(document.body).removeClass("logged-out")
      }
    })
  }
  getUserInfo()

  function doLogin() {
    $("#login .errors").hide()
    var data = {
      username: $("#username").val()
    , pw: $("#pw").val() }

    $.get("/users/login", data)
    .success(function(dat) {
      if(!JSON.parse(dat)) {
        $("#login .errors").show()
      } else {
        $(document.body).addClass("logged-in")
        $(document.body).removeClass("logged-out")

        getUserInfo()
        $("#logged-in-as").show().html(data.username) 
        $("#logout").show()
        display()
        $("#login").dialog("close") 
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
    $(document.body).addClass("logged-out")
    $(document.body).removeClass("logged-in")
    $(document.body).removeClass("user-" + currentUser)
    currentUser = null
    $("#login").dialog("close") 
  })
})
