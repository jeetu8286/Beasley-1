<?php

if ( ! function_exists( 'ee_get_episodes_query' ) ) :
	function ee_get_episodes_query( $podcast = null ) {
		$podcast = get_post( $podcast );

		return new \WP_Query( array(
			'post_type'   => 'episode',
			'post_parent' => $podcast->ID,
		) );
	}
endif;
