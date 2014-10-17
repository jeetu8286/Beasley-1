/**
 * Greater Media
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */
(function ($,window,undefined) {
	'use strict';

	// variables
	var document = window.document,
		$document = $(document),
		$window = $(window),
		body = document.querySelectorAll('body'),
		$body = $(body),
		mobileNavButton = document.querySelectorAll('.mobile-nav--toggle'),
		$mobileNavButton = $(mobileNavButton);

	// function to toggle a class when the menu button is clicked
	function mobileNav(){
		$mobileNavButton.click(function(){
			$body.toggleClass('mobile-nav--open');
		});
	}

	mobileNav();

} )(jQuery,window);