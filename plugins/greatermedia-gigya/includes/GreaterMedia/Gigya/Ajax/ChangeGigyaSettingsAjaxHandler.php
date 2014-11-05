<?php

namespace GreaterMedia\Gigya\Ajax;

class ChangeGigyaSettingsAjaxHandler extends AjaxHandler {

	public function get_action() {
		return 'change_gigya_settings';
	}

	public function run( $params ) {
		$gigya_api_key    = sanitize_text_field( $params['gigya_api_key'] );
		$gigya_secret_key = sanitize_text_field( $params['gigya_secret_key'] );

		try {
			$this->check_gigya_credentials( $gigya_api_key, $gigya_secret_key );
		} catch ( \Exception $e ) {
			throw new \Exception( 'Invalid Credentials: Please check the entered keys.' );
		}

		$settings                     = get_option( 'member_query_settings', '{}' );
		$settings                     = json_decode( $settings, true );
		$settings['gigya_api_key']    = $gigya_api_key;
		$settings['gigya_secret_key'] = $gigya_secret_key;

		update_option( 'member_query_settings', json_encode( $settings ) );
	}

	public function check_gigya_credentials( $api_key, $secret_key ) {
		// Not using GigyaRequest here because we want to test the
		// specified credentials, not the saved ones
		$request = new \GSRequest(
			$api_key,
			$secret_key,
			'accounts.search'
		);

		$request->setParam( 'query',  'select * from accounts limit 1' );
		$response = $request->send();

		if ( $response->getErrorCode() !== 0 ) {
			throw new \Exception( $response->getErrorMessage() );
		}
	}

}
