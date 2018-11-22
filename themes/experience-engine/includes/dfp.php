<?php

add_filter( 'bbgiconfig', 'ee_update_dfp_bbgiconfig', 50 );

if ( ! function_exists( 'ee_update_dfp_bbgiconfig' ) ) :
	function ee_update_dfp_bbgiconfig( $config ) {
		$config['dfp'] = array(
			'global' => ee_get_dfp_global_targeting(),
		);

		return $config;
	}
endif;

if ( ! function_exists( 'ee_get_dfp_global_targeting' ) ) :
	function ee_get_dfp_global_targeting() {
		static $targeting = null;

		if ( is_null( $targeting ) ) {
			$cpage = ! is_home() && ! is_front_page()
				? untrailingslashit( current( explode( '?', $_SERVER['REQUEST_URI'], 2 ) ) ) // strip query part and trailing slash of the current uri
				: 'home';

			$targeting = array(
				array( 'cdomain', parse_url( home_url( '/' ), PHP_URL_HOST ) ),
				array( 'cpage', $cpage ),
				array( 'genre', trim( strtolower( implode( ',', ee_get_publisher_information( 'genre' ) ) ) ) ),
				array( 'market', trim( strtolower( ee_get_publisher_information( 'location' ) ) ) ),
			);

			if ( is_singular() ) {
				$post_id = get_queried_object_id();
				$targeting[] = array( 'cpostid', "{$post_id}" );

				if ( class_exists( 'ShowsCPT' ) && defined( 'ShowsCPT::SHOW_TAXONOMY' ) ) {
					$terms = get_the_terms( $post_id, ShowsCPT::SHOW_TAXONOMY );
					if ( $terms && ! is_wp_error( $terms ) ) {
						$targeting[] = array( 'shows', implode( ",", wp_list_pluck( $terms, 'slug' ) ) );
					}
				}

				if ( class_exists( 'GMP_CPT' ) && defined( 'GMP_CPT::PODCAST_POST_TYPE' ) && defined( 'GMP_CPT::EPISODE_POST_TYPE' ) ) {
					$podcast = false;

					$post = get_post( $post_id );
					$post_type = get_post_type( $post );
					if ( GMP_CPT::PODCAST_POST_TYPE == $post_type ) {
						$podcast = $post->post_name;
					}

					if ( GMP_CPT::EPISODE_POST_TYPE == $post_type ) {
						$parent_podcast_id = wp_get_post_parent_id( $post );
						if ( $parent_podcast_id && ! is_wp_error( $parent_podcast_id ) ) {
							$parent_podcast = get_post( $parent_podcast_id );
							$podcast = $parent_podcast->post_name;
						}
					}

					if ( $podcast ) {
						$targeting[] = array( 'podcasts', $podcast );
					}
				}

				$categories = wp_get_post_categories( get_queried_object_id() );
				if ( ! empty( $categories ) ) {
					$categories = array_filter( array_map( 'get_category', $categories ) );
					$categories = wp_list_pluck( $categories, 'slug' );
					$targeting[] = array( 'categories', implode( ',', $categories ) );
				}
			} elseif ( is_category() ) {
				$category = get_queried_object();
				$targeting[] = array( 'categories', $category->slug );
			}
		}

		return $targeting;
	}
endif;
