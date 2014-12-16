<?php
/**
 * Handles filtering and additional columns on the post-listing screen
 *
 * static class for consistency
 */

class GreaterMediaGalleriesPostList {

	public static function init() {
		add_filter( 'manage_' . GreaterMediaGalleryCPT::GALLERY_POST_TYPE . '_posts_columns', array( __CLASS__, 'add_album_column' ) );
		add_filter( 'manage_' . GreaterMediaGalleryCPT::GALLERY_POST_TYPE . '_posts_custom_column', array( __CLASS__, 'render_custom_column' ), 10, 2 );
	}

	public static function add_album_column( $columns ) {
		$first = array_slice( $columns, 0, 3 );
		$first['album'] = 'Album';

		$columns = array_merge( $first, $columns );

		return $columns;
	}

	public static function render_custom_column( $column, $post_id ) {
		switch( $column ) {
			case 'album':
				$post = get_post( $post_id );
				if ( $post->post_parent > 0 ) {
					$parent = get_post( $post->post_parent );
					echo esc_html( $parent->post_title );
				}
				break;
		}
	}

}

GreaterMediaGalleriesPostList::init();
