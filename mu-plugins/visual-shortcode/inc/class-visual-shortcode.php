<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

abstract class VisualShortcode {

	/**
	 * @var string The name of this shortcode i.e. 'time-restricted'
	 */
	protected $shortcode_name;

	/**
	 * @var string TinyMCE requires plugins to have unique names. Generated by this class using $shortcode_name
	 */
	protected $tinymce_plugin_name;

	/**
	 * @var string Name of the dashicon used for this shortcode's icon in TinyMCE i.e. 'dashicon-clock'
	 * @see http://melchoyce.github.io/dashicons/
	 */
	protected $dashicon_name;

	/**
	 * @var string Glyph code for this shortcode's dashicon. Set by this class using a lookup table.
	 */
	protected $dashicon_glyph;

	/**
	 * @var string Name of the JavaScript module containing this shortcode's implementations of VisualShortcode methods
	 * @see https://carldanley.com/js-module-pattern/
	 */
	protected $js_module_name;

	/**
	 * @var string Unique name of the shortcode's button in TinyMCE. Generated by this class from the plugin name.
	 */
	protected $button_name;

	/**
	 * @var string MCE_TOOLBAR_* constant (see below). Determines which TinyMCE toolbar this shortcode's button will
	 *             render on.
	 */
	protected $mce_toolbar;

	/**
	 * @var string CSS class
	 */
	protected $icon_class;

	/**
	 * @var array used to keep track of all the VisualShortcodes which have been instantiated
	 *            so this class's PHP & JavaScript code can handle as much as possible and the
	 *            plugins which extend this plugin's functionality only need to worry about
	 *            functionality specific to what they're trying to do.
	 */
	protected static $registry;

	/**
	 * Used to track runtime status across all instances. Initially created to keep from
	 * echoing the same JavaScript in the footer multiple times.
	 * @var array
	 */
	protected static $runtime_status = array();

	/**
	 * Constants for declaring which TinyMCE toolbar this shortcode's icon belongs on
	 */
	const MCE_TOOLBAR_DEFAULT = '';
	const MCE_TOOLBAR_ADVANCED = '_2';

