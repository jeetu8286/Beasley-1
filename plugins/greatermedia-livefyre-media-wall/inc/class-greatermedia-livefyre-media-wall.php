<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaLiveFyreMediaWall {

	function __construct() {

		add_action( 'init', array( $this, 'init' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );

	}

	public function init() {

		// Generated using http://generatewp.com/post-type/
		$labels = array(
			'name'               => _x( 'LiveFyre Media Walls', 'Post Type General Name', 'greatermedia-livefyre-media-wall' ),
			'singular_name'      => _x( 'LiveFyre Media Wall', 'Post Type Singular Name', 'greatermedia-livefyre-media-wall' ),
			'menu_name'          => __( 'Media Wall', 'greatermedia-livefyre-media-wall' ),
			'parent_item_colon'  => __( 'Parent Wall:', 'greatermedia-livefyre-media-wall' ),
			'all_items'          => __( 'All Walls', 'greatermedia-livefyre-media-wall' ),
			'view_item'          => __( 'View Wall', 'greatermedia-livefyre-media-wall' ),
			'add_new_item'       => __( 'Add New Wall', 'greatermedia-livefyre-media-wall' ),
			'add_new'            => __( 'Add a Wall', 'greatermedia-livefyre-media-wall' ),
			'edit_item'          => __( 'Edit Wall', 'greatermedia-livefyre-media-wall' ),
			'update_item'        => __( 'Update Wall', 'greatermedia-livefyre-media-wall' ),
			'search_items'       => __( 'Search Wall', 'greatermedia-livefyre-media-wall' ),
			'not_found'          => __( 'Not found', 'greatermedia-livefyre-media-wall' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'greatermedia-livefyre-media-wall' ),
		);
		$args   = array(
			'label'               => __( 'livefyre_media_wall', 'greatermedia-livefyre-media-wall' ),
			'description'         => __( 'LiveFyre Media Wall', 'greatermedia-livefyre-media-wall' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( 'livefyre_media_wall', $args );

	}

	public function add_meta_boxes() {

		add_meta_box(
			'media_wall_id',
			__( 'Media Wall', 'greatermedia-livefyre-media-wall' ),
			array( $this, 'media_wall_id_meta_box' ),
			'livefyre_media_wall',
			'normal',
			'high'
		);

	}

	public function media_wall_id_meta_box( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'media_wall_meta_box', 'media_wall_meta_box' );

		/*
		 * Use get_post_meta() to retrieve an existing value
		 * from the database and use the value for the form.
		 */
		$value = get_post_meta( $post->ID, 'media_wall_id', true );

		echo '<label for="media_wall_id">';
		_e( 'Media Wall ID', 'greatermedia-livefyre-media-wall' );
		echo '</label> ';
		echo '<p><input type="text" id="media_wall_id" name="media_wall_id" value="' . esc_attr( $value ) . '" size="25" /></p>';

	}

	public function save_post( $post_id ) {

		// Check if our nonce is set.
		if ( ! isset( $_POST['media_wall_meta_box'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['media_wall_meta_box'], 'media_wall_meta_box' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Make sure the post type is correct
		if ( ! isset( $_POST['post_type'] ) || 'livefyre_media_wall' !== $_POST['post_type'] ) {
			return;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Make sure that it is set.
		if ( ! isset( $_POST['media_wall_id'] ) ) {
			return;
		}

		$media_wall_id = absint( $_POST['media_wall_id'] );
		delete_post_meta( $post_id, 'media_wall_id' );
		update_post_meta( $post_id, 'media_wall_id', $media_wall_id );

	}
}

$GreaterMediaLiveFyreMediaWall = new GreaterMediaLiveFyreMediaWall();