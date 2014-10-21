/*! Greater Media - v0.1.0 - 2014-10-21
 * http://greatermedia.com
 * Copyright (c) 2014; * Licensed GPLv2+ */
(function() {
	'use strict';

	var body = document.querySelector('body'),
		mobileNavButton = document.querySelector('.mobile-nav--toggle');

	mobileNavButton.onclick = function(){
		body.classList.toggle('mobile-nav--open');
	};

} )();