<?php

class GMR_Cleanup_Cron {

	/**
	 * Setups cleanup cron class instance.
	 *
	 * @access public
	 */
	public function setup() {
		add_action( GMR_CLEANUP_CRON, array( $this, 'queue_up_cleanup' ) );
		add_action( GMR_CLEANUP_ASYNC_TASK, array( $this, 'do_cleanup' ) );
	}

	/**
	 * Handles cleanup cron.
	 *
	 * @access public
	 */
	public function queue_up_cleanup() {
		// do nothing if cron is disabled
		if ( 1 != get_option( GMR_CLEANUP_STATUS_OPTION ) ) {
			return;
		}

		// fetch authors and return if no authors are found
		$authors = explode( ',', get_option( GMR_CLEANUP_AUTHORS_OPTION ) );
		$authors = array_filter( array_map( array( $this, 'map_author' ), $authors ) );
		if ( empty( $authors ) ) {
			return;
		}

		// fetch age and return if age is empty
		$age = intval( get_option( GMR_CLEANUP_AGE_OPTION ) );
		if ( $age < 1 ) {
			return;
		}

		// fetch post types and return if its empty
		$types = get_option( GMR_CLEANUP_CPT_OPTION, array() );
		$types = array_filter( $types, 'post_type_exists' );
		if ( empty( $types ) ) {
			return;
		}

		// add async task to cleanup content
		if ( function_exists( 'wp_async_task_add' ) ) {
			wp_async_task_add( GMR_CLEANUP_ASYNC_TASK, array(
				'authors' => $authors,
				'age'     => $age,
				'types'   => $types,
			) );
		}
	}

	/**
	 * Cleans up content on the site.
	 *
	 * @access public
	 * @param array $args The array of async task arguments.
	 */
	public function do_cleanup( $args ) {
		$query = new WP_Query();
		$query_args = array(
			'author__in'          => $args['authors'],
			'post_type'           => $args['types'],
			'post_status'         => 'any',
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

		// remove action hook which would block us
		remove_action( 'before_delete_post', 'gmr_contests_prevent_hard_delete' );

		do {
			// fetch posts
			$query->query( $query_args );

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
	}

	/**
	 * Maps author name and returns author ID.
	 *
	 * @access public
	 * @param type $author
	 * @return type
	 */
	public function map_author( $author ) {
		$user = get_user_by( 'login', $author );
		return ! empty( $user ) ? $user->ID : false;
	}

}