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

		wp_register_script(
			'date-format',
			GMRDEPENDENCIES_URL . '/date.format/date.format.js',
			array(),
			false,
			true
		);

		wp_register_script(
			'date-toisostring',
			GMRDEPENDENCIES_URL . '/date-toisostring.js',
			array(),
			null,
			true
		);

		wp_register_script(
			'datetimepicker',
			GMRDEPENDENCIES_URL . '/datetimepicker/jquery.datetimepicker.js',
			array( 'jquery' ),
			'2.3.9',
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

		wp_register_style(
			'datetimepicker',
			GMRDEPENDENCIES_URL . '/datetimepicker/jquery.datetimepicker.css',
			array(),
			'2.3.9',
			'all'
		);
	}
}

$GmrDependencies = new GmrDependencies();