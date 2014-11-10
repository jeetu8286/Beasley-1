/**
 * Greater Media
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */
(function() {
	'use strict';

	var headroom, livePlayerFix, livePlayerInit, livePlayerLocation,

		body = document.querySelector('body'),
		mobileNavButton = document.querySelector('.mobile-nav--toggle'),
		header = document.getElementById('header'),
		headerHeight = header.scrollHeight,
		livePlayer = document.getElementById('live-player--sidebar'),
		livePlayerStreamSelect = document.querySelector('.live-player__stream--current'),
		livePlayerStreams = document.querySelector('.live-player__stream--available'),
		wpAdminHeight = 32,
		onAir = document.getElementById('on-air'),
		nowPlaying = document.getElementById('now-playing'),
		liveLinks = document.getElementById('live-links');

	/**
	 * adds a class to the live player that causes it to become fixed to the top of the window while also removing the
	 * class that has the initial live player state
	 */
	livePlayerFix = function() {
		// Using an if statement to check the class
		livePlayer.style.top = '0px';
		livePlayer.classList.remove('live-player--init');
		livePlayer.classList.add('live-player--fixed');
	};

	/**
	 * adds a class to the live player that causes it to return to it's original state while also removing the class
	 * that causes the live player to become fixed to the top of the window
	 */
	livePlayerInit = function() {
		if (body.classList.contains('logged-in')) {
			livePlayer.style.top = headerHeight + wpAdminHeight + 'px';
		} else {
			livePlayer.style.top = headerHeight + 'px';
		}
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
	if(window.innerWidth >= 768) {
		headroom.init();
	}

	window.addEventListener('resize', function() {
		if(window.innerWidth >= 768) {
			headroom.init();
		}
	});

	/**
	 * Toggles a class to the body when the mobile nav button is clicked
	 */
	mobileNavButton.onclick = function(){
		body.classList.toggle('mobile-nav--open');
	};

	livePlayerStreamSelect.onclick = function(){
		this.classList.toggle('open');
	};

	/**
	 * Toggles a class to the live links when the live player is clicked clicked on smaller screens
	 */
	if(window.innerWidth <= 767) {
		onAir.onclick = function () {
			body.classList.toggle('live-player--open');
		};
		nowPlaying.onclick = function () {
			body.classList.toggle('live-player--open');
		};
	}

	window.addEventListener('resize', function() {
		if(window.innerWidth <= 767) {
			onAir.onclick = function () {
				body.classList.toggle('live-player--open');
			};
			nowPlaying.onclick = function () {
				body.classList.toggle('live-player--open');
			};
		}
	}, false);

})();