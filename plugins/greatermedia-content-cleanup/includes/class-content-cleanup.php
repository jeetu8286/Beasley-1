<?php

class GMR_Content_Cleanup extends WP_CLI_Command {

	/**
	 * Removes posts based on provided query.
	 *
	 * @access private
	 * @global \wpdb $wpdb
	 * @param string $query
	 * @param boolean $verbose
	 */
	private function _cleanup( $query, $verbose ) {
		global $wpdb;

		// remove action hook which would block us
		remove_action( 'before_delete_post', 'gmr_contests_prevent_hard_delete' );

		do {
			// fetch posts
			$images = $wpdb->get_col( $query );

			// delete found posts
			foreach ( $images as $image ) {
				wp_delete_post( $image, true );
				WP_CLI::success( "#{$image} deleted" );
			}
		} while( count( $images ) > 0 );

		$verbose && WP_CLI::success( 'Clean up completed.' );
	}

	/**
	 * Cleans up getty images.
	 *
	 * @synopsis [--verbose]
	 *
	 * @access public
	 * @global \wpdb $wpdb
	 * @param array $args The array of arguments.
	 * @param array $assoc_args The array of associted arguments.
	 */
	public function getty( $args, $assoc_args ) {
		global $wpdb;

		$prefix = $wpdb->get_blog_prefix();

		$query = <<<EOQ
SELECT {$prefix}posts.ID
  FROM {$prefix}posts
 INNER JOIN {$prefix}postmeta ON {$prefix}posts.ID = {$prefix}postmeta.post_id
 WHERE {$prefix}posts.post_type = 'attachment'
   AND ({$prefix}posts.post_excerpt LIKE '%Getty%' OR ({$prefix}postmeta.meta_key = 'gmr_image_attribution' AND {$prefix}postmeta.meta_value LIKE '%Getty%'))
 GROUP BY {$prefix}posts.ID
 ORDER BY {$prefix}posts.post_date DESC
 LIMIT 0, 100
EOQ;

		$this->_cleanup( $query, ! empty( $assoc_args['verbose'] ) );
	}

	/**
	 * Cleans up video thumbnail images.
	 *
	 * @synopsis [--verbose]
	 *
	 * @access public
	 * @global \wpdb $wpdb
	 * @param array $args The array of arguments.
	 * @param array $assoc_args The array of associted arguments.
	 */
	public function videothumbs( $args, $assoc_args ) {
		global $wpdb;

		$prefix = $wpdb->get_blog_prefix();

		$query = <<<EOQ
SELECT {$prefix}posts.ID
  FROM {$prefix}posts
 INNER JOIN {$prefix}postmeta ON {$prefix}posts.ID = {$prefix}postmeta.post_id
 WHERE {$prefix}posts.post_type = 'attachment'
   AND {$prefix}postmeta.meta_key = 'video_thumbnail'
   AND {$prefix}postmeta.meta_value = 1
 GROUP BY {$prefix}posts.ID
 ORDER BY {$prefix}posts.post_date DESC
 LIMIT 0, 100
EOQ;

		$this->_cleanup( $query, ! empty( $assoc_args['verbose'] ) );
	}

}