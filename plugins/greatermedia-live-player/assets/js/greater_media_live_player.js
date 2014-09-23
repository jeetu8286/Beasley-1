/*! Greater Media Live Player - v0.1.0
 * http://wordpress.org/plugins
 * Copyright (c) 2014; * Licensed GPLv2+ */
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