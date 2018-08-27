<?php

/**
 * Since media is from S3, the default domain won't be replaced automatically, unless we overwrite it
 */
add_filter( 'dynamic_cdn_site_domain', function ( $domain ) {
	if ( defined( 'DYNAMIC_CDN_SITE_DOMAIN' ) ) {
		return DYNAMIC_CDN_SITE_DOMAIN;
	}

	return 'files.greatermedia.com.s3.amazonaws.com';
} );

add_filter( 'dynamic_cdn_extensions', function ( $extensions ) {
	$extensions = array_merge( $extensions, array( 'mp3', 'ogg', 'wma', 'm4a', 'wav' ) );

	return $extensions;
} );
