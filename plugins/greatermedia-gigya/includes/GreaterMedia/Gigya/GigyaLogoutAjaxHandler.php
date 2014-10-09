<?php

namespace GreaterMedia\Gigya;

class GigyaLogoutAjaxHandler extends AjaxHandler {

	public function get_action() {
		return 'gigya_logout';
	}

	public function run( $params ) {
		$session = GigyaSession::get_instance();
		$session->logout();
	}

}
