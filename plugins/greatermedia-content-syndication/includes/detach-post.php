<?php
/**
 * Handles flagging a post as "detached" from it's original content factory post
 */

class Syndication_Detach_Post {

	public static function init() {
		add_action( 'post_submitbox_misc_actions', array( __CLASS__, 'misc_actions' ), 1001 );
		add_action( 'save_post', array( __CLASS__, 'save' ) );
		add_action( 'admin_post_reset-syndication', array( __CLASS__, 'reset_syndication' ) );
		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
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
				<?php
				$reset_url = admin_url( 'admin-post.php' );
				$reset_url = add_query_arg( array(
					'action' => 'reset-syndication',
					'post_id' => get_the_ID(),
					'nonce' => wp_create_nonce( 'reset-syndication-' . get_the_ID() ),
				), $reset_url );
				?>
				<span id="feature-image-preference-value">Detached from Syndication</span>&nbsp;<a href="<?php echo esc_url( $reset_url ); ?>" id="js-syndication-reset">Reset</a>
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

	public static function reset_syndication() {
		if ( ! isset( $_GET['post_id'] ) || ! isset( $_GET['nonce'] ) ) {
			wp_die( "An error occurred. Please try again" );
		}

		$post_id = (int) $_GET['post_id'];
		$post = get_post( $post_id );
		if ( ! $post ) {
			wp_die( "Invalid Post. Please try again." );
		}

		if ( ! wp_verify_nonce( $_GET['nonce'], 'reset-syndication-' . $post_id ) ) {
			wp_die( "Your request did not validate. Please try again." );
		}

		delete_post_meta( $post_id, 'syndication-detached' );

		// Redirect to post list screen, with success message
		$redirect_url = add_query_arg( array(
			'post_type' => $post->post_type,
			'syndication-reset' => true,
		), admin_url( 'edit.php' ) );

		wp_redirect( $redirect_url, 302 );
		exit;
	}

	public static function admin_notices() {
		global $pagenow;

		if ( 'edit.php' !== $pagenow ) {
			return;
		}

		if ( isset( $_GET['syndication-reset'] ) ) {
			?>
			<div class="notice notice-info is-dismissible">
				<p>The post was successfully reset to the source site.</p>
			</div>'
			<?php
		}
	}

}

Syndication_Detach_Post::init();
