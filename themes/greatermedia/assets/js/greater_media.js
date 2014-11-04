/*! Greater Media - v0.1.0 - 2014-11-04
 * http://greatermedia.com
 * Copyright (c) 2014; * Licensed GPLv2+ */
(function() {
	'use strict';

	var body = document.querySelector('body');
	var mobileNavButton = document.querySelector('.mobile-nav--toggle');
	var header = document.getElementById('header');
	var livePlayer = document.getElementById('live-player--sidebar');
	var headroom;
	var livePlayerInit;

	/**
	 * adds headroom.js functionality to the header
	 *
	 * @type {Window.Headroom}
	 */
	headroom = new Headroom(header, {
		"offset": 238,
		"tolerance": {
			"up": 0,
			"down": 0
		},
		"classes": {
			"pinned": "header--pinned",
			"unpinned": "header--unpinned"
		}
	});

	/**
	 * detects if the header has a class of `header--unpinned`. If the header has this class, the `live-player--fixed`
	 * will be added to the live player so that it becomes fixed at the top of the window. This will also allows the
	 * live player to fall back to it's original location.
	 */
	livePlayerInit = function(){
		// Using an if statement to check the class
		if (header.classList.contains('header--unpinned')) {
			// The box that we clicked has a class of bad so let's remove it and add the good class
			livePlayer.classList.remove('live-player--init');
			livePlayer.classList.add('live-player--fixed');
		} else {
			// The user obviously can't follow instructions so let's alert them of what is supposed to happen next
			livePlayer.classList.remove('live-player--fixed');
			livePlayer.classList.add('live-player--init');
		}
	};

	/**
	 * Initiates `livePlayerInit` on scroll
	 */
	window.addEventListener('scroll', livePlayerInit, false);

	/**
	 * Initiates `headroom`
	 */
	headroom.init();

	/**
	 * Toggles a class to the body when the mobile nav button is clicked
	 */
	mobileNavButton.onclick = function(){
		body.classList.toggle('mobile-nav--open');
	};

})();