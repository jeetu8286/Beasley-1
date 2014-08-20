<?php

if ( defined('WP_CLI') && WP_CLI ) {
	include 'tidyjson.php';
	include trailingslashit(__DIR__) . 'inc/class-marketron-to-gigya.php';
}
