<?php

if ( ! function_exists( 'ee_get_episodes_query' ) ) :
	function ee_get_episodes_query( $podcast = null, $args = array() ) {
		$podcast = get_post( $podcast );

		return new \WP_Query( array_merge( $args, array(
			'post_type'   => 'episode',
			'post_parent' => $podcast->ID,
		) ) );
	}
endif;

if ( ! function_exists( 'ee_get_episodes_count' ) ) :
	function ee_get_episodes_count( $podcast = null ) {
		$podcast = get_post( $podcast );
		$query = ee_get_episodes_query( $podcast, array(
			'posts_per_page' => 1,
			'fields'         => 'ids',
		) );

		return $query->found_posts;
	}
endif;
