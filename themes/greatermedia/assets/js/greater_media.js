/*! Greater Media - v0.1.0 - 2014-11-18
 * http://greatermedia.com
 * Copyright (c) 2014; * Licensed GPLv2+ */
(function() {
	'use strict';

	var body = document.querySelector( 'body' ),
		mobileNavButton = document.querySelector( '.mobile-nav__toggle' ),
		header = document.getElementById( 'header' ),
		headerHeight = header.offsetHeight,
		livePlayer = document.getElementById( 'live-player__sidebar' ),
		livePlayerStreamSelect = document.querySelector( '.live-player__stream--current' ),
		livePlayerStreams = document.querySelector( '.live-player__stream--available' ),
		wpAdminHeight = 32,
		onAir = document.getElementById( 'on-air' ),
		nowPlaying = document.getElementById( 'now-playing' ),
		liveLinks = document.getElementById( 'live-links'),
		windowHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0),
		scrollObject = {};

	function getScrollPosition() {
		scrollObject = {
			x: window.pageXOffset,
			y: window.pageYOffset
		};

		if( scrollObject.y === 0 ) {
			if ( body.classList.contains( 'logged-in' ) ) {
				livePlayer.style.top = headerHeight + wpAdminHeight + 'px';
			} else {
				livePlayer.style.top = headerHeight + 'px';
			}
			livePlayer.style.height = windowHeight - headerHeight + 'px';
			livePlayer.classList.remove( 'live-player--fixed' );
			livePlayer.classList.add( 'live-player--init' );
		} else if ( scrollObject.y >= 1 && scrollObject.y <= headerHeight ){
			livePlayer.style.height = '100%';
			livePlayerInit();
		} else if ( scrollObject.y >= headerHeight ) {
			if ( body.classList.contains( 'logged-in' ) ) {
				livePlayer.style.top = wpAdminHeight + 'px';
			} else {
				livePlayer.style.top = '0px';
			}
			livePlayer.style.height = windowHeight + 'px';
			livePlayer.classList.remove( 'live-player--init' );
			livePlayer.classList.add( 'live-player--fixed' );
		} else {
			livePlayer.style.height = '100%';
		}
	}

	/**
	 * adds a class to the live player that causes it to return to it's original state while also removing the class
	 * that causes the live player to become fixed to the top of the window
	 */
	function livePlayerInit() {
		if (body.classList.contains( 'logged-in' )) {
			livePlayer.style.top = headerHeight + wpAdminHeight + 'px';
		} else {
			livePlayer.style.top = headerHeight + 'px';
		}
		livePlayer.classList.remove( 'live-player--fixed' );
		livePlayer.classList.add( 'live-player--init' );
	}

	/**
	 * Toggles a class to the Live Play Stream Select box when the box is clicked
	 */
	function toggleStreamSelect() {
		livePlayerStreamSelect.classList.toggle( 'open' );
	}
	livePlayerStreamSelect.addEventListener( 'click', toggleStreamSelect, false );

	/**
	 * Toggles a class to the live links when the live player is clicked clicked on smaller screens
	 */
	function onAirClick() {
		body.classList.toggle( 'live-player--open' );
	}

	function nowPlayingClick() {
		body.classList.toggle( 'live-player--open' );
	}

	function resizeWindow() {
		if( window.innerWidth <= 767 ) {
			onAir.addEventListener( 'click', onAirClick, false );
			nowPlaying.addEventListener( 'click', nowPlayingClick, false );
		}
		if ( window.innerWidth >= 768 ) {
			window.addEventListener( 'load', livePlayerInit, false );
		}
	}

	if( window.innerWidth <= 767 ) {
		onAir.addEventListener( 'click', onAirClick, false );
		nowPlaying.addEventListener( 'click', nowPlayingClick, false );
	}

	if ( window.innerWidth >= 768 ) {
		window.addEventListener( 'load', livePlayerInit, false );
	}

	var scrollDebounce = _.debounce(getScrollPosition, 100),
		scrollThrottle = _.throttle(getScrollPosition, 100),
		resizeDebounce = _.debounce(resizeWindow, 100),
		resizeThrottle = _.throttle(resizeWindow, 100);

	window.addEventListener( 'scroll', function() {
		scrollDebounce();
		scrollThrottle();
	}, false );
	window.addEventListener( 'resize', function() {
		resizeDebounce();
		resizeThrottle();

	}, false);

})();