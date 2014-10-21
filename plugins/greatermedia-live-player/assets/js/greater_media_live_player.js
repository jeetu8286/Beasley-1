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
		playButton = $('#playButton'),
		pauseButton = $('#pauseButton');

	// function to toggle a class when the player button is clicked
	function togglePlayer(){
		$toggleButton.click(function(){
			$body.toggleClass('gmlp-open');
		});
	}

	$document.ready(function($){
		togglePlayer();
	});

	playButton.on('click', function(event) {
		event.preventDefault();
		// add gif file for testing
		$('#trackInfo').html('<div><img src="http://coolchaser-static.coolchaser.com/images/themes/t/188000-i175.photobucket.com-albums-w144-XslayerXpac-equalizer.gif" alt=""></div>');
		// call pjax to update container
		$(document).pjax('a:not(.ab-item)', 'main.main', {'fragment': 'main.main', 'maxCacheLength': 500, 'timeout' : 5000});
		playButton.hide();
		pauseButton.show();
	});

	pauseButton.on('click', function(event) {
		event.preventDefault();
		// add gif file for testing
		$('#trackInfo').html('');
		pauseButton.hide();
		playButton.show();
	});
	
	
/*
    $.pjax({
      area: 'main.main',
      scope: {
        '/': ['/', '!/wp-login.php', '!/wp-admin/']
      },
      load: {
        head: 'base, meta, link',
        css: true,
        script: true
      },
      cache: { click: true, submit: false, popstate: true },
      server: { query: null },
      speedcheck: true
    });
    
    $(document).bind( 'pjax:ready' );
    */
} )(jQuery,window);
