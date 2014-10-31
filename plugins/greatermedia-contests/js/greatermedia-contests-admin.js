document.addEventListener(
	"DOMContentLoaded",
	function () {
		var args = {
			selector: '#contest_embedded_form'
		};

		if (GreaterMediaContestsForm.form) {
			args.bootstrapData = JSON.parse(GreaterMediaContestsForm.form);
		}

		var formbuilder = new Formbuilder(args);

		formbuilder.on('save', function (payload) {
			// payload is a JSON string representation of the form
			document.getElementById('contest_embedded_form_data').value = JSON.stringify(JSON.parse(payload).fields);
		});

		// Default the hidden field with the form data loaded from the server
		document.getElementById('contest_embedded_form_data').value = JSON.stringify(GreaterMediaContestsForm.form);

	},
	false
);
