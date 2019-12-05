<?php
/**
 * Module responsible for integrating EE Push Notifications.
 *
 * @package Bbgi
 */

namespace Bbgi\Integration;

class PushNotifications extends \Bbgi\Module {


	/**
	 * Register actions and hooks.
	 *
	 * @return void
	 */
	public function register() {
		$this->register_custom_cap();
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
}
