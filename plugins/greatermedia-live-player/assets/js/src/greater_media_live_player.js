/**
 * Greater Media Live Player
 * http://wordpress.org/plugins
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */

(function ($,window,undefined) {
	"use strict";

	// variables
	var document = window.document,
		$document = $(document),
		$window = $(window),
		body = document.querySelectorAll('body'),
		$body = $(body),
		toggleButton = document.querySelectorAll('.gmlp-nav-toggle'),
		$toggleButton = $(toggleButton);

	// function to toggle a class when the player button is clicked
	function togglePlayer(){
		$toggleButton.click(function(){
			$body.toggleClass('gmlp-open');
		});
	}

	$document.ready(function($){
		togglePlayer();
	});

} )(jQuery,window);