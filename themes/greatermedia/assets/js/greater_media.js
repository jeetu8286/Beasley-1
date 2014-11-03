/*! Greater Media - v0.1.0 - 2014-11-03
 * http://greatermedia.com
 * Copyright (c) 2014; * Licensed GPLv2+ */
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
	var header = $('.header');
	var breakingNews = $('#breaking-news-banner');

	function livePlayerHeight() {
		var height = pageWrap.height();
		var headerHeight = header.height();
		var bnHeight = breakingNews.height();
		livePlayer.css( {"top": headerHeight + bnHeight + "px" } );
		livePlayer.css( {"height": height - headerHeight - bnHeight + "px" } );
	}

	$window.on('resize', livePlayerHeight);

	setTimeout(livePlayerHeight, 1000);

}(jQuery) );