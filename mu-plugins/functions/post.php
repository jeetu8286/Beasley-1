<?php

/**
 * Returns TRUE if a post has [gallery] shortcode, otherwise FALSE.
 *
 * @param int|\WP_Post $post The post to check.
 * @return boolean TRUE if a post has gallery, otherwise FALSE.
 */
function bbgi_post_has_gallery( $post = null ) {
	$post = get_post( $post );
	return is_a( $post, '\WP_Post' )
		? stripos( $post->post_content, '[gallery' ) !== false
		: false;
}
