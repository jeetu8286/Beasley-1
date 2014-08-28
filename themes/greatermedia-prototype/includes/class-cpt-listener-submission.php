<?php

/**
 * Establishes the custom post type for Listener Submissions; aka User Generated Content (UGC)
 */
class GMI_Listener_Submission {

	function __construct() {
		add_action( 'init', array( __CLASS__, 'register_cpt' ) );
	}

	public function register_cpt() {
		$labels = array(
			'name'               => 'Listener Submissions',
			'singular_name'      => 'Listener Submission',
			'add_new'            => 'Add New Listener Submission',
			'all_items'          => 'All Listener Submissions',
			'add_new_item'       => 'Add New Listener Submission',
			'edit_item'          => 'Edit Listener Submission',
			'new_item'           => 'New Listener Submission',
			'view_item'          => 'View Listener Submission',
			'search_items'       => 'Search Listener Submissions',
			'not_found'          => 'No listener submissions found',
			'not_found_in_trash' => 'No listener submissions found in trash',
			'parent_item_colon'  => 'Parent Listener Submission:',
			'menu_name'          => 'Listener Submissions'
		);

		$args = array(
			'labels'      => $labels,
			'description' => 'Listener Submissions',
			'public'      => true,
			'supports'    => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'     => array( 'slug' => 'listener-submissions' ),
			'menu_icon'   => ''
		);

		register_post_type( 'gmi_listener_submit', $args );

	}
}

$GMI_Listener_Submission = new GMI_Listener_Submission();