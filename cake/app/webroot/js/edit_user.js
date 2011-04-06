	function display() {
		$.ajax({url: '/users/id',
				success: function (id) {
					var userId = JSON.parse(id);
					$.ajax({url: '/users?id=' + userId,
							success: function (res) {
								var props = JSON.parse(res);
								$("#name").attr('value', props['name']);
								$("#position").attr('value', props['position']);
								$("#email").attr('value', props['email']);
								$("#title").attr('value', props['title']);
								$("#department").attr('value', props['department']);
								$("#id").attr('value', userId);
							}});
				}});
	}

$(function () {
  $("#submit").click(function () {
    var data = {
      name: $("#name").attr('value'),
      position: $("#position").attr('value'),
      email: $("#email").attr('value'),
      title: $("#title").attr('value'),
      department: $("#department").attr('value'),
      id: $("#id").attr('value')
    };
    
    $.ajax({url: '/users/update',
        data: data,
        success: display,
        error: display
         });
  });
});