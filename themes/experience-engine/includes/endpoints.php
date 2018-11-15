<?php
add_action( 'rest_api_init', 'ee_rest_api_init' );

if ( ! function_exists( 'ee_rest_api_init' ) ) :
	function ee_rest_api_init() {
		register_rest_route( 'experience_engine/v1', '/purge-cache/', array(
			'methods'  => 'GET',
			'callback' => 'ee_purge_cache',
		) );
	}

endif;

if ( ! function_exists( 'ee_purge_cache' ) ) :
	function ee_purge_cache( WP_REST_Request $request ) {
		wp_cache_flush();
		return rest_ensure_response( 'Cache Flushed' );
	}

endif;

