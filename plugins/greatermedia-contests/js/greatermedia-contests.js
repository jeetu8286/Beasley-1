jQuery(function () {

	// Attach a submit handler to contest forms
	jQuery('.contest_entry_form').submit(function () {

		var form = this;

		// Submit the form via AJAX
		jQuery.post(
			GreaterMediaContests.ajax_url,
			jQuery(this).serialize(),
			function (data, textStatus, jqXHR) {

				if ('success' === textStatus && data.data.message) {
					var wrapper = document.createElement('p');
					wrapper.class = 'contest_thank_you';
					wrapper.innerText = data.data.message;
					jQuery(form).replaceWith(wrapper);
				}

			}
		);

		return false;

	});

});
