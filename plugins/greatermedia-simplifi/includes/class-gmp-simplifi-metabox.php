<?php
/**
 * Class GMP_SIMPLIFI_Meta
 *
 * This class constructs a meta box for episodes and saves data entered into the fields of the meta box.
 */
class GMP_SIMPLIFI_Meta {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta_box' ) );

	}

	/**
	 * Adds the meta box container for Simplifi Pixels.
	 *
	 * @param $post_type
	 */
	public function add_meta_box( $post_type ) {

		$post_types = array( GMP_SIMPLIFI_CPT::SIMPLIFI_PIXEL_POST_TYPE );

		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				'gmp_simplifi_pixels_meta_box'
				, __( 'Simplifi Pixel Details', 'gmsimplifi_pixels' )
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
		if ( ! isset( $_POST['gmp_simplifi_pixels_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['gmp_simplifi_pixels_meta_box_nonce' ], 'gmp_simplifi_pixels_meta_box' ) ) {
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
		$cid = sanitize_text_field( $_POST[ 'gmp_simplifi_pixels_cid' ] );
		// Sanitize and save the user input.
		update_post_meta( $post_id, 'gmp_simplifi_pixels_cid', $cid );

		$action = sanitize_text_field( $_POST[ 'gmp_simplifi_pixels_action' ] );
		// Sanitize and save the user input.
		update_post_meta( $post_id, 'gmp_simplifi_pixels_action', $action );

		$segment = sanitize_text_field( $_POST[ 'gmp_simplifi_pixels_segment' ] );
		// Sanitize and save the user input.
		update_post_meta( $post_id, 'gmp_simplifi_pixels_segment', $segment );

		$m = sanitize_text_field( $_POST[ 'gmp_simplifi_pixels_m' ] );
		// Sanitize and save the user input.
		update_post_meta( $post_id, 'gmp_simplifi_pixels_m', $m );

		$conversion =  sanitize_text_field( $_POST['gmp_simplifi_pixels_conversion'] );
		update_post_meta( $post_id, 'gmp_simplifi_pixels_conversion', $conversion );

		$tid =  sanitize_text_field( $_POST['gmp_simplifi_pixels_tid'] );
		update_post_meta( $post_id, 'gmp_simplifi_pixels_tid', $tid );

		$c =  sanitize_text_field( $_POST['gmp_simplifi_pixels_c'] );
		update_post_meta( $post_id, 'gmp_simplifi_pixels_c', $c );

		$campaign_id =  sanitize_text_field( $_POST['gmp_simplifi_pixels_campaign_id'] );
		update_post_meta( $post_id, 'gmp_simplifi_pixels_campaign_id', $campaign_id );

		$sifi_tuid =  sanitize_text_field( $_POST['gmp_simplifi_pixels_sifi_tuid'] );
		update_post_meta( $post_id, 'gmp_simplifi_pixels_sifi_tuid', $sifi_tuid );

		// delete cache so it will be re-evaluated on the next front-end page request
		delete_transient( 'simplifi_tags' );

	}

	/**
	 * Render Meta Box content for Podcasts.
	 *
	 * @param $post
	 */
	public function render_meta_box_content( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'gmp_simplifi_pixels_meta_box', 'gmp_simplifi_pixels_meta_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$cid = sanitize_text_field( get_post_meta( $post->ID, 'gmp_simplifi_pixels_cid', true ) );
		$action = sanitize_text_field( get_post_meta( $post->ID, 'gmp_simplifi_pixels_action', true ) );
		$segment = sanitize_text_field( get_post_meta( $post->ID, 'gmp_simplifi_pixels_segment', true ) );
		$m = sanitize_text_field( get_post_meta( $post->ID, 'gmp_simplifi_pixels_m', true ) );
		$conversion = sanitize_text_field( get_post_meta( $post->ID, 'gmp_simplifi_pixels_conversion', true ) );
		$tid = sanitize_text_field( get_post_meta( $post->ID, 'gmp_simplifi_pixels_tid', true ) );
		$c = sanitize_text_field( get_post_meta( $post->ID, 'gmp_simplifi_pixels_c', true ) );
		$campaign_id = sanitize_text_field( get_post_meta( $post->ID, 'gmp_simplifi_pixels_campaign_id', true ) );
		$sifi_tuid = sanitize_text_field( get_post_meta( $post->ID, 'gmp_simplifi_pixels_sifi_tuid', true ) );
		?>

		<div class="gmp-meta-row">
			<div class="gmp-meta-row-content gmp-upload">
					<label for="gmp_simplifi_pixels_cid" class="gmp-meta-row-label"><?php esc_html_e( 'Company ID (cid):', 'gmsimplifi_pixels' ); ?></label>
					<input type="text" id="gmp_simplifi_pixels_cid" name="gmp_simplifi_pixels_cid" value="<?php echo esc_attr( $cid ); ?>"/>
					<br>
					<span class="description">Your company id in the Simpli.fi UI.</span>
			</div>
		</div>
		<br>
		<div class="gmp-meta-row">
			<div class="gmp-meta-row-content gmp-upload">
					<label for="gmp_simplifi_pixels_action" class="gmp-meta-row-label"><?php esc_html_e( 'Action (action):', 'gmsimplifi_pixels' ); ?></label>
					<input type="text" id="gmp_simplifi_pixels_action" name="gmp_simplifi_pixels_action" value="<?php echo esc_attr( $action ); ?>"/>			</div>
		  </div>
		<br>
		<div class="gmp-meta-row">
			<div class="gmp-meta-row-content gmp-upload">
					<label for="gmp_simplifi_pixels_segment" class="gmp-meta-row-label"><?php esc_html_e( 'Segment (segment):', 'gmsimplifi_pixels' ); ?></label>
					<input type="text" id="gmp_simplifi_pixels_segment" name="gmp_simplifi_pixels_segment" value="<?php echo esc_attr( $segment ); ?>"/>			</div>
		  </div>
		<br>
		<div class="gmp-meta-row">
			<div class="gmp-meta-row-content gmp-upload">
					<label for="gmp_simplifi_pixels_m" class="gmp-meta-row-label"><?php esc_html_e( 'Allow Matching (m):', 'gmsimplifi_pixels' ); ?></label>
					<input type="text" id="gmp_simplifi_pixels_m" name="gmp_simplifi_pixels_m" value="<?php echo esc_attr( $m ); ?>"/>
					<br>
					<span class="description">"Allow matching", whether to do user matching.</span>
			</div>
		</div>
		<br>
		<div class="gmp-meta-row">
			<div class="gmp-meta-row-content gmp-upload">
					<label for="gmp_simplifi_pixels_conversion" class="gmp-meta-row-label"><?php esc_html_e( 'Type of Conversion Tag (conversion):', 'gmsimplifi_pixels' ); ?></label>
					<input type="text" id="gmp_simplifi_pixels_conversion" name="gmp_simplifi_pixels_conversion" value="<?php echo esc_attr( $conversion ); ?>"/>
					<br>
					<span class="description">The type of conversion tag:  0, 10, 20, 40 for Purchase/Sale, Lead, Sign Up or Other.</span>
			</div>
		</div>
		<br>
		<div class="gmp-meta-row">
			<div class="gmp-meta-row-content gmp-upload">
				<label for="gmp_simplifi_pixels_tid" class="gmp-meta-row-label"><?php esc_html_e( '"Other" Conversion Name (tid):', 'gmsimplifi_pixels' ); ?></label>
				<input type="text" id="gmp_simplifi_pixels_tid" name="gmp_simplifi_pixels_tid" value="<?php echo esc_attr( $tid ); ?>"/>
				<br>
				<span class="description">The meaning of the "other" type of conversion above.  A custom conversion type.</span>
			</div>
		</div>
		<br>
		<div class="gmp-meta-row">
			<div class="gmp-meta-row-content gmp-upload">
				<label for="gmp_simplifi_pixels_c" class="gmp-meta-row-label"><?php esc_html_e( 'Conversion Value (c):', 'gmsimplifi_pixels' ); ?></label>
				<input type="text" id="gmp_simplifi_pixels_c" name="gmp_simplifi_pixels_c" value="<?php echo esc_attr( $c ); ?>"/>
				<br>
				<span class="description">The conversion value, usually what the conversion is worth to you.</span>
			</div>
		</div>
		<br>
		<div class="gmp-meta-row">
			<div class="gmp-meta-row-content gmp-upload">
				<label for="gmp_simplifi_pixels_campaign_id" class="gmp-meta-row-label"><?php esc_html_e( 'Campaign Id (campaign_id):', 'gmsimplifi_pixels' ); ?></label>
				<input type="text" id="gmp_simplifi_pixels_campaign_id" name="gmp_simplifi_pixels_campaign_id" value="<?php echo esc_attr( $campaign_id ); ?>"/>
				<br>
				<span class="description">Either zero or your campaign ID from one of your active campaigns.  0 represents all campaigns.</span>
			</div>
		</div>
		<br>
		<div class="gmp-meta-row">
			<div class="gmp-meta-row-content gmp-upload">
				<label for="gmp_simplifi_pixels_sifi_tuid" class="gmp-meta-row-label"><?php esc_html_e( 'SIFI TUID (sifi_tuid):', 'gmsimplifi_pixels' ); ?></label>
				<input type="text" id="gmp_simplifi_pixels_sifi_tuid" name="gmp_simplifi_pixels_sifi_tuid" value="<?php echo esc_attr( $sifi_tuid ); ?>"/>
			</div>
		</div>

	<?php

	}

}

$GMP_Meta = new GMP_SIMPLIFI_Meta();
