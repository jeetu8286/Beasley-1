<?php

namespace GreaterMedia\Gigya\Sync;

class InitializerTask extends Task {

	function get_task_name() {
		return 'sync_initializer';
	}

	function before() {
		return true;
	}

	function run() {

	}

}
