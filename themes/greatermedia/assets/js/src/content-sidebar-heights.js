/**
 * Greater Media
 *
 * Set the content and sidebar columns to equal heights.
 */
(function( window, document ) {
	/**
	 * Variables
	 */
	var elements = document.querySelectorAll( 'section.content, aside.sidebar' );

	if ( window.matchMedia( '(min-width: 768px)' ).matches ) {

		// Using window.onload because heights we want need to be rendered.
		window.addEventListener( 'load', function( e ) {
			/* jshint ignore:start */
			matchHeights( elements );
			/* jshint ignore:end */
		} );
	}

})( window, document );