<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaContestEntryMetaboxes {

	function __construct() {

		add_action( 'custom_metadata_manager_init_metadata', array( $this, 'custom_metadata_manager_init_metadata' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

	}

	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'dashicons' );
	}

	public function custom_metadata_manager_init_metadata() {

		// Groups
		x_add_metadata_group(
			'contestant',
			array( 'contest_entry' ),
			array(
				'label' => 'Contestant', // Label for the group
			)
		);

		// Fields
		x_add_metadata_field(
			'contestant-name',
			array( 'contest_entry' ),
			array(
				'group'      => 'contestant', // The slug of group the field should be added to. This needs to be registered with x_add_metadata_group first.
				'field_type' => 'text', // The type of field; 'text', 'textarea', 'password', 'checkbox', 'radio', 'select', 'upload', 'wysiwyg', 'datepicker', 'taxonomy_select', 'taxonomy_radio'
				'label'      => 'Contestant', // Label for the field
			)
		);

		x_add_metadata_field(
			'email',
			array( 'contest_entry' ),
			array(
				'group'      => 'contestant', // The slug of group the field should be added to. This needs to be registered with x_add_metadata_group first.
				'field_type' => 'text', // The type of field; 'text', 'textarea', 'password', 'checkbox', 'radio', 'select', 'upload', 'wysiwyg', 'datepicker', 'taxonomy_select', 'taxonomy_radio'
				'label'      => 'Email', // Label for the field
			)
		);

		x_add_metadata_field(
			'email',
			array( 'contest_entry' ),
			array(
				'group'      => 'contestant', // The slug of group the field should be added to. This needs to be registered with x_add_metadata_group first.
				'field_type' => 'text', // The type of field; 'text', 'textarea', 'password', 'checkbox', 'radio', 'select', 'upload', 'wysiwyg', 'datepicker', 'taxonomy_select', 'taxonomy_radio'
				'label'      => 'Email', // Label for the field
			)
		);

	}

	public function add_meta_boxes() {

		add_meta_box(
			'parent-contest',
			'Contest',
			array( $this, 'contest_meta_box' ),
			'contest_entry',
			'advanced',
			'default',
			array()
		);

		add_meta_box(
			'contest-entries',
			'Listener Submissions',
			array( $this, 'submissions_meta_box' ),
			'contest_entry',
			'advanced',
			'default',
			array()
		);

	}

	public function contest_meta_box() {

		global $post;

		$contest       = get_post( $post->post_parent );
		$start_date    = get_post_meta( $contest->ID, 'start-date', true );
		$end_date      = get_post_meta( $contest->ID, 'end-date', true );
		$contest_types = get_the_terms( $contest->ID, 'contest_type' );

		include trailingslashit( GREATER_MEDIA_CONTESTS_PATH ) . 'tpl/contest-meta-box.tpl.php';

	}

	public function submissions_meta_box() {

		global $post;

		$entries = get_children(
			array(
				'post_parent'    => $post->ID,
				'post_type'      => 'listener_submissions',
				'posts_per_page' => - 1,
				'post_status'    => array( 'pending', 'publish' )
			)
		);

		include trailingslashit( GREATER_MEDIA_CONTESTS_PATH ) . 'tpl/listener-submissions-meta-box.tpl.php';

	}
}

$GreaterMediaContestEntryMetaboxes = new GreaterMediaContestEntryMetaboxes();