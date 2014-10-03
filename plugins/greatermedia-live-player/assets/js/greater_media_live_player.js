/*! Greater Media Live Player - v0.1.0
 * http://wordpress.org/plugins
 * Copyright (c) 2014; * Licensed GPLv2+ */
(function ($,window,undefined) {
	"use strict";

	// variables
	var document = window.document,
		$document = $(document),
		$window = $(window),
		body = document.querySelectorAll('body'),
		$body = $(body),
		toggleButton = document.querySelectorAll('.gmlp-nav-toggle'),
		$toggleButton = $(toggleButton),
		menuLinkSelector = $('#container a');

	// function to toggle a class when the player button is clicked
	function togglePlayer(){
		$toggleButton.click(function(){
			$body.toggleClass('gmlp-open');
		});
	}

	// audio player controls
	function audioPlayer(){
		$('audio').mediaelementplayer({
			alwaysShowControls: true,
			features: ['playpause'],
			audioWidth: 60,
			audioHeight: 60
		});
	}

	function addLinkClass() {
		menuLinkSelector.addClass('pjaxer');
	}

	$document.ready(function($){
		togglePlayer();
		audioPlayer();
		addLinkClass();
	});

} )(jQuery,window);