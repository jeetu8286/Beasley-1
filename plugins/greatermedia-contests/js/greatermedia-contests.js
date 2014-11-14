jQuery(function () {

	// Attach a submit handler to contest forms
	jQuery('.' + GreaterMediaContests.form_class).submit(function (e) {

		var form = this;
		e.preventDefault();
		if ( jQuery(this).parsley().isValid() ) {

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

		}

		return false;

	});

});

/**
 * Set up date pickers for browsers without a native control
 */
document.addEventListener(
	"DOMContentLoaded",
	function () {
		var supported_input_types = supported_input_types();

		/**
		 * Generate a list of supported input types (text, date, range, etc.).
		 * Adapted from Modernizr, which is MIT licensed
		 * @see http://modernizr.com/
		 */
		function supported_input_types() {

			var inputElem = document.createElement('input'),
				docElement = document.documentElement,
				inputs = {},
				smile = ':)';

			return (function (props) {

				for (var i = 0, bool, inputElemType, defaultView, len = props.length; i < len; i++) {

					inputElem.setAttribute('type', inputElemType = props[i]);
					bool = inputElem.type !== 'text';

					if (bool) {

						inputElem.value = smile;
						inputElem.style.cssText = 'position:absolute;visibility:hidden;';

						if (/^range$/.test(inputElemType) && inputElem.style.WebkitAppearance !== undefined) {

							docElement.appendChild(inputElem);
							defaultView = document.defaultView;

							bool = defaultView.getComputedStyle &&
							defaultView.getComputedStyle(inputElem, null).WebkitAppearance !== 'textfield' &&
							(inputElem.offsetHeight !== 0);

							docElement.removeChild(inputElem);

						} else if (/^(search|tel)$/.test(inputElemType)) {
						} else if (/^(url|email)$/.test(inputElemType)) {
							bool = inputElem.checkValidity && inputElem.checkValidity() === false;

						} else {
							bool = inputElem.value != smile;
						}
					}

					inputs[props[i]] = !!bool;
				}

				return inputs;

			})('search tel url email datetime date month week time datetime-local number range color'.split(' '));
		}

		// Add datepickers for start & end dates if not supported natively
		if (!supported_input_types.hasOwnProperty('date') || false === supported_input_types.date) {
			jQuery('input[type=date]').datepicker(
				{
					format:'m/d/Y'
				}
			);
		}

	},
	false
);