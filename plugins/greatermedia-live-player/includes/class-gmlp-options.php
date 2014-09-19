<?php


/**
 * Class GMLP_Settings
 */
class GMLP_Settings {

	const option_group = 'gmlp_settings';

	/**
	 * Contains the slug of the settings page once it's registered
	 *
	 * @var
	 */
	protected $_settings_page_hook;

	/**
	 * Instance of this class, if it has been created.
	 *
	 * @var GMLP_Settings
	 */
	protected static $_instance = null;

	/**
	 * Get the instance of this class, or set it up if it has not been setup yet.
	 *
	 * @var GMLP_Settings
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new static();
			self::$_instance->_init();
		}

		return self::$_instance;
	}

	public function __construct() {
		// I don't do anything
	}

	/**
	 * Sets up actions and filters.
	 */
	protected function _init() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public function add_settings_page() {
		$this->_settings_page_hook = add_options_page( 'Live Player Settings', 'Live Player Settings', 'manage_options', 'gmlp-settings', array( $this, 'render_settings_page' ), '', 3 );
	}

	public function render_settings_page() {
		?>
		<form action="options.php" method="post" class="gmlp-settings-form" style="max-width: 550px;">
			<?php
			settings_errors();
			settings_fields( self::option_group );
			do_settings_sections( $this->_settings_page_hook );

			/**
			 * Allows adding additional settings sections here.
			 *
			 * Useful for the sections that are only enabled if theme support is enabled, we can conditionally add settings for each child theme.
			 */
			do_action( 'gmlp-settings-additional-settings' );

			submit_button( 'Submit' );
			?>
		</form>
	<?php
	}

	public function register_settings() {
		// Settings Section
		add_settings_section( 'gmlp_settings', 'Live Player Settings', array( $this, 'render_gmlp_settings_section' ), $this->_settings_page_hook );

		// Radio Station Callsign
		register_setting( self::option_group, 'gmlp_radio_callsign', 'sanitize_text_field' );

		/**
		 * Allows us to register extra settings that are not necessarily always present on all child sites.
		 */
		do_action( 'gmlp-settings-register-settings', self::option_group );
	}

	public function render_gmlp_settings_section() {
		$radio_callsign = get_option( 'gmlp_radio_callsign', '' );

		?>

		<h4><?php _e( 'Live Player API Information', 'gmliveplayer' ); ?></h4>

		<p>
			<label for="gmlp_radio_callsign"><?php _e( 'Radio Callsign', 'gmliveplayer' ); ?></label>
			<input type="text" class="widefat" name="gmlp_radio_callsign" id="gmlp_radio_callsign" value="<?php echo sanitize_text_field( $radio_callsign ); ?>" />
		</p>

		<hr/>

	<?php
	}

}

GMLP_Settings::instance();
