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
			'parsleyjs',
			GMRDEPENDENCIES_URL . "/parsleyjs/dist/parsley{$postfix}.js",
			array( 'jquery' ),
			'2.0.5', // Using daveross/parsley.js fork until word count include issue #765 is merged
			true
		);

		wp_register_script(
			'parsleyjs-words',
			GMRDEPENDENCIES_URL . '/parsleyjs/src/extra/validator/words.js',
			array( 'parsleyjs' ),
			'2.0.5', // Using daveross/parsley.js fork until word count include issue #765 is merged
			true
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

		wp_enqueue_style(
			'parsleyjs',
			GMRDEPENDENCIES_URL  . '/parsleyjs/src/parsley.css',
			array(),
			'2.0.5', // Using daveross/parsley.js fork until word count include issue #765 is merged
			'all'
		);

		wp_register_style( 'jquery-ui', GMRDEPENDENCIES_URL . '/jquery-ui-theme/jquery-ui.min.css' );
		wp_register_style( 'jquery-ui-accordion', GMRDEPENDENCIES_URL . '/jquery-ui-theme/jquery.ui.accordion.min.css', array( 'jquery-ui' ) );
		wp_register_style( 'jquery-ui-autocomplete', GMRDEPENDENCIES_URL . '/jquery-ui-theme/jquery.ui.autocomplete.min.css', array( 'jquery-ui' ) );
		wp_register_style( 'jquery-ui-button', GMRDEPENDENCIES_URL . '/jquery-ui-theme/jquery.ui.button.min.css', array( 'jquery-ui' ) );
		wp_register_style( 'jquery-ui-core', GMRDEPENDENCIES_URL . '/jquery-ui-theme/jquery.ui.core.min.css', array( 'jquery-ui' ) );
		wp_register_style( 'jquery-ui-datepicker', GMRDEPENDENCIES_URL . '/jquery-ui-theme/jquery.ui.datepicker.min.css', array( 'jquery-ui' ) );
		wp_register_style( 'jquery-ui-dialog', GMRDEPENDENCIES_URL . '/jquery-ui-theme/jquery.ui.dialog.min.css', array( 'jquery-ui' ) );
		wp_register_style( 'jquery-ui-menu', GMRDEPENDENCIES_URL . '/jquery-ui-theme/jquery.ui.menu.min.css', array( 'jquery-ui' ) );
		wp_register_style( 'jquery-ui-progressbar', GMRDEPENDENCIES_URL . '/jquery-ui-theme/jquery.ui.progressbar.min.css', array( 'jquery-ui' ) );
		wp_register_style( 'jquery-ui-resizable', GMRDEPENDENCIES_URL . '/jquery-ui-theme/jquery.ui.resizable.min.css', array( 'jquery-ui' ) );
		wp_register_style( 'jquery-ui-selectable', GMRDEPENDENCIES_URL . '/jquery-ui-theme/jquery.ui.selectable.min.css', array( 'jquery-ui' ) );
		wp_register_style( 'jquery-ui-slider', GMRDEPENDENCIES_URL . '/jquery-ui-theme/jquery.ui.slider.min.css', array( 'jquery-ui' ) );
		wp_register_style( 'jquery-ui-spinner', GMRDEPENDENCIES_URL . '/jquery-ui-theme/jquery.ui.spinner.min.css', array( 'jquery-ui' ) );
		wp_register_style( 'jquery-ui-tabs', GMRDEPENDENCIES_URL . '/jquery-ui-theme/jquery.ui.tabs.min.css', array( 'jquery-ui' ) );
		wp_register_style( 'jquery-ui-theme', GMRDEPENDENCIES_URL . '/jquery-ui-theme/jquery.ui.theme.min.css', array( 'jquery-ui' ) );
		wp_register_style( 'jquery-ui-tooltip', GMRDEPENDENCIES_URL . '/jquery-ui-theme/jquery.ui.tooltip.min.css', array( 'jquery-ui' ) );

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