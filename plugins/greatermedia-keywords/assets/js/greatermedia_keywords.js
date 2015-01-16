/*! GreaterMedia Keywords - v0.0.1
 * http://wordpress.org/plugins
 * Copyright (c) 2015; * Licensed GPLv2+ */
// Silence jslint warning about _ being undefined.
/*global _, ajaxurl */

( function( window, undefined ) {
	'use strict';

	jQuery( function( $ ) {


		// supa search
		function Fancy_Search() {

		}

		new Fancy_Search();

		var $container = $( '#linked_content_item_container' );
		var item_template = _.template( $( '#linked_content_item_template' ).html() );

		function search( term ) {

			$.getJSON( ajaxurl, {
				action: 'greater_media/keywords/get_posts',
				s: term
			} )
				.done( function ( res ) {
					console.log('new stuff... clearing');
					$container.empty();
					_.each( res.data, function ( item ) {
						$container.append( $( item_template( item ) ) );
					} );
				} )
			;
		}


		$( '#linked_content_search' ).keyup( _.debounce( function () {
			search( $( this ).val() );
		}, 1000 ) );

		search( '' );



		$('.submitdelete').on( 'click', function( event ) {
			event.preventDefault();
			var el = $(this);
			var post_id = el.data( 'postid');
			jQuery.ajax({
				type : "post",
				url : ajax_data.ajaxurl,
				data : {
					action: "delete_keyword" ,
					post_id : post_id ,
					delete_key_nonce: ajax_data.delete_key_nonce
				},
				success: function( success ) {
					if( success ) {
						el.closest('tr').addClass('delete').hide( 'drop' );
					} else {
						$('.form-table').first().prepend(
							'<div id="message" class="error"><p>Something gone wrong! Keyword is not deleted!</p></div>'
						);
					}
				}
			});
		});
	});

} )( this );