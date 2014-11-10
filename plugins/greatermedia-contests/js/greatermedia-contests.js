jQuery(function () {

	jQuery('.contest_entry_form').submit(function () {

		var form = this;

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
