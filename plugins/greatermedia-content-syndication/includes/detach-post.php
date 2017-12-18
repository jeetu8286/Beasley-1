<?php
/**
 * Handles flagging a post as "detached" from it's original content factory post
 */

class Syndication_Detach_Post {

	public static function init() {
		add_action( 'post_submitbox_misc_actions', array( __CLASS__, 'misc_actions' ), 1001 );
		add_action( 'save_post', array( __CLASS__, 'save' ) );
	}

	public static function misc_actions( $post ) {
		$valid_post_types = SyndicationCPT::$supported_subscriptions;

		if ( ! in_array( $post->post_type, $valid_post_types ) ) {
			return;
		}

		wp_nonce_field( 'detach-post-' . $post->ID, 'syndication-detach-post' );

		$detached = get_post_meta( $post->ID, 'syndication-detached', true );
		if ( $detached === 'true' ) {
			?>
			<div class="misc-pub-section syndication-detached misc-pub-syndication-detached">
				<i class="dashicons dashicons-rss"></i>
				<span id="feature-image-preference-value">Detached from Syndication</span>
			</div>
			<?php
		}
	}

	public static function save( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! isset( $_POST['syndication-detach-post' ] ) || ! wp_verify_nonce( $_POST['syndication-detach-post' ], 'detach-post-' . $post_id ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		update_post_meta( $post_id, 'syndication-detached', 'true' );
	}

}

Syndication_Detach_Post::init();
