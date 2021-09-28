/*! Breaking News - v0.1.0
 * http://wordpress.org/plugins
 * Copyright (c) 2021; * Licensed GPLv2+ */
(function ($) {
	var $document = $(document);
	$document.ready(function () {
		$('#breaking_news_option').change(function() {
			$('#syndication_detached_action').val('no');
		});
	});
})(jQuery);
