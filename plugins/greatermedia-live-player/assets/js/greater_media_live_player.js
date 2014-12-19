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
		playButton = $('#playButton'),
		pauseButton = $('#pauseButton');


	var enablePjax = function() {
		$(document).pjax('a:not(.ab-item)', 'section.content', {'fragment': 'section.content', 'maxCacheLength': 500, 'timeout' : 5000});
	};

	playButton.on('click', function(event) {
		event.preventDefault();
		// add gif file for testing
		// call pjax to update container
		if( !gmlp.logged_in ) {
			enablePjax();
		}

	});

	pauseButton.on('click', function(event) {
		event.preventDefault();
	});


	// For "inline" audio support
	if ( "undefined" !== typeof Modernizr && Modernizr.audio ) {
		var inlineAudio = new Audio(),// todo Make sure to check compatibility first, or else fall back to media element!
			content = document.querySelectorAll( '.content'),
			$content = $(content); // Because getElementsByClassName is not supported in IE8 ಠ_ಠ

		var resetStates = function() {
			$('.podcast__btn--play.playing').removeClass('playing');
			$('.podcast__btn--pause.playing').removeClass('playing');
		};

		inlineAudio.addEventListener( 'ended', resetStates );

		$content.on('click', '.podcast__btn--play', function(e) {
			var $play = $(e.currentTarget),
				$pause = $play.parent().find('.podcast__btn--pause');

			inlineAudio.src = $play.attr('data-mp3-src');
			inlineAudio.play();

			resetStates();

			$play.addClass('playing');
			$pause.addClass('playing');

			enablePjax();
		});

		$content.on('click', '.podcast__btn--pause', function(e) {
			inlineAudio.pause();

			resetStates();
		});
	} else {
		var $meFallbacks = $('.gmr-mediaelement-fallback audio'),
			$customInterfaces = $('.podcast__play');

		$meFallbacks.mediaelementplayer();
		$customInterfaces.hide();
	}

} )(jQuery,window);