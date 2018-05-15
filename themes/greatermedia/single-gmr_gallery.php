<?php

add_action( 'wpseo_add_opengraph_images', function( \WPSEO_OpenGraph_Image $opengraph ) {
	$view = get_query_var( 'view' );
	if ( empty( $view ) ) {
		return;
	}
	
	$current_gallery = get_queried_object();
	$ids = \GreaterMediaGallery::get_attachment_ids_for_post( $current_gallery );
	if ( ! is_array( $ids ) ) {
		$ids = array();
	}

	$images = array_values( array_filter( array_map( 'get_post', array_values( $ids ) ) ) );
	if ( empty( $images ) ) {
		return;
	}

	foreach ( $images as $image ) {
		if ( $image->post_name == $view ) {
			$opengraph->add_image( wp_get_attachment_image_url( $image->ID, 'full' ) );
			add_filter( 'wpseo_opengraph_image', '__return_empty_string' );
			break;
		}
	}
} );

get_header();

get_template_part( 'content-gallery', !! get_query_var( 'view' ) ? 'slideshow' : '' );

get_footer();
