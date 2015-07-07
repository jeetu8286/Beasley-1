<?php

global $gmr_loadmore_num_pages, $gmr_loadmore_post_count, $gmr_loadmore_paged;

if ( $query->have_posts() ) :

	while ( $query->have_posts() ) : $query->the_post();

		if ( in_array( get_the_ID(), $rendered_posts ) ) {
			continue;
		}

		$rendered_posts[ get_the_ID() ] = get_the_ID();

		get_template_part( 'partials/gallery-grid' );

	endwhile;

	wp_reset_query();

	$gmr_loadmore_paged = get_query_var( 'paged', 1 );
	if ( $gmr_loadmore_paged < 2 ) :
		greatermedia_load_more_button( array(
			'query'        => $query,
			'partial_slug' => 'partials/gallery-grid',
		) );
	endif;

	$gmr_loadmore_num_pages = $query->max_num_pages;
	$gmr_loadmore_post_count = $query->post_count;

endif;