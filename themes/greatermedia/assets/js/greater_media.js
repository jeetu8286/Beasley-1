/*! Greater Media - v0.1.0 - 2014-10-17
 * http://greatermedia.com
 * Copyright (c) 2014; * Licensed GPLv2+ */
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