/*! Greater Media - v0.1.0 - 2014-11-05
 * http://greatermedia.com
 * Copyright (c) 2014; * Licensed GPLv2+ */
(function() {
	'use strict';

	var body = document.querySelector('body');
	var mobileNavButton = document.querySelector('.mobile-nav--toggle');
	var header = document.getElementById('header');
	var headerHeight = header.offsetHeight;
	var livePlayer = document.getElementById('live-player--sidebar');
	var headroom;
	var livePlayerFix;
	var livePlayerInit;

	/**
	 * adds a class to the live player that causes it to become fixed to the top of the window while also removing the
	 * class that has the initial live player state
	 */
	livePlayerFix = function() {
		// Using an if statement to check the class
		livePlayer.classList.remove('live-player--init');
		livePlayer.classList.add('live-player--fixed');
	};

	/**
	 * adds a class to the live player that causes it to return to it's original state while also removing the class
	 * that causes the live player to become fixed to the top of the window
	 */
	livePlayerInit = function() {
		// Using an if statement to check the class
		livePlayer.classList.remove('live-player--fixed');
		livePlayer.classList.add('live-player--init');
	};

	/**
	 * adds headroom.js functionality to the header
	 *
	 * @type {Window.Headroom}
	 */
	headroom = new Headroom(header, {
		"offset": headerHeight,
		"tolerance": {
			"up": 0,
			"down": 0
		},
		"classes": {
			"pinned": "header--pinned",
			"unpinned": "header--unpinned"
		},
		onTop : function() {
			livePlayerInit();
		},
		onNotTop : function() {
			livePlayerFix();
		}
	});

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