<?php
/**
 * Class GMP_Meta
 *
 * This class constructs a meta box for episodes and saves data entered into the fields of the meta box.
 */
class GreaterMediaGalleryAlbumMetaboxes {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_box' ) );
		self::add_save_post_actions();
	}

	public static function add_save_post_actions() {
		add_action( 'save_post_' . GreaterMediaGalleryCPT::ALBUM_POST_TYPE, array( __CLASS__, 'save_meta_box' ), 10, 2 );
	}

	public static function remove_save_post_actions() {
		remove_action( 'save_post_' . GreaterMediaGalleryCPT::ALBUM_POST_TYPE, array( __CLASS__, 'save_meta_box' ), 10 );
	}

	/**
	 * Adds the meta box container for Episodes.
	 *
	 * @param $post_type
	 */
	public static function add_meta_box( $post_type ) {

		$post_types = array( GreaterMediaGalleryCPT::ALBUM_POST_TYPE );

		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box( 'gmp_galleries_meta_box', 'Galleries', array( __CLASS__, 'render_gallery_metabox' ), $post_type, 'advanced', 'high' );
		}

	}

	/**
	 * Render Meta Box content for Episodes.
	 *
	 * @param $post
	 */
	public static function render_gallery_metabox( \WP_Post $post ) {
		wp_nonce_field( 'save_album_galleries', 'album_gallery_nonce' );
	}

	/**
	 * Save the meta when the post is saved for Episodes.
	 *
	 * @param $post_id
	 */
	public static function save_meta_box( $post_id, \WP_Post $post ) {
		if ( ! isset( $_POST['album_gallery_nonce'] ) || ! wp_verify_nonce( $_POST['album_gallery_nonce'], 'save_album_galleries' ) ) {
			return;
		}

	}

}

GreaterMediaGalleryAlbumMetaboxes::init();
