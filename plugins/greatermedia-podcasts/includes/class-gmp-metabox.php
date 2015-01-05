<?php
/**
 * Class GMP_Meta
 *
 * This class constructs a meta box for episodes and saves data entered into the fields of the meta box.
 */
class GMP_Meta {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta_box' ) );
		//add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_styles' ) );

	}

	/**
	 * Adds the meta box container for Episodes.
	 *
	 * @param $post_type
	 */
	public function add_meta_box( $post_type ) {

		$post_types = array( 'episode' );

		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				'gmp_episodes_meta_box'
				, __( 'Podcast Episode Details', 'gmpodcasts' )
				, array( $this, 'render_meta_box_content' )
				, $post_type
				, 'side'
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
		if ( ! isset( $_POST['gmp_episodes_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['gmp_episodes_meta_box_nonce' ], 'gmp_episodes_meta_box' ) ) {
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
		$checked = sanitize_text_field( $_POST[ 'gmp_audio_downloadable' ] );

		if( $checked != 'on' ) {
			$checked = 'off';
		}

		// Sanitize and save the user input.
		update_post_meta( $post_id, 'gmp_audio_downloadable', esc_attr( $checked ) );
	}

	/**
	 * Render Meta Box content for Episodes.
	 *
	 * @param $post
	 */
	public function render_meta_box_content( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'gmp_episodes_meta_box', 'gmp_episodes_meta_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$gmp_file = get_post_meta( $post->ID, 'gmp_audio_downloadable', true );

		// if no post meta set downloadable this is done for import script
		if( $gmp_file == 'off' ) {
			$checked = '';
		} else {
			$checked = 'checked';
		}

		?>

		<div class="gmp-meta-row">
			<div class="gmp-meta-row-content gmp-upload">
				<div id="gmp-audio-location">
					<label for="gmp_audio_downloadable" class="gmp-meta-row-label"><?php _e( 'Downloadable:', 'gmpodcasts' ); ?></label>
					<input type="checkbox" id="gmp_audio_downloadable" name="gmp_audio_downloadable" <?php echo $checked; ?>/>
				</div>
			</div>
		</div>

	<?php

	}

	/**
	 * Enqueue scripts and styles for Admin
	 */
	public function enqueue_scripts_styles() {

		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_media();
		wp_enqueue_script( 'gmp-admin-js', GMPODCASTS_URL . "/assets/js/gmp_admin{$postfix}.js", array( 'jquery' ), GMPODCASTS_VERSION, true );
		wp_enqueue_style( 'gmp-admin-style', GMPODCASTS_URL . "/assets/css/gmp_admin{$postfix}.css", array(), GMPODCASTS_VERSION );

	}

}

//$GMP_Meta = new GMP_Meta();