/**
 * Greater Media Content Syndication
 * http://wordpress.org/plugins
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */

/*global $:false, jQuery:false, wp:false */
( function( window, undefined ) {
	'use strict';

	jQuery( function( $ ) {
		$("#subscription_terms").select2({
			placeholder: "Select term"
		});
	});

} )( this );