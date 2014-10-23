<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaContestsMetaboxes {

	function __construct() {

		add_action( 'custom_metadata_manager_init_metadata', array(
			$this,
			'custom_metadata_manager_init_metadata'
		), 20, 3 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );

	}

	/**
	 * Implements admin_enqueue_scripts action
	 */
	public function admin_enqueue_scripts() {

		global $post;

		wp_enqueue_style( 'formbuilder-vendor', trailingslashit( GREATER_MEDIA_CONTESTS_URL ) . 'bower_components/formbuilder/vendor/css/vendor.css' );
		wp_enqueue_style( 'formbuilder', trailingslashit( GREATER_MEDIA_CONTESTS_URL ) . 'bower_components/formbuilder/dist/formbuilder.css' );

		wp_enqueue_script( 'formbuilder-vendor', trailingslashit( GREATER_MEDIA_CONTESTS_URL ) . 'bower_components/formbuilder/vendor/js/vendor.js' );
		wp_enqueue_script( 'formbuilder', trailingslashit( GREATER_MEDIA_CONTESTS_URL ) . 'bower_components/formbuilder/dist/formbuilder.js' );

		$embedded_form = get_post_meta( $post->ID, 'embedded_form', true );
		$settings = array(
			'form' => $embedded_form,
		);
		wp_localize_script( 'formbuilder', 'GreaterMediaContestsForm', $settings );
	}

	public function custom_metadata_manager_init_metadata() {

		// Groups
		x_add_metadata_group(
			'prizes',
			array( 'contest' ),
			array(
				'label' => 'Prizes', // Label for the group
			)
		);

		x_add_metadata_group(
			'how-to-enter',
			array( 'contest' ),
			array(
				'label' => 'How to Enter', // Label for the group
			)
		);

		x_add_metadata_group(
			'rules',
			array( 'contest' ),
			array(
				'label' => 'Rules', // Label for the group
			)
		);

		x_add_metadata_group(
			'dates',
			array( 'contest' ),
			array(
				'label' => 'Eligible Dates', // Label for the group
			)
		);

		// Fields
		x_add_metadata_field(
			'prizes-desc',
			array( 'contest' ),
			array(
				'group'      => 'prizes',
				// The slug of group the field should be added to. This needs to be registered with x_add_metadata_group first.
				'field_type' => 'wysiwyg',
				// The type of field; 'text', 'textarea', 'password', 'checkbox', 'radio', 'select', 'upload', 'wysiwyg', 'datepicker', 'taxonomy_select', 'taxonomy_radio'
				'label'      => 'What You Win',
				// Label for the field
			)
		);

		x_add_metadata_field(
			'how-to-enter-desc',
			array( 'contest' ),
			array(
				'group'      => 'how-to-enter',
				// The slug of group the field should be added to. This needs to be registered with x_add_metadata_group first.
				'field_type' => 'wysiwyg',
				// The type of field; 'text', 'textarea', 'password', 'checkbox', 'radio', 'select', 'upload', 'wysiwyg', 'datepicker', 'taxonomy_select', 'taxonomy_radio'
				'label'      => null,
				// Label for the field
			)
		);

		x_add_metadata_field(
			'rules-desc',
			array( 'contest' ),
			array(
				'group'      => 'rules',
				// The slug of group the field should be added to. This needs to be registered with x_add_metadata_group first.
				'field_type' => 'wysiwyg',
				// The type of field; 'text', 'textarea', 'password', 'checkbox', 'radio', 'select', 'upload', 'wysiwyg', 'datepicker', 'taxonomy_select', 'taxonomy_radio'
				'label'      => 'Official Contest Rules',
				// Label for the field
			)
		);

		x_add_metadata_field(
			'start-date',
			array( 'contest' ),
			array(
				'group'      => 'dates',
				// The slug of group the field should be added to. This needs to be registered with x_add_metadata_group first.
				'field_type' => 'datepicker',
				// The type of field; 'text', 'textarea', 'password', 'checkbox', 'radio', 'select', 'upload', 'wysiwyg', 'datepicker', 'taxonomy_select', 'taxonomy_radio'
				'label'      => 'Start Date',
				// Label for the field
			)
		);

		x_add_metadata_field(
			'end-date',
			array( 'contest' ),
			array(
				'group'      => 'dates',
				// The slug of group the field should be added to. This needs to be registered with x_add_metadata_group first.
				'field_type' => 'datepicker',
				// The type of field; 'text', 'textarea', 'password', 'checkbox', 'radio', 'select', 'upload', 'wysiwyg', 'datepicker', 'taxonomy_select', 'taxonomy_radio'
				'label'      => 'End Date',
				// Label for the field
			)
		);

	}

	public function add_meta_boxes() {

		add_meta_box(
			'form',
			'Form',
			array( $this, 'contest_embedded_form' ),
			'contest',
			'advanced',
			'default',
			array()
		);

		add_meta_box(
			'contest-entries',
			'Contest Entries',
			array( $this, 'contest_entries_meta_box' ),
			'contest',
			'advanced',
			'default',
			array()
		);

	}

	public function contest_embedded_form() {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'contest_form_meta_box', 'contest_form_meta_box' );

		include trailingslashit( GREATER_MEDIA_CONTESTS_PATH ) . 'tpl/contest-form-meta-box.tpl.php';

	}

	public function contest_entries_meta_box() {

		global $post;

		$entries = get_children(
			array(
				'post_parent'    => $post->ID,
				'post_type'      => 'contest_entry',
				'posts_per_page' => - 1,
				'post_status'    => array( 'pending', 'publish' )
			)
		);

		include trailingslashit( GREATER_MEDIA_CONTESTS_PATH ) . 'tpl/contest-entries-meta-box.tpl.php';

	}

	public function save_post( $post_id ) {

		// Check if our nonce is set.
		if ( ! isset( $_POST['contest_form_meta_box'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['contest_form_meta_box'], 'contest_form_meta_box' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Make sure the post type is correct
		if ( ! isset( $_POST['post_type'] ) || 'contest' !== $_POST['post_type'] ) {
			return;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Using json_decode() + json_encode() as a form of JSON sanitization
		$form = wp_kses_stripslashes( $_POST['contest_embedded_form'] );
		delete_post_meta( $post_id, 'embedded_form' );
		update_post_meta( $post_id, 'embedded_form', $form );

	}

}

$GreaterMediaContestsMetaboxes = new GreaterMediaContestsMetaboxes();