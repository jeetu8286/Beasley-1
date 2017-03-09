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

		/* jshint ignore:start */

		// Using window.onload because heights we want need to be rendered.
		window.addEventListener( 'load', function( e ) {
			matchHeights( elements );
		} );

		// Match heights any time an ad renders.

		// Commented out by Steve as this was throwing a JS error
		/*googletag.pubads().addEventListener( 'slotRenderEnded', function( e ) {
			matchHeights( elements );
		} );*/

		// Not ideal: Match heights every 3 seconds for additional content loading.
		setInterval( function() {
			matchHeights( elements );
		}, 3000 );

		/* jshint ignore:end */
	}

})( window, document );
