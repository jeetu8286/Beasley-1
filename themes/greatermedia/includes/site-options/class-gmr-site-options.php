<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaSiteOptions {

	const option_group = 'greatermedia_site_options';

	/**
	 * Contains the slug of the settings page once it's registered
	 *
	 * @var
	 */
	protected $_settings_page_hook;

	/**
	 * Instance of this class, if it has been created.
	 *
	 * @var GreaterMediaSiteOptions
	 */
	protected static $_instance = null;

	/**
	 * Get the instance of this class, or set it up if it has not been setup yet.
	 *
	 * @return GreaterMediaSiteOptions
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
		$this->_settings_page_hook = add_menu_page( 'Site Settings', 'Site Settings', 'manage_options', 'greatermedia-settings', array( $this, 'render_settings_page' ), '', 3 );
	}

	public function render_settings_page() {
		?>
		<form action="options.php" method="post" class="greatermedia-settings-form" style="max-width: 550px;">
			<?php
			settings_errors();
			settings_fields( self::option_group );
			do_settings_sections( $this->_settings_page_hook );

			/**
			 * Allows adding additional settings sections here.
			 *
			 * Useful for the sections that are only enabled if theme support is enabled, we can conditionally add settings for each child theme.
			 */
			do_action( 'greatermedia-settings-additional-settings' );

			submit_button( 'Submit' );
			?>
		</form>
	<?php
	}


	public function register_settings() {
		// Settings Section
		add_settings_section( 'greatermedia_site_settings', 'Greater Media Site Settings', array( $this, 'render_site_settings_section' ), $this->_settings_page_hook );

		// Social URLs
		register_setting( self::option_group, 'greatermedia_facebook_url', 'esc_url_raw' );
		register_setting( self::option_group, 'greatermedia_twitter_name', 'sanitize_text_field' );
		register_setting( self::option_group, 'greatermedia_youtube_url', 'esc_url_raw' );
		register_setting( self::option_group, 'greatermedia_instagram_name', 'sanitize_text_field' );

		/**
		 * Allows us to register extra settings that are not necessarily always present on all child sites.
		 */
		do_action( 'greatermedia-settings-register-settings', self::option_group );
	}

	public function render_site_settings_section() {
		$facebook = get_option( 'greatermedia_facebook_url', '' );
		$twitter = get_option( 'greatermedia_twitter_name', '' );
		$youtube = get_option( 'greatermedia_youtube_url', '' );
		$instagram = get_option( 'greatermedia_instagram_name', '' );

		?>

		<h4>Social Pages</h4>

		<div class="gmr-option">
			<label for="greatermedia_facebook_url">Facebook URL</label>
			<input type="text" class="widefat" name="greatermedia_facebook_url" id="greatermedia_facebook_url" value="<?php echo esc_url( $facebook ); ?>" />
		</div>

		<div class="gmr-option">
			<label for="greatermedia_twitter_url">Twitter Username</label>
			<input type="text" class="widefat" name="greatermedia_twitter_name" id="greatermedia_twitter_name" value="<?php echo esc_html( $twitter ); ?>" />
			<div class="gmr-option__field--desc"><?php _e( 'Please enter username minus the @', 'greatermedia' ); ?></div>
		</div>

		<div class="gmr-option">
			<label for="greatermedia_youtube_url">YouTube URL</label>
			<input type="text" class="widefat" name="greatermedia_youtube_url" id="greatermedia_youtube_url" value="<?php echo esc_url( $youtube ); ?>" />
		</div>

		<div class="gmr-option">
			<label for="greatermedia_instagram_url">Instagram Username</label>
			<input type="text" class="widefat" name="greatermedia_instagram_name" id="greatermedia_instagram_name" value="<?php echo esc_html( $instagram ); ?>" />
		</div>

		<hr/>


	<?php
	}
}

GreaterMediaSiteOptions::instance();