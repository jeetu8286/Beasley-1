<?php
/**
 * Created by Eduard
 * Date: 07.11.2014 0:09
 */

class GmrDependencies {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_dependencies') );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_dependencies') );
	}

	public function register_dependencies() {

		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

		// Register scripts
		wp_register_script(
			'select2'
			, GMRDEPENDENCIES_URL . "/js/select2{$postfix}.js"
			, array( 'jquery' )
			, '3.5.2'
			, true
		);

		// Register styles
		wp_register_style(
			'select2'
			, GMRDEPENDENCIES_URL . "/css/select2.css"
			, array()
			, '3.5.2'
			, 'all'
		);
	}
}

$GmrDependencies = new GmrDependencies();