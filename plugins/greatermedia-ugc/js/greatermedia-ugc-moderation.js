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