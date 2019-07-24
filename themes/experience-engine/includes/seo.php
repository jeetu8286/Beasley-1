<?php

function ee_update_head_metadata() {
	if ( is_single() ) {
		$post_id        = get_the_ID();
		$article_author = ee_get_opengraph_article_author( $post_id );

		if ( ! empty( $article_author ) ) {
			?>

				<meta property="article:author" content="<?php echo esc_url( $article_author ); ?>" />;w

			<?php
		}
	}
}

function ee_get_opengraph_article_author( $post_id ) {
	$post = get_post( $post_id );
	$author = $post->post_author;

	if ( ! empty( $author ) ) {
		$user = get_user_by( 'ID', $author );

		if ( ! empty( $user->facebook ) ) {
			return $user->facebook;
		} else {
			return get_author_posts_url( $author );
		}
	} else {
		return false;
	}
}

add_action( 'wp_head', 'ee_update_head_metadata' );
