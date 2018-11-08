<?php

if ( ! function_exists( 'bbgi_has_publisher_information' ) ) :
	function bbgi_has_publisher_information( $meata ) {
		// not implemented yet
		return true;
	}
endif;

if ( ! function_exists( 'bbgi_get_publisher_information' ) ) :
	function bbgi_get_publisher_information( $meta ) {
		switch ( $meta ) {
			// not implemented yet
		}

		return '';
	}
endif;

if ( ! function_exists( 'bbgi_ee_request' ) ) :
	/**
	 * Wrapper for wp_remote_request
	 *
	 * This is a wrapper function for wp_remote_request.
	 *
	 *
	 * @param string $path Site URL to retrieve.
	 * @param array  $args Optional. Request arguments. Default empty array.
	 *
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	 function bbgi_ee_request( $path, $args = array() ) {

		if ( empty( $args['method'] ) ) {
			$args['method'] = 'GET';
		}

		//Add the API Header
		$args['headers'] = format_ee_request_headers();

		$request = false;
		
		$host    =  esc_url( trailingslashit( EE_API_HOST ) . $path );
		$request = wp_remote_request( $host, $args ); //try the existing host to avoid unnecessary calls

		return $request;

	}
endif;

if ( ! function_exists( 'format_ee_request_headers' ) ) :
	/**
	 * Add appropriate request headers
	 *
	 * @return array
	 */
	public function format_ee_request_headers() {
		$headers = array(
			'Content-Type' => 'application/json',
		);

		/**
		 * Define the constant FIREBASE_AUTHORIZATION in your wp-config.php
		 * Example: define( 'FIREBASE_AUTHORIZATION', 'es_admin:password' );
		 *
		 */
		if ( defined( 'FIREBASE_AUTHORIZATION' ) && FIREBASE_AUTHORIZATION ) {
			$headers['Authorization'] = 'Basic ' . base64_encode( FIREBASE_AUTHORIZATION );
		}

		return $headers;
	}
endif;


