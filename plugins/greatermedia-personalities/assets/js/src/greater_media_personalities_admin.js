/**
 * Greater Media Personalities
 * http://
 *
 * Copyright (c) 2014 Michael Phillips (10up)
 * Licensed under the GPLv2+ license.
 */

/*jslint devel:true */

var PERSONALITY_JS;
var userPersonalities = PERSONALITY_JS.userPersonalities;

( function( $, window, document ) {
	'use strict';

	$( document ).ready( function( $, userPersonalities ){
		set_default_personality_terms( $ );
	});

	function set_default_personality_terms( $, userPersonalities ) {
		// If there isn't already a personality selected...
		if ( 0 === $( '#_personalitychecklist input[type=checkbox]:checked' ).length ) {
			$.each( PERSONALITY_JS.userPersonalities, function( index, value ) {
				$( 'input:checkbox[value=' + value + ']' ).attr( 'checked', true );
			});
		}
	}
})( jQuery );
