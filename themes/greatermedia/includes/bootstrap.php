<?php

call_user_func( function() {
	$modules = array(
		new \Beasley\Integration\Google(),
		new \Beasley\Media\Video(),
		new \Beasley\Gallery\ThumbnailColumn(),
	);

	foreach ( $modules as $module ) {
		$module->register();
	}
} );
