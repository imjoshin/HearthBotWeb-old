$().ready(function() {
	$('.card').on('click', function() {
		var fields = $.parseJSON($(this).attr('data-fields'));

		// Set fields on form
		$.each(fields, function(input, value) {
			input = input.replace('[', '\\[').replace(']', '\\]');
			if ($('[id = ' + input + ']').attr("type") == "checkbox") {
				$('[id = ' + input + ']').prop('checked', value == "on");
			} else {
				$('[id = ' + input + ']').val(value);
			}
		});

		$('.card-container').show();
	});

	$('.btn-add-card').on('click', function() {
		$('.card-container form').trigger('reset');
		$('.card-container').show();
	});

	$('.modal-container-close').on('click', function() {
		$('.modal-container').hide();
	});
});
