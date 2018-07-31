<?php

include_once __DIR__ . '/includes/Logger.php';
include_once __DIR__ . '/includes/Regenerate.php';

if ( defined( 'WP_CLI' ) && WP_CLI ) {
    include_once __DIR__ . '/cli/AsyncMedia.php';
}

$regenerate = new \TenUp\AsyncThumbnails\Regenerate();
$regenerate->setup();


