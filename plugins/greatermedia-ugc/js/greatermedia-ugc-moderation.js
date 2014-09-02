// Scroll to the appropriate row if a hash is present
jQuery(function () {

	function do_scroll() {
		jQuery(document.body).animate({
			'scrollTop': jQuery(window.location.hash).offset().top
		}, 500);
	}

	if (window.location.href.indexOf('page=moderate-ugc')) {
		if (window.location.hash) {
			// If Twitter oembed content, delay scrolling until it's loaded
			if (jQuery('script[src="//platform.twitter.com/widgets.js"]').length) {
				setTimeout(function () {
					if (twttr.events.bind) {
						console.log('go');
						twttr.events.bind('loaded', do_scroll);
					}
				}, 100);
			}
			else {
				do_scroll();
			}
		}
	}

});

jQuery(function () {

	function append_extension(url, extension) {
		var parser = document.createElement('a');
		parser.href = url;

		var new_url = parser.protocol + '//' +
			parser.host +
			parser.pathname +
			'.' + extension +
			parser.search +
			parser.hash;

		return new_url;

	}

	jQuery('a[name=approve]').click(
		function () {

			var approve_link = append_extension(this.href, 'json');
			var ugc_id = jQuery(this).parents('tr').data('ugc-id');

			var req = jQuery.ajax( approve_link );
			req.done(function () {
				var row = jQuery('tr[data-ugc-id=' + ugc_id + ']');
				row.addClass('approved');
				row.find('a[name=approve]').replaceWith(GreaterMediaUGC.templates.approved);
				row.find('input[type=checkbox]').css('visibility', 'hidden');
				if(GreaterMediaAdminNotifier && GreaterMediaAdminNotifier.message) {
					// @TODO add listener name, contest name, etc. to this message & run it through translation
					GreaterMediaAdminNotifier.message('Approved')
				}
			});

			return false;
		}

	);

});