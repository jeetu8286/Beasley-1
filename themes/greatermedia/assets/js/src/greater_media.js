/**
 * Greater Media
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */
(function() {

	var headroom, livePlayerFix, livePlayerInit, livePlayerLocation, livePlayerScroll,

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
		windowHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

	/**
	 * adds a class to the live player that causes it to become fixed to the top of the window while also removing the
	 * class that has the initial live player state
	 */
	livePlayerFix = function() {
		// Using an if statement to check the class
		if (body.classList.contains( 'logged-in' )) {
			livePlayer.style.top = wpAdminHeight + 'px';
		} else {
			livePlayer.style.top = '0px';
		}
		livePlayer.classList.remove( 'live-player--init' );
		livePlayer.classList.add( 'live-player--fixed' );
		livePlayer.style.height = windowHeight - headerHeight - wpAdminHeight + 'px';
	};

	/**
	 * adds a class to the live player that causes it to return to it's original state while also removing the class
	 * that causes the live player to become fixed to the top of the window
	 */
	livePlayerInit = function() {
		if (body.classList.contains( 'logged-in' )) {
			livePlayer.style.top = headerHeight + wpAdminHeight + 'px';
			livePlayer.style.height = windowHeight - headerHeight - wpAdminHeight + 'px';
		} else {
			livePlayer.style.top = headerHeight + 'px';
			livePlayer.style.height = windowHeight - headerHeight + 'px';
		}
		livePlayer.classList.remove( 'live-player--fixed' );
		livePlayer.classList.add( 'live-player--init' );
	};

	function lpScroll() {
		if (body.classList.contains( 'logged-in' )) {
			if (header.classList.contains('header--pinned')) {
				livePlayer.style.height = windowHeight - headerHeight - wpAdminHeight + 'px';
			} else if (header.classList.contains('header--unpinned')) {
				livePlayer.style.height = windowHeight - wpAdminHeight + 'px';
			} else {
				livePlayer.style.height = '100%';
			}
		} else {
			if (header.classList.contains('header--pinned')) {
				livePlayer.style.height = windowHeight - headerHeight + 'px';
			} else if (header.classList.contains('header--unpinned')) {
				livePlayer.style.height = windowHeight + 'px';
			} else {
				livePlayer.style.height = '100%';
			}
		}
	}
	window.addEventListener( 'scroll', lpScroll, false );

	/**
	 * adds headroom.js functionality to the header
	 *
	 * @type {Window.Headroom}
	 */
	headroom = new Headroom( header, {
		offset: headerHeight,
		tolerance : 0,
		classes: {
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
	 * Initiates `headroom` if the window size is above 768px
	 */
	if ( window.innerWidth >= 768 ) {
		headroom.init();
	}

	/**
	 * Toggles a class to the body when the mobile nav button is clicked
	 */
	function toggleNavButton() {
		body.classList.toggle( 'mobile-nav--open' );
	}
	mobileNavButton.addEventListener( 'click', toggleNavButton, false );

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

	if( window.innerWidth <= 767 ) {
		onAir.addEventListener( 'click', onAirClick, false );
		nowPlaying.addEventListener( 'click', nowPlayingClick, false );
	}

	/**
	 * A fail-safe for when the browser window is resized
	 */
	window.addEventListener( 'resize', function() {
		if( window.innerWidth <= 767 ) {
			onAir.addEventListener( 'click', onAirClick, false );
			nowPlaying.addEventListener( 'click', nowPlayingClick, false );
		}
		if( window.innerWidth >= 768 ) {
			headroom.init();
		}
	});

})();