<?php

class GMR_Cleanup_Cron {

	/**
	 * Setups cleanup cron class instance.
	 *
	 * @access public
	 */
	public function setup() {
		add_action( GMR_CLEANUP_CRON, array( $this, 'do_cleanup' ) );
	}

	/**
	 * Handles cleanup cron.
	 *
	 * @access public
	 */
	public function do_cleanup() {
		// do nothing if cron is disabled
		if ( 1 != get_option( GMR_CLEANUP_STATUS_OPTION ) ) {
			return;
		}
	}

}