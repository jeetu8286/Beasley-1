<?php

namespace GreaterMedia\Gigya\Ajax;

use GreaterMedia\Gigya\Sync\EmmaGroupSyncTask;

class RegisterAccountAjaxHandler extends AjaxHandler {

	function get_action() {
		return 'register_account';
	}

	function is_public() {
		return true;
	}

	function run( $params ) {
		if ( is_gigya_user_logged_in() ) {
			$user_id              = get_gigya_user_id();
			$emma_group_sync_task = new EmmaGroupSyncTask();
			$emma_group_sync_task->enqueue( array( 'user_id' => $user_id ) );

			return true;
		} else {
			return false;
		}
	}

}
