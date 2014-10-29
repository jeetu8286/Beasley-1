<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

/**
 * Class GreaterMediaLiveFyreMediaWallAdmin
 * LiveFyre Media Wall admin functionality
 */
class GreaterMediaLiveFyreMediaWallAdmin {

	function __construct() {

		add_action( 'save_post', array( $this, 'save_post' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Implements admin_init action
	 * Configures settings on the General Options page
	 */
	public function admin_init() {

		add_settings_section(
			'livefyre-media-walls',
			'LiveFyre Media Walls',
			array( $this, 'render_media_walls_settings' ),
			'general'
		);

		add_settings_field(
			'network',
			'Network ID',
			array( $this, 'render_textbox' ),
			'general',
			'livefyre-media-walls',
			array(
				'id'          => 'livefyre_media_walls_network',
				'name'        => 'livefyre_media_walls_network',
				'option_name' => 'livefyre_media_walls_network'
			)
		);

		add_settings_field(
			'site_id',
			'Site ID',
			array( $this, 'render_textbox' ),
			'general',
			'livefyre-media-walls',
			array(
				'id'          => 'livefyre_media_walls_site',
				'name'        => 'livefyre_media_walls_site',
				'option_name' => 'livefyre_media_walls_site'
			)
		);

		register_setting( 'general', 'livefyre_media_walls_network', 'sanitize_text_field' );
		register_setting( 'general', 'livefyre_media_walls_site', 'sanitize_text_field' );

	}

	/**
	 * Render instructions for the settings section
	 */
	public function render_media_walls_settings() {

	}

	/**
	 * Render a text input for a settings field
	 *
	 * @param array $args
	 */
	function render_textbox( array $args ) {  // Textbox Callback

		$option = get_option( $args['option_name'] );
		echo '<input type="text" id="' . esc_attr( $args['id'] ) . '" name="' . esc_attr( $args['name'] ) . '" value="' . esc_attr( $option ) . '" />';

	}

	/**
	 * Implements admin_enqueue_scripts action
	 */
	public function admin_enqueue_scripts() {

		wp_enqueue_script( 'livefyre-media-wall-admin', trailingslashit( GREATER_MEDIA_LIVEFYRE_WALLS_URL ) . 'js/livefyre-media-wall-admin.js', array( 'jquery' ), false, true );
	}

	/**
	 * Implements save_post action
	 * Saves custom meta fields
	 *
	 * @param int $post_id
	 */
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
		if ( ! isset( $_POST['post_type'] ) || 'livefyre-media-wall' !== $_POST['post_type'] ) {
			return;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Make sure that it is set.
		if ( isset( $_POST['media_wall_id'] ) ) {
			$media_wall_id = sanitize_text_field( $_POST['media_wall_id'] );
			update_post_meta( $post_id, 'media_wall_id', $media_wall_id );
		}

		if ( isset( $_POST['media_wall_initial'] ) ) {
			$media_wall_initial = absint( $_POST['media_wall_initial'] );
			update_post_meta( $post_id, 'media_wall_initial', $media_wall_initial );
		}

		if ( isset( $_POST['media_wall_responsive'] ) ) {
			$media_wall_responsive = $_POST['media_wall_responsive'];
			if ( ! in_array( $media_wall_responsive, array( 'columns', 'min-width' ) ) ) {
				$media_wall_responsive = 'min-width';
			}
			update_post_meta( $post_id, 'media_wall_responsive', $media_wall_responsive );
		}

		if ( isset( $_POST['media_wall_columns'] ) ) {
			$media_wall_columns = absint( $_POST['media_wall_columns'] );
			update_post_meta( $post_id, 'media_wall_columns', $media_wall_columns );
		}

		if ( isset( $_POST['media_wall_min_width'] ) ) {
			$media_wall_min_width = absint( $_POST['media_wall_min_width'] );
			update_post_meta( $post_id, 'media_wall_min_width', $media_wall_min_width );
		}

		if ( isset( $_POST['media_wall_allow_modal'] ) ) {
			$media_wall_modal = sanitize_text_field( $_POST['media_wall_allow_modal'] );
			update_post_meta( $post_id, 'media_wall_allow_modal', $media_wall_modal );
		}

	}

	/**
	 * Implements add_meta_boxes action
	 * Render a meta box for custom meta fields
	 */
	public function add_meta_boxes() {

		add_meta_box(
			'media_wall_id',
			__( 'Media Wall', 'greatermedia-livefyre-media-wall' ),
			array( $this, 'media_wall_id_meta_box' ),
			'livefyre-media-wall',
			'normal',
			'high'
		);

	}

	/**
	 * Render custom meta fields in the custom meta box
	 *
	 * @param WP_Post $post
	 */
	public function media_wall_id_meta_box( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'media_wall_meta_box', 'media_wall_meta_box' );

		/*
		 * Use get_post_meta() t o retrieve an existing value
		 * from the database and use the value for the form.
		 */
		$media_wall_id = get_post_meta( $post->ID, 'media_wall_id', true );

		$media_wall_initial = get_post_meta( $post->ID, 'media_wall_initial', true );
		if ( empty( $media_wall_initial ) ) {
			$media_wall_initial = 50; // LiveFyre's default
		}

		$media_wall_responsive = get_post_meta( $post->ID, 'media_wall_responsive', true );
		if ( empty( $media_wall_responsive ) ) {
			$media_wall_responsive = 'min-width';
		}

		$media_wall_columns = get_post_meta( $post->ID, 'media_wall_columns', true );
		if ( empty( $media_wall_columns ) ) {
			$media_wall_columns = 3;
		}

		$media_wall_min_width = get_post_meta( $post->ID, 'media_wall_min_width', true );
		if ( empty( $media_wall_min_width ) ) {
			$media_wall_min_width = 300; // LiveFyre's default
		}

		$media_wall_allow_modal = get_post_meta( $post->ID, 'media_wall_allow_modal', true );
		if ( empty( $media_wall_allow_modal ) ) {
			$media_wall_allow_modal = 'modal'; // Default to allowing it -- LiveFyre's default
		}

		// Render the meta fields
		ob_start();
		include trailingslashit( GREATER_MEDIA_LIVEFYRE_WALLS_PATH ) . 'tpl/meta-box.tpl.php';
		$html          = ob_get_clean();
		$filtered_html = apply_filters( 'render_media_wall_meta_box', $html );
		echo $filtered_html;

	}

}

$GreaterMediaLiveFyreMediaWallAdmin = new GreaterMediaLiveFyreMediaWallAdmin();

