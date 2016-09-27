<?php

class GMR_Content_Settings {

	/**
	 * Setups class hooks.
	 *
	 * @access public
	 */
	public function setup() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Registers settings.
	 *
	 * @access public
	 * @action admin_init
	 */
	public function register_settings() {
		$page = 'gmr-content-cleanup';
		$section = 'gmr-content-cleanup-common';

		add_settings_section( $section, '', array( $this, 'render_settings_description' ), $page );

		add_settings_field( GMR_CLEANUP_STATUS_OPTION, 'Enabled', array( $this, 'render_enabled_field' ), $page, $section );
		add_settings_field( GMR_CLEANUP_AUTHORS_OPTION, 'Authors', array( $this, 'render_authors_field' ), $page, $section );
		add_settings_field( GMR_CLEANUP_AGE_OPTION, 'Age', array( $this, 'render_age_field' ), $page, $section );

		register_setting( $page, GMR_CLEANUP_STATUS_OPTION, array( $this, 'sanitize_status_option' ) );
		register_setting( $page, GMR_CLEANUP_AUTHORS_OPTION, 'sanitize_text_field' );
		register_setting( $page, GMR_CLEANUP_AGE_OPTION, 'absint' );
	}

	/**
	 * Sanitizes status option and configures cron event to cleanup content.
	 *
	 * @access public
	 * @param string $status The original status.
	 * @return int The sanitized status.
	 */
	public function sanitize_status_option( $status ) {
		$status = absint( $status );
		$timestamp = wp_next_scheduled( GMR_CLEANUP_CRON );

		if ( $status ) {
			if ( ! $timestamp ) {
				$timestamp = current_time( 'timestamp', 1 ) + DAY_IN_SECONDS;
				wp_schedule_event( $timestamp, 'daily', GMR_CLEANUP_CRON );
			}
		} else {
			if ( $timestamp ) {
				wp_unschedule_event( $timestamp, GMR_CLEANUP_CRON );
			}
		}

		return $status;
	}

	/**
	 * Renders settings description.
	 *
	 * @access public
	 */
	public function render_settings_description() {
		echo '<p>';
			echo 'Content Cleanup tool allows you to configure automatic cleanup of auto-generated files on current site.';
		echo '</p>';
	}

	/**
	 * Renders status field.
	 *
	 * @access public
	 */
	public function render_enabled_field() {
		echo '<input type="hidden" name="', esc_attr( GMR_CLEANUP_STATUS_OPTION ), '" value="0">';
		echo '<input type="checkbox" name="', esc_attr( GMR_CLEANUP_STATUS_OPTION ), '" value="1"', checked( get_option( GMR_CLEANUP_STATUS_OPTION ), 1, false ), '>';
	}

	/**
	 * Renders authors field.
	 *
	 * @access public
	 */
	public function render_authors_field() {
		echo '<input type="text" name="', esc_attr( GMR_CLEANUP_AUTHORS_OPTION ), '" class="regular-text" value="', esc_attr( get_option( GMR_CLEANUP_AUTHORS_OPTION ) ), '"><br>';
		echo '<span class="description">Comma separated list of user logins which articles will be deleted. If authors are not provided, no posts will be deleted.</span>';
	}

	/**
	 * Renders age field.
	 *
	 * @access public
	 */
	public function render_age_field() {
		echo '<input type="text" name="', esc_attr( GMR_CLEANUP_AGE_OPTION ), '" class="regular-text" value="', esc_attr( get_option( GMR_CLEANUP_AGE_OPTION ) ), '"><br>';
		echo '<span class="description">Enter number of days after which posts created by selected authors will de deleted. If age is not provided, no posts will be deleted.</span>';
	}

	/**
	 * Registers integrations settings page.
	 *
	 * @access public
	 * @action admin_menu
	 */
	public function register_menu() {
		$title = 'Content Cleanup';
		$callback = array( $this, 'render_settings_page' );
		add_management_page( $title, $title, 'manage_options', 'gmr-content-cleanup', $callback );
	}

	/**
	 * Renders integrations settings page.
	 *
	 * @access public
	 */
	public function render_settings_page() {
		?><div class="wrap">
			<h2>Content Cleanup</h2>

			<form id="settings-form" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>" method="post">
				<?php settings_fields( 'gmr-content-cleanup' ); ?>
				<?php do_settings_sections( 'gmr-content-cleanup' ); ?>
				<p class="submit">
					<?php submit_button( null, 'primary', 'submit', false ); ?>
					<button type="reset" class="button button-secondary">Reset Changes</button>
				</p>
			</form>
		</div><?php
	}

}