<?php

namespace GreaterMedia\Gigya;

class ProfilePath {

	static $instance = null;
	static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new ProfilePath();
		}

		return self::$instance;
	}

	public $endpoint = 'members';

	function path_for( $action_name, $params = null ) {
		$alias = $this->alias_for( $action_name );
		$path  = "/{$this->endpoint}/{$alias}";

		if ( is_null( $params ) ) {
			return $path;
		} else {
			if ( array_key_exists( 'anchor', $params ) ) {
				$anchor = $params['anchor'];
				unset( $params['anchor'] );

				return $path . '?' . http_build_query( $params ) . "#{$anchor}";
			} else {
				return $path . '?' . http_build_query( $params );
			}
		}
	}

	function alias_for( $action_name ) {
		switch ( $action_name ) {
			case 'login':
			case 'signin':
				return 'login';

			case 'logout':
			case 'signout':
				return 'logout';

			case 'register':
			case 'signup':
				return 'register';

			case 'forgot-password':
				return 'forgot-password';

			case 'settings':
				return 'settings';

			case 'cookies-required':
				return 'cookies-required';

			default:
				return $action_name;
		}
	}

}
