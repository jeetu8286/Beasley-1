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
		toggleButton = document.querySelectorAll('#styleguide-nav-toggle'),
		$toggleButton = $(toggleButton);

	// function to toggle a class when the menu button is clicked
	function toggleMenu(){
		$toggleButton.click(function(){
			$body.toggleClass('styleguide-nav-open');
		});
	}

	// functions to run on load of the site
	$(document).ready(function(){
		toggleMenu();
	});

} )(jQuery,window);