<?php

if ( ! defined( 'WP_CACHE_KEY_SALT' ) ) {
	define(
		'WP_CACHE_KEY_SALT',
		'Sj4.+(d7n[qMY}P8ggb80_La;oZ(ZA+,;o|' . ( isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : 'bbgi' )
	);
}

if ( class_exists( 'Memcached' ) ) {
	require_once __DIR__ . '/object-cache-actual.php';
}

