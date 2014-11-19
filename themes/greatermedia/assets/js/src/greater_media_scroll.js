/**
 * Greater Media
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */
(function() {
	'use strict';

	var _now,

		body = document.querySelector( 'body' ),
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

	_now = Date.now || function () {
			return new Date().getTime();
		};

	function _throttle(func, wait, options) {
		var context, args, result;
		var timeout = null;
		var previous = 0;
		if (!options) options = {};
		var later = function() {
			previous = options.leading === false ? 0 : _now();
			timeout = null;
			result = func.apply(context, args);
			if (!timeout) context = args = null;
		};
		return function() {
			var now = _now();
			if (!previous && options.leading === false) previous = now;
			var remaining = wait - (now - previous);
			context = this;
			args = arguments;
			if (remaining <= 0 || remaining > wait) {
				clearTimeout(timeout);
				timeout = null;
				previous = now;
				result = func.apply(context, args);
				if (!timeout) context = args = null;
			} else if (!timeout && options.trailing !== false) {
				timeout = setTimeout(later, remaining);
			}
			return result;
		};
	}

	function _debounce(func, wait, immediate) {
		var timeout, args, context, timestamp, result;

		var later = function() {
			var last = _now() - timestamp;

			if (last < wait && last > 0) {
				timeout = setTimeout(later, wait - last);
			} else {
				timeout = null;
				if (!immediate) {
					result = func.apply(context, args);
					if (!timeout) context = args = null;
				}
			}
		};

		return function() {
			context = this;
			args = arguments;
			timestamp = _now();
			var callNow = immediate && !timeout;
			if (!timeout) timeout = setTimeout(later, wait);
			if (callNow) {
				result = func.apply(context, args);
				context = args = null;
			}

			return result;
		};
	}

	function getScrollPosition() {
		scrollObject = {
			x: window.pageXOffset,
			y: window.pageYOffset
		};

		if( scrollObject.y === 0 ) {
			if ( body.classList.contains( 'logged-in' ) ) {
				livePlayer.style.top = headerHeight + wpAdminHeight + 'px';
				livePlayer.style.height = windowHeight - wpAdminHeight - headerHeight + 'px';
			} else {
				livePlayer.style.top = headerHeight + 'px';
				livePlayer.style.height = windowHeight - headerHeight + 'px';
			}
			livePlayer.classList.remove( 'live-player--fixed' );
			livePlayer.classList.add( 'live-player--init' );
		} else if ( scrollObject.y >= 1 && scrollObject.y <= headerHeight ){
			livePlayer.style.height = '100%';
		} else if ( scrollObject.y >= headerHeight ) {
			if ( body.classList.contains( 'logged-in' ) ) {
				livePlayer.style.top = wpAdminHeight + 'px';
				livePlayer.style.height = windowHeight - wpAdminHeight + 'px';
			} else {
				livePlayer.style.top = '0px';
				livePlayer.style.height = windowHeight + 'px';
			}
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
		if ( body.classList.contains( 'logged-in' ) ) {
			livePlayer.style.top = headerHeight + wpAdminHeight + 'px';
			livePlayer.style.height = windowHeight - wpAdminHeight - headerHeight + 'px';
		} else {
			livePlayer.style.top = headerHeight + 'px';
			livePlayer.style.height = windowHeight - headerHeight + 'px';
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

	var scrollDebounce = _debounce(getScrollPosition, 50);
	var scrollThrottle = _throttle(getScrollPosition, 50);
	var resizeDebounce = _debounce(resizeWindow, 50);
	var resizeThrottle = _throttle(resizeWindow, 50);

	window.addEventListener( 'scroll', function() {
		scrollDebounce();
		scrollThrottle();
	}, false );

	window.addEventListener( 'resize', function() {
		resizeDebounce();
		resizeThrottle();
	}, false);

})();