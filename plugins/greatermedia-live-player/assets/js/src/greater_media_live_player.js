/**
 * Greater Media Live Player
 * http://wordpress.org/plugins
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */

(function ($) {
	"use strict";

	function togglePlayer(){
		var toggleButton = $('.gmlp-nav-toggle'),
			body = $('body');

		toggleButton.click(function(){
			body.toggleClass('gmlp-open');
		});
	}

	$(document).ready(function($){
		togglePlayer();
	});

} )(jQuery);