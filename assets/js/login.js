$().ready(function() {
	$('.login-modal-form input').on('keypress', function (e) {
		if (e.which == 13) {
			$(this).siblings('.btn').click();
		}
	});

	$('.btn-login').on('click', function() {
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "php/ajax.php",
			data: {
				call: 'login',
				form: $('#login').serialize()
			},
			success: function(data) {
				if (data.success) {
					location.reload();
				} else {
					alert(data.output);
				}
			}
		});
	});

	$('.btn-logout').on('click', function() {
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "php/ajax.php",
			data: {
				call: 'logout'
			},
			success: function(data) {
				if (data.success) {
					location.reload();
				} else {
					// TODO do something about it
					alert("Something went wrong!");
					location.reload();
				}
			}
		});
	});
});
