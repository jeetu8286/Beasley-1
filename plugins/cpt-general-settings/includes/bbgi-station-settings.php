<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}
class BbgiStationSettings {

	const bbgi_option_group = 'bbgi_site_options';

	function __construct()
	{
		add_action( 'init', array( __CLASS__, 'bbgi_settings_page_init' ), 0 );
		add_action( 'admin_menu', array( $this, 'add_bbgi_settings_page' ), 1 );
		add_action( 'admin_init', array( $this, 'bbgi_register_settings' ) );
	}
	public function bbgi_settings_page_init() {
		// Register custom capability for Draft Kings On/Off Setting and Max mega menu
		$roles = [ 'administrator' ];

		foreach ( $roles as $role ) {
			$role_obj = get_role($role);

			if (is_a($role_obj, \WP_Role::class)) {
				$role_obj->add_cap( 'manage_bbgi_station_settings', false );
			}
		}
	}
	public function add_bbgi_settings_page() {
		$this->_bbgi_settings_page_hook = add_options_page( 'BBGI Station Settings', 'BBGI Station Settings', 'manage_bbgi_station_settings', 'bbgi-station-settings', array( $this, 'render_bbgi_settings_page') );
	}

	public function render_bbgi_settings_page() {
		echo '<form action="options.php" method="post" style="max-width:750px;">';
		settings_fields( self::bbgi_option_group );
		do_settings_sections( $this->_bbgi_settings_page_hook );
		submit_button( 'Submit' );
		echo '</form>';
	}
	public function bbgi_register_settings() {
		add_settings_section( 'ee_bbgi_site_settings', 'BBGI Station Settings', '__return_false', $this->_bbgi_settings_page_hook );
		add_settings_field( 'ee_theme_header_background_color', 'Remove this field before use Header Background Color', 'bbgi_input_field', $this->_bbgi_settings_page_hook, 'ee_bbgi_site_settings', 'name=ee_theme_header_background_color&default=#202020' );
	}

}
new BbgiStationSettings();
