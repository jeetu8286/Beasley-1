<?php

add_filter( 'next_posts_link_attributes', 'ee_load_more_attributes' );
add_filter( 'get_the_archive_title', 'ee_update_archive_title' );

if ( ! function_exists( 'ee_the_date' ) ) :
	function ee_the_date( $post = null ) {
		$post = get_post( $post );

		$created = mysql2date( 'G', $post->post_date_gmt );
		$now = current_time( 'timestamp', 1 );

		$elapsed = abs( $now - $created );
		if ( $elapsed < DAY_IN_SECONDS ) {
			if ( $elapsed < HOUR_IN_SECONDS ) {
				$number = floor( $elapsed / MINUTE_IN_SECONDS );
				return printf( $number == 1 ? '%d minute ago' : '%d minutes ago', $number );
			} else {
				$number = floor( $elapsed / HOUR_IN_SECONDS );
				return printf( $number == 1 ? '%s hour ago' : '%s hours ago', $number );
			}
		}

		$created_offset = $created + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
		if ( date( 'Y' ) == date( 'Y', $created_offset ) ) {
			return print( date( 'M jS', $created_offset ) );
		}

		return printf( date( 'M jS, Y', $created_offset ) );
	}
endif;

if ( ! function_exists( 'ee_the_query_tiles' ) ) :
	function ee_the_query_tiles( $query ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'partials/tile', get_post_type() );
		}

		wp_reset_postdata();
	}
endif;

if ( ! function_exists( 'ee_load_more' ) ) :
	function ee_load_more( $query = null ) {
		if ( $query ) {
			$GLOBALS['wp_query'] = $query;
		}

		next_posts_link( 'Load More' );

		if ( $query ) {
			wp_reset_query();
		}
	}
endif;

if ( ! function_exists( 'ee_load_more_attributes' ) ) :
	function ee_load_more_attributes() {
		return 'class="load-more"';
	}
endif;

if ( ! function_exists( 'ee_is_first_page' ) ) :
	function ee_is_first_page() {
		return get_query_var( 'paged', 1 ) < 2;
	}
endif;

if ( ! function_exists( 'ee_update_archive_title' ) ) :
	function ee_update_archive_title( $title ) {
		$parts = explode( ':', $title, 2 );
		return array_pop( $parts );
	}
endif;
