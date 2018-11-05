<?php

add_filter( 'body_class', 'ee_jacapps_body_class' );

if ( ! function_exists( 'ee_is_jacapps' ) ) :
	function ee_is_jacapps() {
		return false !== stripos( $_SERVER['HTTP_USER_AGENT'], 'jacapps' );
	}
endif;

if ( ! function_exists( 'ee_jacapps_body_class' ) ) :
	function ee_jacapps_body_class( $classes ) {
		$classes[] = 'jacapps';
		return $classes;
	}
endif;

if ( function_exists( 'vary_cache_on_function' ) ) :
	// batcache variant
	vary_cache_on_function( 'return (bool) preg_match("/jacapps/i", $_SERVER["HTTP_USER_AGENT"]);' );
endif;
