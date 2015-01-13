<?php

/**
 * helper function to return a string for image attribution
 *
 * @param $post_id
 *
 * @return mixed
 */
function get_image_attribution( $post_id ) {

	$image_attribution = get_post_meta( $post_id, 'gmr_image_attribution', true );

	if ( ! empty( $image_attribution ) ) {
		return $image_attribution;
	}

}

/**
 * helper function to echo the image attribution
 */
function image_attribution() {

	$image_attribution = get_image_attribution();

	if ( ! empty( $image_attribution ) ) {
		echo $image_attribution;
	}

}