<?php

namespace GreaterMedia\LiveFyre;

class Plugin {

	function enable() {
		add_action( 'admin_menu', array( $this, 'initialize_admin_menu' ) );
		add_action( 'init', array( $this, 'initialize' ) );

		if ( $this->is_ajax_request() ) {
			$this->register_ajax_handlers();
		}
	}

	function initialize() {
		$comments_app = new CommentsApp();
		$comments_app->register();

		$shortcode = new MediaWallShortCode();
		$shortcode->register();
	}

	function initialize_admin_menu() {
		$settings_page = new Settings\Page();
		$settings_page->register();

		remove_menu_page( 'edit-comments.php' );

		$livefyre_admin_url = $this->get_livefyre_admin_url();
		if ( $livefyre_admin_url !== '' ) {
			$this->add_comments_mod_menu( $livefyre_admin_url );
		}
	}

	function add_comments_mod_menu( $url ) {
		add_management_page(
			'LiveFyre ModQ',
			'LiveFyre ModQ',
			'manage_options',
			'livefyre-admin-comments'
		);

		global $submenu;
		$settings_menu = $submenu['tools.php'];

		foreach ( $settings_menu as $key => $menu ) {
			if ( $menu[2] === 'livefyre-admin-comments' ) {
				$submenu['tools.php'][ $key ] = array(
					'LiveFyre ModQ', 'manage_options', $url,
				);
			}
		}
	}

	function register_ajax_handlers() {
		$handlers   = array();
		$handlers[] = new Ajax\ChangeLiveFyreSettings();
		$handlers[] = new Ajax\GetLiveFyreAuthToken();

		foreach ( $handlers as $handler ) {
			$handler->register();
		}
	}

	function is_ajax_request() {
		return defined( 'DOING_AJAX' ) && DOING_AJAX;
	}

	function get_livefyre_admin_url() {
		$settings = $this->get_livefyre_settings();

		if ( $settings ) {
			$network_name  = $settings['network_name'];
			$network_admin = str_replace( 'fyre.co', 'admin.fyre.co', $network_name );
			return "https://$network_admin/v3/modq";
		} else {
			return '';
		}
	}

	function get_livefyre_settings() {
		$settings = get_option( 'livefyre_settings' );
		$settings = json_decode( $settings, true );

		return $settings;
	}

}
