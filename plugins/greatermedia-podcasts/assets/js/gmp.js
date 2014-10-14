/*! Greater Media Podcasts - v0.1.0
 * http://wordpress.org/plugins
 * Copyright (c) 2014; * Licensed GPLv2+ */
(function ($) {
	"use strict";

	// function to target html5 audio
	function audioPlayer(){
		$('audio').mediaelementplayer({
			alwaysShowControls: true,
			features: ['playpause'],
			audioWidth: 60,
			audioHeight: 60
		});
	}

	$(document).ready(function($){
		audioPlayer();
	});

} )(jQuery);