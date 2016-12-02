<?php

class GMR_Content_Cleanup extends WP_CLI_Command {

	/**
	 * Cleans up getty images.
	 *
	 * @synopsis [--verbose]
	 *
	 * @access public
	 * @param array $args The array of arguments.
	 * @param array $assoc_args The array of associted arguments.
	 */
	public function getty( $args, $assoc_args ) {
		$query = new WP_Query();
		$verbose = ! empty( $assoc_args['verbose'] );

		// remove action hook which would block us
		remove_action( 'before_delete_post', 'gmr_contests_prevent_hard_delete' );

		do {
			// fetch posts
			$query->query( array(
				'post_type'           => 'attachment',
				'post_status'         => 'any',
				'suppress_filters'    => true,
				'posts_per_page'      => 100,
				'ignore_sticky_posts' => true,
				'fields'              => 'ids',
				'no_found_rows'       => true,
				'meta_query'          => array(
					array(
						'key'     => 'gmr_image_attribution',
						'value'   => 'Getty',
						'compare' => 'LIKE',
					),
				),
			) );

			// delete found posts
			while ( $query->have_posts() ) {
				$post_id = $query->next_post();
				wp_delete_post( $post_id, true );
				WP_CLI::success( "#{$post_id} deleted" );
			}
		} while( $query->post_count > 0 );

		$verbose && WP_CLI::success( 'Clean up completed.' );
	}

}