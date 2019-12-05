<?php
/**
 * Module responsible for integrating EE Push Notifications.
 *
 * @package Bbgi
 */

namespace Bbgi\Integration;

class PushNotifications extends \Bbgi\Module {

	const MENU_SLUG = 'bbgi-send-notifications-menu';

	/**
	 * Register actions and hooks.
	 *
	 * @return void
	 */
	public function register() {
		$this->register_custom_cap();
		add_filter( 'post_row_actions', [ $this, 'send_notification_link' ], 10, 2 );
		add_action( 'admin_menu', [ $this, 'register_notification_menu' ] );
		add_action( 'add_meta_boxes', [ $this, 'register_meta_box' ] );
	}

	/**
	 * Register custom capability for admins and editors.
	 *
	 * @return void
	 */
	public function register_custom_cap() {
		$roles = [ 'administrator', 'editor' ];

		foreach ( $roles as $role ) {
			$role_obj = get_role( $role );

			if ( is_a( $role_obj, \WP_Role::class ) ) {
				$role_obj->add_cap( 'send_notifications', true );
			}
		}
	}

	/**
	 * Builds the url to link to the notifications screen.
	 *
	 * @param int $post_id The id of the post to send a notification
	 *
	 * @return void
	 */
	public function get_send_notifications_url( $post_id ) {
		return wp_nonce_url(
			admin_url(
				sprintf(
					'admin.php?page=%s&post_id=%d',
					self::MENU_SLUG,
					$post_id
					)
			),
			'send_notifications'
		);
	}

	/**
	 * Check if the current user can send notifications for a given post.
	 *
	 * @param int $post_id The post id.
	 * @return boolean
	 */
	public function can_send_notifications( $post_id ) {
		return current_user_can( 'edit_post', $post_id ) && current_user_can( 'send_notifications' );
	}

	/**
	 * Sends notification link
	 *
	 * @return array
	 */
	public function send_notification_link( $actions, \WP_Post $post ) {
		if ( $this->can_send_notifications( $post->ID ) ) {
			$actions['send_notification'] = sprintf(
				'<a href="%s">%s</a>',
				esc_url( $this->get_send_notifications_url( $post->ID ) ),
				esc_html__( 'Send Notification', 'bbgi' )
			);
		}

		return $actions;
	}

	/**
	 * Register the notifications menu
	 *
	 * @return void
	 */
	public function register_notification_menu() {
		add_menu_page(
			esc_html__( 'Notifications', 'bbgi' ),
			esc_html__( 'Notifications', 'bbgi' ),
			'send_notifications',
			self::MENU_SLUG,
			[ $this, 'render_notifications_page' ],
			'dashicons-share',
			5
		);
	}

	/**
	 * Register the notifications metabox.
	 *
	 * @return void
	 */
	public function register_meta_box( ) {
		add_meta_box(
			'bbgi-notifications-metabox',
			esc_html__( 'Push Notifications', 'bbgi' ),
			[ $this, 'render_metabox' ],
			get_post_types(),
			'side'
		);
	}

	/**
	 * Renders the notifications metabox
	 *
	 * @param \WP_Post $post The post object.
	 *
	 * @return void
	 */
	public function render_metabox( \WP_Post $post ) {
		?>
		<a class="button"
		   style="width: 100%; text-align:center;height: 50px; line-height: 50px;"
		   href="<?php echo esc_url( $this->get_send_notifications_url( $post->ID ) ); ?>"
		   <?php if ( ! $this->can_send_notifications( $post->ID ) ) { echo 'disabled'; } ?>
		   >
			<?php echo esc_html__( 'Send Notification', 'bbgi' ); ?>
		</a>
		<?php
	}

	/**
	 * Renders the notification page.
	 *
	 * @return void
	 */
	public function render_notifications_page() {
		$post_id = (int) filter_input( INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT );

		if ( $post_id > 0 ) {
			if ( ! $this->can_send_notifications( $post_id ) ) {
				wp_die( 'Not allowed' );
			}
			// additional security check if coming from a post.
			check_admin_referer( 'send_notifications' );
		} else {
			if ( ! current_user_can( 'send_notifications' ) ) {
				wp_die( 'Not allowed' );
			}
		}


		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Notifications', 'bbgi' ); ?></h1>

			<!-- iframe to come here -->
		</div>
		<?php
	}
}
