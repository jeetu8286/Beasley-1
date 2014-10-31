/**
 * Breaking News
 * http://wordpress.org/plugins
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */
(function( $ ){
	$( document ).ready(function($){
		synchronize_breaking_news_checboxes( $ );
	});

	function synchronize_breaking_news_checboxes( $ ) {
		var breakingNewsCheckbox = $( '#breaking-news-meta-fields' ).find( 'input#breaking_news_option' );
		var siteNotificationCheckbox = $( '#breaking-news-meta-fields' ).find( 'input#site_wide_notification_option' );

		breakingNewsCheckbox.bind( 'change', function() {
			if ( false === $( this ).is( ':checked' ) ) {
				siteNotificationCheckbox.removeAttr( 'checked' );
			}
		});

		siteNotificationCheckbox.bind( 'change', function() {
			if ( true === $( this ).is( ':checked' ) ) {
				breakingNewsCheckbox.attr('checked', 'checked');
			}
		});
	}
})( jQuery );
