/**
 * Greater Media Podcasts
 * http://wordpress.org/plugins
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */

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