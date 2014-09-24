<?php
/**
 * Class GM_Podcasts_Meta
 *
 * This class constructs a meta box for episodes and saves data entered into the fields of the meta box.
 */
class GM_Podcasts_Meta {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta_box' ) );

	}

	/**
	 * Adds the meta box container for Episodes.
	 *
	 * @param $post_type
	 */
	public function add_meta_box( $post_type ) {

		$post_types = array( 'episodes' );

		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				'gm_episodes_meta_box'
				, __( 'Episode Attributes', 'gmpodcasts' )
				, array( $this, 'render_meta_box_content' )
				, $post_type
				, 'normal'
				, 'high'
			);
		}

	}

	/**
	 * Save the meta when the post is saved for Episodes.
	 *
	 * @param $post_id
	 */
	public function save_meta_box( $post_id ) {

		// Check if our nonce is set and that it validates it. Also serves as a post type check, because this is only created in the post-type specific meta box
		if ( ! isset( $_POST['gm_episodes_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['gm_episodes_meta_box_nonce' ], 'gm_episodes_meta_box' ) ) {
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
		update_post_meta( $post_id, '_gm_podcasts_audio_file_meta_key', esc_url_raw( $_POST[ 'gm_podcasts_audio_file' ] ) );
	}

	/**
	 * Render Meta Box content for Episodes.
	 *
	 * @param $post
	 */
	public function render_meta_box_content( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'gm_episodes_meta_box', 'gm_episodes_meta_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$podcast_audio_url = get_post_meta( $post->ID, '_gm_podcasts_audio_file_meta_key', true );
		?>

		<div class="gm-podcasts-meta-row">
			<div class="gm-podcasts-meta-row-content gm-podcasts-upload">
				<?php _e( 'Audio File URL: ', 'gmpodcasts' ); ?><?php if ( isset ( $podcast_audio_url ) ) echo esc_url_raw( $podcast_audio_url ) ; ?>
				<input type="hidden" name="gm_podcasts_audio_file" id="gm_podcasts_audio_file" value="<?php if ( isset ( $podcast_audio_url ) ) echo esc_url_raw( $podcast_audio_url ) ; ?>" />
				<div class="gm-podcasts-upload-button">
					<input type="button" id="gm-podcasts-audio-upload" class="button" value="<?php _e( 'Upload Audio', 'gmpodcasts' )?>" />
				</div>
			</div>
		</div>

	<?php

	}

}

new GM_Podcasts_Meta();