/*! Greater Media Live Player - v0.1.0
 * http://wordpress.org/plugins
 * Copyright (c) 2014; * Licensed GPLv2+ */
(function ($) {
	"use strict";

	// function to toggle a class when the player button is clicked
	function togglePlayer(){
		var body;
		var toggleButton;
		toggleButton = $('.gmlp-nav-toggle');
		body = $('body');

		toggleButton.click(function(){
			body.toggleClass('gmlp-open');
		});
	}

	function audioPlayer(){
		$('audio').mediaelementplayer({
			alwaysShowControls: true,
			features: ['playpause'],
			audioWidth: 60,
			audioHeight: 60
		});
	}

	$(document).ready(function($){
		togglePlayer();
		audioPlayer();
	});

} )(jQuery);