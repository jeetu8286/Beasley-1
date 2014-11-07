<?php

class GreaterMediaSongsMetaboxes {

	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'add_songs_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_songs_meta_box' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

	}

	/**
	 * Adds the meta box container for the Songs Post Type.
	 *
	 * @param $post_type
	 * @uses add_meta_boxes
	 *
	 * @since 0.1.0
	 */
	public function add_songs_meta_box( $post_type ) {

		$post_types = array( 'songs' );

		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				'greatermedia_songs_meta_box'
				, __( 'Song Details', 'greatermedia_songs' )
				, array( $this, 'render_songs_meta_box_content' )
				, $post_type
				, 'normal'
				, 'high'
			);
		}

	}

	/**
	 * Save the meta when the post is saved for the Songs Post Type.
	 *
	 * @param $post_id
	 * @uses save_post
	 *
	 * @since 0.1.0
	 */
	public function save_songs_meta_box( $post_id ) {

		// Check if our nonce is set and that it validates it. Also serves as a post type check, because this is only created in the post-type specific meta box
		if ( ! isset( $_POST['greatermedia_songs_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['greatermedia_songs_meta_box_nonce' ], 'greatermedia_songs_meta_box' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted we don't want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

		/* OK, its safe for us to save the data now. */

		// Sanitize and save the user input.
		update_post_meta( $post_id, 'greatermedia_song_artist', sanitize_text_field( $_POST[ 'greatermedia_song_artist' ] ) );
		update_post_meta( $post_id, 'greatermedia_song_album', sanitize_text_field( $_POST[ 'greatermedia_song_album' ] ) );
		update_post_meta( $post_id, 'greatermedia_song_radio_text', sanitize_text_field( $_POST[ 'greatermedia_song_radio_text' ] ) );
		update_post_meta( $post_id, 'greatermedia_song_purchase_url', esc_url_raw( $_POST[ 'greatermedia_song_purchase_url' ] ) );
	}

	/**
	 * Render Meta Box content for the Songs Post Type.
	 *
	 * @param $post
	 *
	 * @since 0.1.0
	 */
	public function render_songs_meta_box_content( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'greatermedia_songs_meta_box', 'greatermedia_songs_meta_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$song_artist = get_post_meta( $post->ID, 'greatermedia_song_artist', true );
		$song_album = get_post_meta( $post->ID, 'greatermedia_song_artist', true );
		$song_radio_text = get_post_meta( $post->ID, 'greatermedia_song_radio_text', true );
		$song_purchase_url = get_post_meta( $post->ID, 'greatermedia_song_purchase_url', true );
		?>

		<div class="songs__container">
			<label for="greatermedia_song_artist" class="songs__label"><?php _e( 'Artist:', 'greatermedia_songs' ); ?></label>
			<input type="text" name="greatermedia_song_artist" id="greatermedia_song_artist" class="songs__input" value="<?php if ( isset ( $song_artist ) ) echo esc_html( $song_artist ) ; ?>" />
		</div>

		<div class="songs__container">
			<label for="greatermedia_song_album" class="songs__label"><?php _e( 'Album:', 'greatermedia_songs' ); ?></label>
			<input type="text" name="greatermedia_song_album" id="greatermedia_song_album" class="songs__input" value="<?php if ( isset ( $song_album ) ) echo esc_html( $song_album ) ; ?>" />
		</div>

		<div class="songs__container">
			<label for="greatermedia_song_radio_text" class="songs__label"><?php _e( 'Radio Text:', 'greatermedia_songs' ); ?></label>
			<input type="text" name="greatermedia_song_radio_text" id="greatermedia_song_radio_text" class="songs__input" value="<?php if ( isset ( $song_radio_text ) ) echo esc_html( $song_radio_text ) ; ?>" />
		</div>

		<div class="songs__container">
			<label for="greatermedia_song_purchase_url" class="songs__label"><?php _e( 'Purchase URL:', 'greatermedia_songs' ); ?></label>
			<input type="url" name="greatermedia_song_purchase_url" id="greatermedia_song_purchase_url" class="songs__input" value="<?php if ( isset ( $song_purchase_url ) ) echo esc_html( $song_purchase_url ) ; ?>" />
		</div>

	<?php

	}

	/**
	 * Localize scripts and enqueue
	 *
	 * @uses admin_enqueue_scripts
	 *
	 * @since 0.1.0
	 */
	public static function admin_scripts() {

		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_script( 'greatermedia_admin_js', GREATERMEDIA_SONGS_URL . "assets/js/greatermedia_songs_admin{$postfix}.js", array(), GREATERMEDIA_SONGS_VERSION, 'all' );
		wp_enqueue_style( 'greatermedia_admin_js_css', GREATERMEDIA_SONGS_URL . "assets/css/greatermedia_songs_admin{$postfix}.css", array(), GREATERMEDIA_SONGS_VERSION );

	}

}

$GreaterMediaSongsMetaboxes = new GreaterMediaSongsMetaboxes();