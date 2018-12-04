<?php

add_action( 'rest_api_init', 'ee_rest_api_init' );

if ( ! function_exists( 'ee_rest_api_init' ) ) :
	function ee_rest_api_init() {
		$namespace = 'experience_engine/v1';

		register_rest_route( $namespace, '/purge-cache', array(
			'methods'  => \WP_REST_Server::READABLE,
			'callback' => 'ee_rest_purge_cache',
		) );

		$authorization = array(
			'authorization' => array(
				'type'              => 'string',
				'required'          => true,
				'validate_callback' => function( $value ) {
					return strlen( $value ) > 0;
				},
			),
		);

		register_rest_route( $namespace, 'save-user', array(
			'methods'  => \WP_REST_Server::CREATABLE,
			'callback' => 'ee_rest_save_user',
			'args'     => $authorization,
		) );

		register_rest_route( $namespace, 'get-user', array(
			'methods'  => \WP_REST_Server::CREATABLE,
			'callback' => 'ee_rest_get_user',
			'args'     => $authorization,
		) );
	}
endif;

if ( ! function_exists( 'ee_rest_purge_cache' ) ) :
	function ee_rest_purge_cache() {
		update_option( 'ee_cache_index', time(), 'no' );
		return rest_ensure_response( 'Cache Flushed' );
	}
endif;

if ( ! function_exists( 'ee_rest_save_user' ) ) :
	function ee_rest_save_user( $request ) {
		$request = rest_ensure_request( $request );
		$authorization = $request->get_param( 'authorization' );

		$path = 'user?authorization=' . urlencode( $authorization );
		$response = _bbgi_ee_request( $path, array( 'method' => 'PUT' ) );

		return rest_ensure_response( 'OK' );
	}
endif;

if ( ! function_exists( 'ee_rest_get_user' ) ) :
	function ee_rest_get_user( $request ) {
		$request = rest_ensure_request( $request );
		$authorization = $request->get_param( 'authorization' );

		$path = 'user?authorization=' . urlencode( $authorization );
		$response = _bbgi_ee_request( $path );
		if ( ! is_wp_error( $response ) ) {
			if ( wp_remote_retrieve_response_code( $response ) == 200 ) {
				$response = wp_remote_retrieve_body( $response );
				$response = json_decode( $response, true );
			} else {
				$response = new \WP_Error( 401, 'Authorization failed' );
			}
		}

		return rest_ensure_response( $response );
	}
endif;
