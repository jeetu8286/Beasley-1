/**
 * Greater Media
 *
 * Set the content and sidebar columns to equal heights
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */
(function( window, document, jQuery ) {
	/**
	 * Variables
	 */
	var elements = document.querySelectorAll( 'section.content, aside.sidebar' );

	if ( window.matchMedia( '(min-width: 768px)' ).matches ) {

		// Using window.onload because heights we want need to be rendered.
		window.onload = function() {
			/* jshint ignore:start */
			matchHeights( elements );
			/* jshint ignore:end */
		};
	}

})( window, document, jQuery );