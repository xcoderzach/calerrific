	function display() {
		$.ajax({url: '/users/id',
				success: function (id) {
					var userId = JSON.parse(id)
					$.ajax({url: '/users?id=' + userId,
							success: function (res) {
								var props = JSON.parse(res)
								$("#name").attr('value', props['name'])
								$("#position").attr('value', props['position'])
								$("#email").attr('value', props['email'])
								$("#title").attr('value', props['title'])
								$("#department").attr('value', props['department'])
								$("#id").attr('value', userId)
							}})
				}})
	}

function doUpdate() {
  var pw = $("#password").val()
  var cpw = $("#confirm_password").val()

  if(pw !== cpw) {
    alert("Passwords do not match")
    $("#password").val("")
    $("#confirm_password").val("")
    return
  }

  var data = {
    name: $("#name").attr('value'),
    position: $("#position").attr('value'),
    email: $("#email").attr('value'),
    title: $("#title").attr('value'),
    department: $("#department").attr('value'),
    pw: $("#password").attr('value'),
    id: $("#id").attr('value')
  };

  $("#password").val("")
  $("#confirm_password").val("")
  
  $.ajax({url: '/users/update',
    data: data,
    success: display,
    error: display
  });
  $("#event-create").dialog("close") 
}

$(function () {
  $("#user-edit").dialog({
    autoOpen: false,
    height: 510,
    width: 400,
    modal: true,
    buttons: {"Save profile": doUpdate,
              "Cancel": function() { $("#user-edit").dialog("close") }}
  })
  $("#edit-user-info").click(function() {
    $("#user-edit").dialog("open")
  })
});
