<?php

namespace GreaterMedia\LiveFyre\Ajax;

use GreaterMedia\Gigya\Ajax\AjaxHandler;
use GreaterMedia\LiveFyre\TokenBuilder;

class GetLiveFyreAuthToken extends AjaxHandler {

	function get_action() {
		return 'get_livefyre_auth_token';
	}

	function is_public() {
		return true;
	}

	function run( $params ) {
		return '';
	}

	function get_livefyre_options() {
		$options = get_option( 'livefyre_settings' );

		if ( $options !== false ) {
			$options = json_decode( $options, true );
		}

		return $options;
	}

}
