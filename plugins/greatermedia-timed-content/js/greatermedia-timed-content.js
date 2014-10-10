(function () {

	GreaterMediaTimedContent = (function ($, undefined) {

		var public = {};

		function current_unix_time_utc() {
			return Math.floor(new Date().getTime() / 1000);
		}

		jQuery('.timed-content').each(function () {

			var content_block = jQuery(this),
				show = content_block.data('show-time'),
				hide = content_block.data('hide-time'),
				now_utc = current_unix_time_utc();

			if (!(_.isNumber(show))) {
				show = Number.MIN_VALUE;
			}

			if (!(_.isNumber(hide))) {
				hide = Number.MAX_VALUE;
			}

			if (now_utc > show && hide > now_utc) {
				// leave content visible
			}
			else {
				jQuery(content_block).hide();
			}

		});

		return public;

	}(jQuery));

})();