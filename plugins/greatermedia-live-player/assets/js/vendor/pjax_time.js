/**
 * Greater Media Live Player
 * http://wordpress.org/plugins
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */

(function ($) {
	"use strict";

	$(':checkbox').attr('checked', $.cookie('pjax'))

	if ( !$(':checkbox').attr('checked') )
		$.fn.pjax = $.noop

	$(':checkbox').change(function() {
		if ( $.pjax == $.noop ) {
			$(this).removeAttr('checked')
			return alert( "Sorry, your browser doesn't support pjax :(" )
		}

		if ( $(this).attr('checked') )
			$.cookie('pjax', true)
		else
			$.cookie('pjax', null)

		window.location = location.href
	})


} )(jQuery);
