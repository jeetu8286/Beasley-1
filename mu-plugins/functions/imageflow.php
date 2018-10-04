<?php

function bbgi_get_image_url( $image, $width, $height, $mode = 'crop', $max = false ) {
	$image_id = is_a( $image, '\WP_Post' ) ? $image->ID : $image;

	$data = wp_get_attachment_image_src( $image_id, 'original' );
	if ( ! $data ) {
		return false;
	}

	$prefix = $max ? 'max' : '';
	$aspect = ! empty( $data[2] ) ? $data[1] / $data[2] : 1;

	$args = array(
		"{$prefix}width"  => $width,
		"{$prefix}height" => $height,
		'anchor' => $aspect < 1 ? 'topleft' : 'middlecenter',
	);

	if ( $mode ) {
		$args['mode'] = $mode;
	}

	return add_query_arg( $args, $data[0] );
}

function bbgi_post_thumbnail_url( $post_id, $use_fallback, $width, $height, $mode = 'crop', $max = false ) {
	$url = false;

	$thumbnail_id = get_post_thumbnail_id( $post_id );
	if ( ! $thumbnail_id && $use_fallback ) {
		$thumbnail_id = greatermedia_get_fallback_thumbnail_id( $post_id );
	}

	if ( $thumbnail_id ) {
		$url = bbgi_get_image_url( $thumbnail_id, $width, $height, $mode, $max );
		if ( $url ) {
			echo esc_url( $url );
		}
	}
}
