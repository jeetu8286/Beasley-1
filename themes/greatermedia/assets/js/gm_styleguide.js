/*! Greater Media - v0.1.0 - 2014-10-09
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
		sgToggleButton = document.querySelectorAll('#sg-nav-toggle'),
		$sgToggleButton = $(sgToggleButton);

	// function to toggle a class when the menu button is clicked
	function sgMenu(){
		$sgToggleButton.click(function(){
			$body.toggleClass('sg-nav-open');
		});
	}

	// functions to run on load of the site
	$(document).ready(function(){
		sgMenu();
	});

} )(jQuery,window);