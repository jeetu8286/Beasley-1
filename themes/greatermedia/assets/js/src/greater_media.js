/**
 * Greater Media
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */
(function() {
	'use strict';

	var body = document.querySelector('body'),
		mobileNavButton = document.querySelector('.mobile-nav--toggle');

	mobileNavButton.onclick = function(){
		body.classList.toggle('mobile-nav--open');
	};

})();