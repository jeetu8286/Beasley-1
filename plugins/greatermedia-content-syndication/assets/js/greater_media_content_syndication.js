/*! Greater Media Content Syndication - v0.0.1
 * http://wordpress.org/plugins
 * Copyright (c) 2014; * Licensed GPLv2+ */
/*global $:false, jQuery:false, wp:false */
( function( window, undefined ) {
	'use strict';

	jQuery( function( $ ) {
		$(".subscription_terms").select2({
			placeholder: "Select term"
		});
	});

} )( this );