/**
 * Greater Media
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */
(function() {
	'use strict';

	var body = document.querySelector('body');
	var mobileNavButton = document.querySelector('.mobile-nav--toggle');

	mobileNavButton.onclick = function () {
		body.classList.toggle('mobile-nav--open');
	};

})();

(function ($) {
	"use strict";

	var $window = $(window);
	var pageWrap = $('#page-wrap');
	var livePlayer = $('.live-player');

	function livePlayerHeight() {
		var height = pageWrap.height();
		livePlayer.css( {"height": height + "px" } );
	}

	$window.on('resize', livePlayerHeight);

	setTimeout(livePlayerHeight, 1000);

}(jQuery) );