<?php

function bbgi_post_has_gallery( $post = null ) {
	$post = get_post( $post );
	return is_a( $post, '\WP_Post' )
		? stripos( $post->post_content, '[gallery' ) !== false
		: false;
}
