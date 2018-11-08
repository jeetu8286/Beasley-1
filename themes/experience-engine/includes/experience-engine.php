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
		 
		$request = bbgi_ee_get_request_from_cache( $path );

		if ( ! empty( $request ) ) {
			return $request
		}

		if ( empty( $args['method'] ) ) {
			$args['method'] = 'GET';
		}

		//Add the API Header
		$args['headers'] = format_ee_request_headers();
		
		$host    =  esc_url( trailingslashit( EE_API_HOST ) . $path );
		$request = wp_remote_request( $host, $args ); //try the existing host to avoid unnecessary calls
		
		$is_valid_res = ( $request_response_code >= 200 && $request_response_code <= 299 );

		if ( false === $request || is_wp_error( $request ) || ! $is_valid_res ) {
			$cache_time = bbgi_ee_get_request_cache_time( $request );

			if ( $cache_time ) {
				wp_cache_set( $path, $request, 'experience_engine_api', $cache_time );
			}		
		}

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

if ( ! function_exists( 'bbgi_ee_get_request_cache_time' ) ) :
	/**
	 * Get request cache time
	 *
	 * @param array $response HTTP response.
	 *
	 * @return Mixed false if cache time is not set int otherwise
	 */
	 function bbgi_ee_get_request_cache_time( $request ) {

		$request_response_code = (int) wp_remote_retrieve_response_code( $request );
		
		$response_headers = wp_remote_retrieve_headers( $request );

		if ( empty( $response_headers['cache-control']['max-age'] ) ) {
			return false;
		}

		return $response_headers['cache-control']['max-age'];
	}

endif;

if ( ! function_exists( 'bbgi_ee_get_request_from_cache' ) ) :
	/**
	 * Helper function to get request data from cache
	 *
	 * @param array $response HTTP response.
	 *
	 * @return mixed false if max-age is not set int otherwise
	 */
	 function bbgi_ee_get_request_from_cache( $path ) {

		$request_response_code = (int) wp_remote_retrieve_response_code( $request );
		
		$response_headers = wp_remote_retrieve_headers( $request );

		if ( empty( $response_headers['cache-control']['max-age'] ) ) {
			return false;
		}

		return absint( $response_headers['cache-control']['max-age'] );
	}

endif;

