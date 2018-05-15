<?php

$dimension_width = $dimension_height = false;

add_action( 'wpseo_add_opengraph_images', function( \WPSEO_OpenGraph_Image $opengraph ) use ( $dimension_width, $dimension_height ) {
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
			$src = wp_get_attachment_image_src( $image->ID, 'full' );
			if ( ! empty( $src ) && count( $src ) == 3 ) {
				$opengraph->add_image( $src[0] );
				$dimension_width = $src[1];
				$dimension_height = $src[2];
				add_filter( 'wpseo_opengraph_image', '__return_empty_string' );
				break;
			}
		}
	}
} );

add_filter( 'wpseo_og_og:image', function( $content ) use ( $dimension_width, $dimension_height ) {
	if ( ! empty( $dimension_width ) ) {
		echo '<meta property="og:image:width" content="', esc_attr( $dimension_width ), '">', "\n";
	}

	if ( ! empty( $dimension_height ) ) {
		echo '<meta property="og:image:height" content="', esc_attr( $dimension_height ), '">', "\n";
	}

	return $content;
} );

add_filter( 'wpseo_canonical', function( $canonical ) {
	$view = get_query_var( 'view' );
	if ( empty( $view ) ) {
		return $canonical;
	}

	$post = get_queried_object();

	return sprintf(
		'%s/view/%s/',
		untrailingslashit(get_permalink( $post->ID ) ),
		urlencode( $view )
	);
} );

get_header();

get_template_part( 'content-gallery', !! get_query_var( 'view' ) ? 'slideshow' : '' );

get_footer();
