<?php
/*
Plugin Name: Greater Media Gigya
Description: Greater Media Gigya
Author: 10up
*/

require_once __DIR__ . '/vendor/autoload.php';

function gmr_gigya_main() {
	$plugin = new \GreaterMedia\Gigya\Plugin( __FILE__ );
	$plugin->enable();
}

gmr_gigya_main();
