<?php

namespace Greater_Media\Flexible_Feature_Images;

function feature_image_preference_is($post = null, $feature_image_preference = null) {
	$post = get_post($post);
	if ( ! $post ) {
		return;
	}

	$preference = get_post_meta( $post->ID, 'post_feature_image_preference', true );

	// Match to current meta data.  Default to 'poster' if none already specified in post meta.
	return $preference
		? ( $feature_image_preference === $preference )
		: ( 'top' === $feature_image_preference );
}
