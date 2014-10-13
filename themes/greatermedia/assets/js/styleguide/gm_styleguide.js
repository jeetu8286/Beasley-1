/**
 * Greater Media Style Guide
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
		sgToggleButton = document.querySelectorAll('#sg-nav-toggle'),
		$sgToggleButton = $(sgToggleButton),
		$sgShowHTML = $('.sg-markup-controls .sg-show-html'),
		$sgSourceHTML = $('.sg-source-html');

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