/*! Greater Media - v0.1.0 - 2014-10-08
 * http://greatermedia.com
 * Copyright (c) 2014; * Licensed GPLv2+ */
(function ($) {
	'use strict';

	/* toggle the mobile menu */
	function mobileMenu() {
		$('#styleguide-nav-toggle').click(function () {
			var nav = $('#styleguide-nav'),
				toggle = $('#styleguide-nav-toggle');

			if (toggle.hasClass('active')) {
				toggle.removeClass('active');
				nav.removeClass('active');
			} else {
				toggle.addClass('active');
				nav.addClass('active');
			}
		});
	}

	// functions to run on load of the site
	$(document).ready(function(){
		mobileMenu();
	});

}(jQuery) );