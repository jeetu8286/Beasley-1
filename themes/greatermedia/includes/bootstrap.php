<?php

call_user_func( function() {
	$modules = array(
		new \Beasley\Integration\Google(),
	);

	foreach ( $modules as $module ) {
		$module->register();
	}
} );
