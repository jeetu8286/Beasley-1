<?php

function get_image_attribution( $post_id ) {

	$image_attribution = get_post_meta( $post_id, 'gmr_image_attribution', true );

	if ( ! empty( $image_attribution ) ) {
		return $image_attribution;
	}

}

function image_attribution() {

	$image_attribution = get_image_attribution();

	if ( ! empty( $image_attribution ) ) {
		echo $image_attribution;
	}

}