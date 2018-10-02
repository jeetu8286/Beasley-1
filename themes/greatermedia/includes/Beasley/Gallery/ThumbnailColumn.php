<?php

namespace Beasley\Gallery;

class ThumbnailColumn extends \Beasley\Module {

	/**
	 * Registers this module.
	 *
	 * @access public
	 */
	public function register() {
		add_filter( 'manage_posts_columns', array( $this, 'filter_columns' ), 10, 2 );
		add_action( 'manage_posts_custom_column', array( $this, 'do_custom_column' ), 10, 2 );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
	}

	/**
	 * Add the custom column to the columns list.
	 *
	 * @param array $columns
	 * @return array
	 */
	public function filter_columns( $columns, $post_type ) {
		// Make sure this is a post type we're handling.
		if ( $post_type != 'gmr_gallery' ) {
			return $columns;
		}

		// Put the thumbnail right after the checkbox.
		return array_merge(
			array_slice( $columns, 0, 1 ),
			array( 'thumbnail' => 'Thumbnail' ),
			array_slice( $columns, 1 )
		);
	}

	/**
	 * Render the custom column.
	 *
	 * @param string $column_name
	 * @param int $post_id
	 */
	public function do_custom_column( $column_name, $post_id ) {
		// Make sure this is our column.
		if ( $column_name != 'thumbnail' ) {
			return;
		}

		// Make sure we have a thumbnail.
		if ( ! has_post_thumbnail( $post_id ) ) {
			return;
		}

		$thumbnail_id = get_post_thumbnail_id( $post_id );
		$url = wp_get_attachment_image_url( $thumbnail_id, 'original' );
		if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
			$url = add_query_arg( array( 'width' => 100, 'height' => 75, 'mode' => 'crop' ), $url );
			echo '<img src="', esc_url( $url ), '" width="100" height="75">';
		}
	}

	/**
	 * Add some CSS to the page head that sets the column width to match the
	 * thumbnail width.
	 */
	public function admin_head() {
		global $typenow, $pagenow;

		if ( $typenow == 'gmr_gallery' && $pagenow == 'edit.php' ) {
			?><style type="text/css">
				.column-thumbnail, .column-thumbnail img { width: 100px; }
			</style><?php
		}
	}

}
