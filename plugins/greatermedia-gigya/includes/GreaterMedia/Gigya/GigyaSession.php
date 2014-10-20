<?php

namespace GreaterMedia\Gigya;

class GigyaSession {

	public $cookie_name     = 'gigya_session';
	public $cookie_data     = array();
	public $loaded          = false;
	public $session_timeout = 1800; // 30 minutes

	static public $instance = null;
	static public function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new GigyaSession();
		}

		return self::$instance;
	}

	public function login( $user_id ) {
		$this->load();

		$this->cookie_data['user_id'] = $user_id;
		$this->save();
	}

	public function logout() {
		$this->load();
		$this->clear();
	}

	public function is_logged_in() {
		$this->load();

		if ( array_key_exists( 'user_id', $this->cookie_data ) ) {
			return ! empty($this->cookie_data['user_id']);
		} else {
			return false;
		}
	}

	public function get_user_id() {
		return $this->get_key( 'user_id' );
	}

	public function get_key( $name ) {
		$this->load();

		if ( array_key_exists( $name, $this->cookie_data ) ) {
			return $this->cookie_data[ $name ];
		} else {
			return '';
		}
	}

	/* helpers */
	public function clear() {
		if ( array_key_exists( $this->cookie_name, $_COOKIE ) ) {
			unset( $_COOKIE[ $this->cookie_name ] );
			$this->set_cookie( '', time() - (12 * 60 * 60) );
			$this->cookie_data = array();
		}
	}

	public function save() {
		$cookie_data = json_encode( $this->cookie_data );
		$this->set_cookie( $cookie_data, time() + $this->session_timeout );
	}

	public function load( $cookie_data = null ) {
		if ( $this->loaded ) {
			return;
		}

		if ( is_null( $cookie_data ) ) {
			$cookie_data = $this->load_cookie_data();
		}

		$this->cookie_data = $this->parse( $cookie_data );
		$this->loaded      = true;
	}

	public function load_cookie_data() {
		if ( array_key_exists( $this->cookie_name, $_COOKIE ) ) {
			$cookie_data = $_COOKIE[ $this->cookie_name ];
			return wp_unslash( $cookie_data );
		} else {
			return '{}';
		}
	}

	public function parse( $cookie_data ) {
		$cookie_data = json_decode( $cookie_data, true );
		if ( ! is_array( $cookie_data ) ) {
			$cookie_data = array();
		}

		return $cookie_data;
	}

	public function set_cookie( $data, $expiry ) {
		if ( ! defined( 'PHPUNIT_RUNNER' ) ) {
			setcookie(
				$this->cookie_name,
				$data,
				$expiry,
				'/',
				$this->get_cookie_domain(),
				is_ssl(),
				true
			);
		} else {
			$_COOKIE[ $this->cookie_name ] = $data;
		}
	}

	public function get_cookie_domain() {
		$site_url = get_site_url();
		return parse_url( $site_url, PHP_URL_HOST );
	}

}
