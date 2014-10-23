<?php

namespace GreaterMedia\Gigya\Ajax;

use GreaterMedia\Gigya\GigyaSession;

class GigyaLoginAjaxHandler extends AjaxHandler {

	public function get_action() {
		return 'gigya_login';
	}

	public function run( $params ) {
		$user_id = $params['UID'];
		$session = GigyaSession::get_instance();

		$session->login( $user_id );
	}

}
