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
		// I do nothing
	}

	/**
	 * Sets up actions and filters.
	 */
	protected function _init() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'gmlp_location', array($this, 'player_location_class') );
		add_filter( 'body_class', array( $this, 'player_location_body_class' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Add the settings page
	 */
	public function add_settings_page() {
		$this->_settings_page_hook = add_menu_page( 'Live Player Settings', 'Live Player', 'manage_options', 'gmlp-settings', array( $this, 'render_settings_page' ), 'dashicons-format-audio', 3 );
	}

	/**
	 * Render the settings page
	 */
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

	/**
	 * Register the settings page
	 *
	 * @todo remove option for player location after the proposed design has been fully vetted and approved by client
	 *
	 */
	public function register_settings() {
		// Settings Section
		add_settings_section( 'gmlp_settings', 'Greater Media Live Player Settings', array( $this, 'render_gmlp_settings_section' ), $this->_settings_page_hook );

		// Radio Station Callsign
		register_setting( self::option_group, 'gmlp_radio_callsign', 'sanitize_text_field' );
		register_setting( self::option_group, 'gmlp_player_location', 'esc_attr' );

		/**
		 * Allows us to register extra settings that are not necessarily always present on all child sites.
		 */
		do_action( 'gmlp-settings-register-settings', self::option_group );
	}

	/**
	 * Render inputs for the settings page
	 *
	 * @todo remove selector for player location after the proposed design has been fully vetted and approved by client
	 */
	public function render_gmlp_settings_section() {
		$radio_callsign = get_option( 'gmlp_radio_callsign', '' );
		$player_location = get_option( 'gmlp_player_location', '' );
		?>

		<h4><?php _e( 'Live Player API Information', 'gmliveplayer' ); ?></h4>

		<p>
			<label for="gmlp_player_location" class="gmlp-admin-label"><?php _e( 'Player Location', 'gmliveplayer' ); ?></label>
			<select name="gmlp_player_location" id="gmlp_player_location">
				<option value="Off" <?php selected( $player_location, 'off' ); ?>><?php _e( 'Off', 'gmliveplayer' )?></option>
				<option value="top" <?php selected( $player_location, 'top' ); ?>><?php _e( 'Top', 'gmliveplayer' )?></option>
				<option value="bottom" <?php selected( $player_location, 'bottom' ); ?>><?php _e( 'Bottom', 'gmliveplayer' )?></option>
				<option value="right" <?php selected( $player_location, 'right' ); ?>><?php _e( 'Right', 'gmliveplayer' )?></option>
				<option value="left" <?php selected( $player_location, 'left' ); ?>><?php _e( 'Left', 'gmliveplayer' )?></option>
			</select>
		</p>

		<p>
			<label for="gmlp_radio_callsign" class="gmlp-admin-label"><?php _e( 'Radio Callsign', 'gmliveplayer' ); ?></label>
			<input type="text" class="widefat" name="gmlp_radio_callsign" id="gmlp_radio_callsign" value="<?php echo sanitize_text_field( $radio_callsign ); ?>" />
			<div class="gmlp-description"><?php _e( 'The value for this field should consist of the Radio Callsign + Band Type. Ex: WMMR + FM = WMMRFM. WMMRFM would be the value to enter in this field.', 'gmliveplayer' ); ?></div>
		</p>

		<hr/>

	<?php
	}

	/**
	 * Call the player location from the settings page
	 */
	public function player_location_class() {

		$player_location = get_option( 'gmlp_player_location', '' );

		echo esc_attr( $player_location);

	}

	/**
	 * Add a new body class based on the chosen location of the player
	 *
	 * @param $classes
	 *
	 * @return array
	 */
	public function player_location_body_class( $classes ) {

		$location = get_option( 'gmlp_player_location', '' );

		if ( $location == 'top' ) {
			$classes[] = 'gmlp-top gmlp-horizontal';
		} elseif ( $location == 'bottom' ) {
			$classes[] = 'gmlp-bottom gmlp-horizontal';
		} elseif ( $location == 'right' ) {
			$classes[] = 'gmlp-right gmlp-vertical';
		} elseif ( $location == 'left' ) {
			$classes[] = 'gmlp-left gmlp-vertical';
		}

		return $classes;

	}

	/**
	 * Enqueue scripts
	 */
	public static function enqueue_scripts() {

		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';
		wp_enqueue_style( 'gmlp-admin-styles', GMLIVEPLAYER_URL . "assets/css/greater_media_live_player_admin{$postfix}.css", array(), GMLIVEPLAYER_VERSION );

	}

}

GMLP_Settings::instance();
