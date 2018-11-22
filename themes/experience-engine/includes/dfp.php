<?php

add_filter( 'bbgiconfig', 'ee_update_dfp_bbgiconfig', 50 );

if ( ! function_exists( 'ee_update_dfp_bbgiconfig' ) ) :
	function ee_update_dfp_bbgiconfig( $config ) {
		$config['dfp'] = array(
			'marked' => strtolower( ee_get_publisher_information( 'location' ) ),
			'genre'  => strtolower( implode( ',', ee_get_publisher_information( 'genre' ) ) ),
		);

		return $config;
	}
endif;
