<?php

class GMR_Archive_Cron {

	/**
	 * Setups cleanup cron class instance.
	 *
	 * @access public
	 */
	public function setup() {
		add_action( GMR_AUTO_ARCHIVE_CRON, array( $this, 'queue_up_cleanup' ) );
		add_action( GMR_AUTO_ARCHIVE_ASYNC_TASK, array( $this, 'do_cleanup' ) );
	}

	/**
	 * Handles cleanup cron.
	 *
	 * @access public
	 */
	public function queue_up_cleanup() {
		// do nothing if cron is disabled
		$days = absint( get_option( GMR_AUTO_ARCHIVE_OPTION_NAME, 0 ) );
		if ( $days < 1 ) {
			return;
		}

		// add async task to cleanup content
		if ( function_exists( 'wp_async_task_add' ) ) {
			wp_async_task_add( GMR_AUTO_ARCHIVE_ASYNC_TASK, array( 'age' => $days ) );
		}
	}

	/**
	 * Cleans up content on the site.
	 *
	 * @access public
	 *
	 * @param array $args The array of async task arguments.
	 */
	public function do_cleanup( $args ) {
		$query      = new WP_Query();
		$query_args = array(
			'post_status'         => 'publish',
			'suppress_filters'    => true,
			'posts_per_page'      => 100,
			'ignore_sticky_posts' => true,
			'fields'              => 'ids',
			'no_found_rows'       => true,
			'date_query'          => array(
				array(
					'column' => 'post_date_gmt',
					'before' => $args['age'] . ' days ago',
				),
			),
		);

		do {
			// fetch posts
			$query->query( $query_args );

			// delete found posts
			while ( $query->have_posts() ) {
				$post_id = $query->next_post();

				// delete post
				wp_update_post( $post_id, array( 'post_status' => GMR_AUTO_ARCHIVE_POST_STATUS ) );
			}
		} while ( $query->post_count > 0 );
	}

}