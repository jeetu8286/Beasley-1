<?php

namespace GreaterMedia\MyEmma;

require_once __DIR__ . '/../../MyEmma/Emma.php';

class EmmaAPI extends \Emma {

	function __construct( $account_id = null, $public_key = null, $private_key = null ) {
		if ( is_null( $account_id ) && is_null( $public_key ) && is_null( $private_key ) ) {
			$settings = $this->get_member_query_settings();
			$account_id = $settings['emma_account_id'];
			$public_key = $settings['emma_public_key'];
			$private_key = $settings['emma_private_key'];
		}

		parent::__construct( $account_id, $public_key, $private_key );
	}

	public function get_member_query_settings() {
		$settings = get_option( 'member_query_settings' );
		if ( $settings === '' ) {
			throw new \Exception( 'Fatal Error: Emma settings not found.' );
		} else {
			$settings = json_decode( $settings, true );
		}

		return $settings;
	}

	protected function _request( $url, $verb = null ) {
		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_USERPWD, "{$this->_pub_key}:{$this->_priv_key}" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

		if ( isset( $verb ) ) {
			if ( $verb == "post" ) {
				curl_setopt( $ch, CURLOPT_POST, true );
			} else {
				curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, strtoupper( $verb ) );
			}
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $this->_postData ) );
		}

		$data = curl_exec( $ch );
		$info = curl_getinfo( $ch );

		$debug_email = get_option( 'emma_debug_email', false );
		if ( filter_var( $debug_email, FILTER_VALIDATE_EMAIL ) ) {
			$message = '';

			$message .= var_export( $url, true ) . PHP_EOL;
			$message .= var_export( $data, true ) . PHP_EOL;
			$message .= var_export( $info, true ) . PHP_EOL;
			$message .= var_export( $this->_postData, true ) . PHP_EOL;

			wp_mail( $debug_email, 'Emma Request Report', $message );
		}

		curl_close( $ch );

		if ( $this->_validHttpResponseCode( $info['http_code'] ) ) {
			return $data;
		} else {
			throw new Emma_Invalid_Response_Exception( null, 0, $data, $info['http_code'] );
		}
	}

}