	/**
	 * @param string $shortcode_name The name of this shortcode i.e. 'time-restricted'
	 * @param string $js_module_name Name of the JavaScript module containing this shortcode's implementations of VisualShortcode methods
	 * @param string $dashicon_name  Name of the dashicon used for this shortcode's icon in TinyMCE i.e. 'dashicon-clock'
	 * @param string $mce_toolbar    MCE_TOOLBAR_* constant for which TinyMCE toolbar this shortcode's button will render on
	 */
	public function __construct( $shortcode_name, $js_module_name, $dashicon_name = 'dashicons-edit', $mce_toolbar = self::MCE_TOOLBAR_DEFAULT ) {

		// Sanity checks
		if ( ! is_string( $shortcode_name ) ) {
			throw new UnexpectedValueException( 'Shortcode name must be a string' );
		}

		if ( ! is_string( $js_module_name ) ) {
			throw new UnexpectedValueException( 'JavaScript module name must be a string' );
		}

		if ( ! is_string( $dashicon_name ) ) {
			throw new UnexpectedValueException( 'Dashicon name must be a string' );
		}

		if ( self::MCE_TOOLBAR_DEFAULT !== $mce_toolbar && self::MCE_TOOLBAR_ADVANCED !== $mce_toolbar ) {
			throw new UnexpectedValueException( 'Invalid TinyMCE toolbar: expecting MCE_TOOLBAR_DEFAULT or MCE_TOOLBAR_ADVANCED' );
		}

		// Build & set member variables
		$this->shortcode_name      = $shortcode_name;
		$this->js_module_name      = $js_module_name;
		$this->dashicon_name       = $dashicon_name;
		$this->dashicon_glyph      = dashicon_name_to_glyph( $dashicon_name );
		$this->mce_toolbar         = $mce_toolbar;
		$this->tinymce_plugin_name = 'vs_' . str_replace( array( ' ', '-' ), '_', $shortcode_name );
		$this->button_name         = $this->tinymce_plugin_name . '_button';
		$this->icon_class          = $this->button_name;

		// Add this shortcode to the VisualShortcode registry
		self::$registry[ $shortcode_name ] = array(
			'plugin_name'    => $this->tinymce_plugin_name,
			'js_module'      => $js_module_name,
			'dashicon'       => $dashicon_name,
			'dashicon_glyph' => $this->dashicon_glyph,
			'button'         => $this->button_name,
			'icon_class'     => $this->icon_class,
		);

		// Register actions & filters
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ), 100 );
		add_action( 'admin_print_scripts', array( $this, 'admin_print_scripts' ) );

	}

	/**
	 * plugins_loaded hook implementation
	 */
	public function plugins_loaded() {
		// Set up the textdomain, even thought we don't really use it
		load_plugin_textdomain( 'visual-shortcode', false, GREATER_MEDIA_TIMED_CONTENT_PATH );
	}

	/**
	 * init hook implementation
	 */
	public function init() {
		// Register this shortcode with WordPress
		add_shortcode( $this->shortcode_name, array( $this, 'process_shortcode' ) );
	}

	public function admin_head() {

		// check user permissions
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		// check if WYSIWYG is enabled
		if ( 'true' == get_user_option( 'rich_editing' ) ) {

			add_filter( 'mce_external_plugins', array( $this, 'register_tinymce_plugin' ) );

			// Using if/else instead of string concat so filter names stay searchable
			if ( self::MCE_TOOLBAR_DEFAULT === $this->mce_toolbar ) {
				add_filter( 'mce_buttons', array( $this, 'register_tinymce_button' ) );
			} else {
				add_filter( 'mce_buttons_2', array( $this, 'register_tinymce_button' ) );
			}

		}

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'underscore' );

		if ( 'true' == get_user_option( 'rich_editing' ) ) {

			// Inline styles for the TinyMCE button
			// @TODO is it worth replacing this with a Mustache template?
			$css = <<<CSS
i.mce-i-{$this->icon_class}:before {
	font: normal 20px/1 'dashicons';
	content: "{$this->dashicon_glyph}";
}
CSS;

			echo '<style>' . $css . '</style>';

		}
	}

	/**
	 * admin_print_scripts hook implementation
	 */
	public function admin_print_scripts() {

		if ( ! isset( self::$runtime_status['admin_print_scripts'] ) ) {

			self::$runtime_status['admin_print_scripts'] = true;

			// To become the VisualShortcode class in JavaScript
			$settings = array(
				'strings'  => array(
					'Ok'      => __( 'Ok' ),
					'Cancel'  => __( 'Cancel' ),
					'Edit'    => __( 'Edit' ),
					'Create'  => __( 'Create', 'visual-shortcode' ),
					'Content' => __( 'Content', 'visual-shortcode' ),
				),
				'registry' => self::$registry,
			);

			/**
			 * This is basically what wp_localize_script() does, except we don't want to enqueue an empty script
			 * just to make this happen.
			 */
			echo '<script>var VisualShortcode = ' . json_encode( $settings ) . ';</script>';

		}

	}

	/**
	 * Declare script for new button
	 * mce_external_plugins Implementation.
	 *
	 * @param array $plugin_array
	 *
	 * @return array
	 */
	public function register_tinymce_plugin( array $plugin_array ) {

		$plugin_array[ $this->tinymce_plugin_name ] = trailingslashit( VISUAL_SHORTCODE_URL ) . 'js/visual-shortcode-tinymce.js';

		return $plugin_array;

	}

	/**
	 * Register new button in the editor
	 * mce_buttons Implementation.
	 *
	 * @param array $buttons
	 *
	 * @return array
	 */
	public function register_tinymce_button( array $buttons ) {

		array_push( $buttons, $this->button_name );

		return $buttons;

	}

	/**
	 * Render this shortcode in the page content
	 *
	 * @param             $atts    Shortcode attributes
	 * @param string|null $content Shortcode content
	 *
	 * @return string HTML rendered from the shortcode
	 * @see http://codex.wordpress.org/Function_Reference/add_shortcode
	 */
	abstract function process_shortcode( $atts, $content = null );

}
