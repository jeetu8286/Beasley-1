<?php

/**
 * Created by Eduard
 * Date: 15.10.2014
 */
class GMR_Show_Metaboxes {

	/**
	 * Construcotr.
	 *
	 * @access public
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'post_submitbox_misc_actions', array( $this, 'post_submitbox_misc_actions' ) );
		add_action( 'save_post', array( $this, 'save_box' ), 20 );
	}

	/**
	 * Enqueues necessary scripts and styles.
	 *
	 * @action admin_enqueue_scripts
	 * @access public
	 */
	public function admin_enqueue_scripts() {
		global $pagenow, $typenow;
		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';
		if ( in_array( $pagenow, array( 'post-new.php', 'post.php' ) ) && $typenow == ShowsCPT::CPT_SLUG ) {
			wp_enqueue_script( 'meta_box', GMEDIA_SHOWS_URL . "assets/js/greatermedia_shows{$postfix}.js", array( 'jquery' ), GMEDIA_SHOWS_VERSION, true );
			wp_enqueue_style( 'meta_box', GMEDIA_SHOWS_URL . "assets/css/greatermedia_shows{$postfix}.css", array(), GMEDIA_SHOWS_VERSION );
		}
	}

	/**
	 * Adds meta boxes to show edit page.
	 *
	 * @action add_meta_boxes
	 * @access public
	 */
	public function add_meta_boxes() {
		add_meta_box( 'show_logo', 'Logo', array( $this, 'render_logo_meta_box' ), ShowsCPT::CPT_SLUG, 'side' );
	}

	/**
	 * Displays show settings.
	 *
	 * @action post_submitbox_misc_actions
	 * @access public
	 */
	public function post_submitbox_misc_actions() {
		global $typenow;
		if ( ShowsCPT::CPT_SLUG != $typenow ) {
			return;
		}

		wp_nonce_field( 'gmr_show', 'show_nonce', false );

		$has_homepage = filter_var( get_post_meta( get_the_ID(), 'show_homepage', true ), FILTER_VALIDATE_BOOLEAN );
		
		?><div id="show-homepage" class="misc-pub-section misc-pub-gmr mis-pub-radio">
			Has home page:
			<span class="post-pub-section-value radio-value"><?php echo $has_homepage ? 'Yes' : 'No' ?></span>
			<a href="#" class="edit-radio hide-if-no-js" style="display: inline;"><span aria-hidden="true">Edit</span></a>

			<div class="radio-select hide-if-js">
				<label><input type="radio" name="show_homepage" value="0"<?php checked( $has_homepage, false ) ?>> No</label><br>
				<label><input type="radio" name="show_homepage" value="1"<?php checked( $has_homepage, true ) ?>> Yes</label><br>

				<p>
					<a href="#" class="save-radio hide-if-no-js button"><?php esc_html_e( 'OK' ) ?></a>
					<a href="#" class="cancel-radio hide-if-no-js button-cancel"><?php esc_html_e( 'Cancel' ) ?></a>
				</p>
			</div>
		</div><?php
	}

	/**
	 * Outputs the logo meta box.
	 *
	 * @access public
	 */
	public function render_logo_meta_box( WP_Post $post ) {
		$image = '';
		$image_id = intval( get_post_meta( $post->ID, 'logo_image', true ) );
		if ( $image_id ) {
			$image = current( (array) wp_get_attachment_image_src( $image_id, 'medium' ) );
		}
		
		echo '<input name="logo_image" type="hidden" class="meta_box_upload_image" value="', $image_id, '">';
		echo '<img src="', esc_attr( $image ), '" class="meta_box_preview_image">';
		echo '<div style="text-align:center">';
			echo '<a href="#" class="meta_box_upload_image_button button button-primary" rel="', $post->ID, '">Choose Image</a> ';
			echo '<a href="#" class="meta_box_clear_image_button button">Remove Image</a>';
		echo '</div>';
	}

	/**
	 * Saves the captured data.
	 *
	 * @action save_post
	 * @access public
	 */
	public function save_box( $post_id ) {
		$doing_autosave = defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE;
		$valid_nonce = wp_verify_nonce( filter_input( INPUT_POST, 'show_nonce' ), 'gmr_show' );
		$can_edit_post = current_user_can( 'edit_page', $post_id );
		if ( $doing_autosave || ! $valid_nonce || ! $can_edit_post ) {
			return;
		}

		update_post_meta( $post_id, 'show_homepage', filter_input( INPUT_POST, 'show_homepage', FILTER_VALIDATE_BOOLEAN ) );
		update_post_meta( $post_id, 'logo_image', filter_input( INPUT_POST, 'logo_image', FILTER_VALIDATE_INT ) );
	}

}

$gmr_show_metaboxes = new GMR_Show_Metaboxes();