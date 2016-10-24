<?php

class GMR_Content_Cleanup extends WP_CLI_Command {

	/**
	 * Cleans up the site.
	 *
	 * @synopsis [--verbose]
	 *
	 * @access public
	 * @param array $args The array of arguments.
	 * @param array $assoc_args The array of associted arguments.
	 */
	public function cleanup( $args, $assoc_args ) {
		$query = new WP_Query();
		$verbose = ! empty( $assoc_args['verbose'] );

		// remove action hook which would block us
		remove_action( 'before_delete_post', 'gmr_contests_prevent_hard_delete' );

		// types to cleanup
		$types = array(
			'post',
			'listener_submissions',
			'contest_entry',
			'contest',
			'survey_response',
			'survey',
			'episode',
			'gmr_gallery',
			'gmr_album',
			'gmr-live-link',
			'songs',
			'tribe_events',
		);

		do {
			// fetch posts
			$query->query( array(
				'post_type'           => $types,
				'post_status'         => 'any',
				'suppress_filters'    => true,
				'posts_per_page'      => 100,
				'ignore_sticky_posts' => true,
				'fields'              => 'ids',
				'no_found_rows'       => true,
			) );

			// delete found posts
			while ( $query->have_posts() ) {
				$post_id = $query->next_post();

				// delete attachments of available
				$attachments = get_attached_media( null, $post_id );
				foreach ( $attachments as $attachment ) {
					wp_delete_post( $attachment->ID, true );
				}

				// delete children if available
				$children = get_children( array( 'post_parent' => $post_id ) );
				foreach ( $children as $child ) {
					wp_delete_post( $child->ID, true );
				}

				// delete post
				wp_delete_post( $post_id, true );
			}
		} while( $query->post_count > 0 );

		$verbose && WP_CLI::success( 'Clean up completed.' );
	}

}