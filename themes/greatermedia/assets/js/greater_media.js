/*! Greater Media - v0.1.0 - 2014-11-03
 * http://greatermedia.com
 * Copyright (c) 2014; * Licensed GPLv2+ */
(function() {
	'use strict';

	var body = document.querySelector('body');
	var mobileNavButton = document.querySelector('.mobile-nav--toggle');
	var header = document.querySelector(".header");
	var headroom = new Headroom(header, {
		"offset": 100,
		"tolerance": {
			"up": 0,
			"down": 0
		},
		"classes": {
			"initial": "animated",
			"pinned": "slideDown",
			"unpinned": "slideUp"
		}
	});

	headroom.init();

	headroom.destroy();

	mobileNavButton.onclick = function () {
		body.classList.toggle('mobile-nav--open');
	};

})();