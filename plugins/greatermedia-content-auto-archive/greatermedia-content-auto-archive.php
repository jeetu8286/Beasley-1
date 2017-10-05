<?php
/**
 * Plugin Name: Greater Media Content Auto Archive
 * Plugin URI:  http://wordpress.org/plugins
 * Description: Auto archive site content
 * Version:     0.1.0
 * Author:      10up
 * Author URI:  http://10up.com
 * License:     GPLv2+
 */

define( 'GMR_AUTO_ARCHIVE_CRON', 'gmr_do_content_archive' );
define( 'GMR_AUTO_ARCHIVE_ASYNC_TASK', 'gmr_do_content_archive_async' );
define( 'GMR_AUTO_ARCHIVE_OPTION_NAME', 'content_auto_archive_days' );
define( 'GMR_AUTO_ARCHIVE_POST_STATUS', 'archive' );


function gmr_content_auto_archive_setup() {
	require_once __DIR__ . '/includes/class-archive-core.php';
	require_once __DIR__ . '/includes/class-archive-cron.php';

	$core = new GMR_Archive_Core();
	$cron = new GMR_Archive_Cron();

	$core->setup();
	$cron->setup();

	if ( is_admin() ) {
		require_once __DIR__ . '/includes/class-archive-admin.php';
		$settings = new GMR_Archive_Admin();
		$settings->setup();
	}
}

gmr_content_auto_archive_setup();