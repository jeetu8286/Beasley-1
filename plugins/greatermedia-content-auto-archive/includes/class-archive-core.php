<?php

class GMR_Archive_Core {

	/**
	 * Setups class hooks.
	 *
	 * @access public
	 */
	public function setup() {
		add_action( 'init', array( $this, 'register_post_status' ) );
	}

	function register_post_status() {
		$args = array(
			'label'                     => __( 'Archived', 'greatermedia' ),
			'public'                    => false,
			'private'                   => true,
			'exclude_from_search'       => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Archived <span class="count">(%s)</span>', 'Archived <span class="count">(%s)</span>', 'greatermedia' ),
		);

		register_post_status( GMR_AUTO_ARCHIVE_POST_STATUS, $args );
	}
}