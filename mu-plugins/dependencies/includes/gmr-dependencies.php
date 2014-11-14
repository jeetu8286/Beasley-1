<?php

/**
 * Created by Eduard
 * Date: 07.11.2014 0:09
 */
class GmrDependencies {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_dependencies' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_dependencies' ) );
	}

	public function register_dependencies() {

		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

		// Register scripts
		wp_register_script(
			'select2'
			, GMRDEPENDENCIES_URL . "/select2/select2{$postfix}.js"
			, array( 'jquery' )
			, '3.5.2'
			, true
		);

		wp_enqueue_script(
			'parsleyjs',
			GMRDEPENDENCIES_URL . "/parsleyjs/dist/parsley{$postfix}.js",
			array( 'jquery' ),
			'2.0.5', // Using daveross/parsley.js fork until word count include issue #765 is merged
			true
		);

		wp_enqueue_script(
			'parsleyjs-words',
			GMRDEPENDENCIES_URL . '/parsleyjs/src/extra/validator/words.js',
			array( 'parsleyjs' ),
			'2.0.5', // Using daveross/parsley.js fork until word count include issue #765 is merged
			true
		);

		// Register styles
		wp_register_style(
			'select2'
			, GMRDEPENDENCIES_URL . "/select2/select2.css"
			, array()
			, '3.5.2'
			, 'all'
		);

		// jQuery-ui theme
		wp_register_style(
			'jquery-ui',
			GMRDEPENDENCIES_URL . "/jquery-ui/jquery-ui{$postfix}.css",
			array(),
			'1.11.2',
			'all'
		);

		// Cookies.js
		wp_register_script(
			'cookies-js',
			GMRDEPENDENCIES_URL . "/cookies/cookies{$postfix}.js",
			array(),
			'1.1.0',
			true
		);

		wp_enqueue_style(
			'parsleyjs',
			GMRDEPENDENCIES_URL . '/parsleyjs/src/parsley.css',
			array(),
			'2.0.5', // Using daveross/parsley.js fork until word count include issue #765 is merged
			'all'
		);

	}
}

$GmrDependencies = new GmrDependencies();
