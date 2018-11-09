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
	 * @param array $args Optional. Request arguments. Default empty array.
	 *
	 * @return WP_Error|array The response or WP_Error on failure.
	 */
	function bbgi_ee_request( $path, $args = array() ) {

		$request = wp_cache_get( $path, 'experience_engine_api' );

		if ( empty( $request ) ) {

			if ( empty( $args['method'] ) ) {
				$args['method'] = 'GET';
			}

			//Add the API Header
			$args['headers'] = array(
				'Content-Type' => 'application/json',
			);

			$host    = trailingslashit( EE_API_HOST ) . "/v1/{$path}";
			$request = wp_remote_request( $host, $args );

			if ( is_wp_error( $request ) ) {
				return $request;
			}

			$request_response_code = (int) wp_remote_retrieve_response_code( $request );

			$is_valid_res = ( $request_response_code >= 200 && $request_response_code <= 299 );

			if ( false === $request || ! $is_valid_res ) {
				return $request;
			}

			$cache_time = bbgi_ee_get_request_cache_time( $request );

			if ( absint( $cache_time ) ) {
				wp_cache_set( $path, $request, 'experience_engine_api', $cache_time );
			}
		}

		return $request;
	}

endif;

if ( ! function_exists( 'bbgi_ee_get_request_cache_time' ) ) :
	/**
	 * Get request cache time
	 *
	 * @param array $request HTTP response.
	 *
	 * @return int cache time.
	 */
	function bbgi_ee_get_request_cache_time( $request ) {

		$response_headers = wp_remote_retrieve_headers( $request );

		if ( empty( $response_headers['cache-control'] ) ) {
			return 0;
		}

		$cache_control = explode( ',', $response_headers['cache-control'] );
		$cache_time    = 0;

		foreach ( $cache_control as $control_string ) {
			$control_string = trim( $control_string );

			if ( strpos( $control_string, 's-maxage' ) === 0 ) {
				$cache_time = end( explode( 's-maxage=', $control_string ) );
				break;
			}

			if ( strpos( $control_string, 'max-age' ) === 0 ) {
				$cache_time = end( explode( 'max-age=', $control_string ) );
			}
		}

		return absint( $cache_time );
	}

endif;
