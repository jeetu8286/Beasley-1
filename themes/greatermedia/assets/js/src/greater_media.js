/**
 * Greater Media
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */
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