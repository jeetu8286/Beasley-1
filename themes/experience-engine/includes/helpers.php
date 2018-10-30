<?php

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

if ( !function_exists( 'ee_the_query_tiles' ) ) :
	function ee_the_query_tiles( $query ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'partials/tile', get_post_type() );
		}

		wp_reset_postdata();
	}
endif;
