<?php

function ee_setup_theme() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'post-formats', array( 'gallery', 'link', 'image', 'video', 'audio' ) );
	add_theme_support( 'html5', array( 'search-form' ) );
}

add_action( 'after_setup_theme', 'ee_setup_theme' );

function ee_register_nav_menus() {
	register_nav_menus( array(
		'primary-nav' => 'Primary Navigation',
	) );
}

add_action( 'init', 'ee_register_nav_menus' );
