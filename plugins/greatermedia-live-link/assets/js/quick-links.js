/*! GreaterMedia Live Links - v1.0.0
 * http://wordpress.org/plugins
 * Copyright (c) 2015; * Licensed GPLv2+ */
(function($, window, document, live_links) {
	$(document).ready(function() {
		$('#wp-admin-bar-add-live-link').click(function() {
			var location = document.location,
				encode = encodeURIComponent,
				url = live_links.url + '&u=' + encode(location.href) + '&t=' + encode(document.title) + '&v=4',
				open_window;

			open_window = function () {
				if (!window.open(url, 't', 'toolbar=0,resizable=1,scrollbars=1,status=1,width=720,height=570')) {
					location.href = url;
				}
			};

			if (/Firefox/.test(navigator.userAgent)) {
				setTimeout(open_window, 0);
			} else {
				open_window();
			}
		});
	});
})(jQuery, window, document, live_links);