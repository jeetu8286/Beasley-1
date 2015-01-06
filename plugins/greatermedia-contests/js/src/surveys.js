jQuery(function () {

	// Attach a submit handler to survey forms
	jQuery( '.' + GreaterMediaSurveys.form_class ).submit(function (e) {
		var form = this;
		e.preventDefault();
		if (!jQuery(this).parsley || jQuery(this).parsley().isValid()) {

			// Marshall the data
			var form_data = new FormData();
			jQuery(this).find('input').each(function () {
				var input = this;
				if ('file' === input.type) {
					jQuery(this.files).each(function (key, value) {
						form_data.append(input.name, value);
					});
				}
				else {
					form_data.append(input.name, input.value);
				}
			});

			jQuery(this).find('textarea').each(function () {
				form_data.append(this.name, this.value);
			});

			// Submit the form via AJAX
			jQuery.ajax({
				url        : GreaterMediaSurveys.ajax_url,
				type       : 'post',
				data       : form_data,
				processData: false, // Don't process the files
				contentType: false, // Set content type to false as jQuery will tell the server its a query string request
				dataType   : 'json',
				success    : function (data, textStatus, jqXHR) {
					if ('success' === textStatus && data.data.message) {
						var wrapper = document.createElement('p');
						wrapper.class = 'survey_thank_you';
						wrapper.innerText = data.data.message;
						jQuery(form).replaceWith(wrapper);
					}

				}
			});

		}

		return false;

	});

});