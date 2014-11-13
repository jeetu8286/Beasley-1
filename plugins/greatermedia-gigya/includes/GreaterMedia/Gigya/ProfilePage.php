<?php

namespace GreaterMedia\Gigya;

class ProfilePage {

	public $allowed_pages = array(
		'login',
		'logout',
		'settings',
	);

	public function register() {
		add_action(
			'template_include', array( $this, 'render_if_profile_page' ), 99
		);
	}

	public function render_if_profile_page( $template ) {
		$page_path = $this->get_current_page_path();

		if ( $this->is_profile_page( $page_path ) ) {
			return $this->render( $page_path, $template );
		}

		return $template;
	}

	public function render( $page_path, $template ) {
		$page_name        = $this->get_profile_page_name( $page_path );
		$profile_template = $this->get_profile_page_template( $page_name );

		if ( ! is_null( $profile_template ) ) {
			status_header( 200 );
			return $profile_template;
		} else {
			return $template;
		}
	}

	/* helpers */
	public function get_current_page_path() {
		// TODO: make this multisite-subdir safe
		return parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
	}

	public function is_profile_page( $path ) {
		return strpos( $path, '/profile/' ) === 0;
	}

	public function get_profile_page_name( $page_path ) {
		$page_parts = explode( '/', $page_path );

		if ( array_key_exists( 2, $page_parts ) ) {
			return $page_parts[2];
		} else {
			return 'login';
		}
	}

	public function get_profile_page_template( $page_name ) {
		if ( in_array( $page_name, $this->allowed_pages ) ) {
			return get_stylesheet_directory() . "/profile/{$page_name}.php";
		} else {
			return null;
		}
	}

}
