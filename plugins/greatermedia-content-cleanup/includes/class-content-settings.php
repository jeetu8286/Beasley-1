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

		add_settings_field( 'gmr-cleanup-status', 'Enabled', array( $this, 'render_enabled_field' ), $page, $section );
		add_settings_field( 'gmr-cleanup-authors', 'Authors', array( $this, 'render_authors_field' ), $page, $section );

		register_setting( $page, 'gmr-cleanup-status', 'absint' );
		register_setting( $page, 'gmr-cleanup-authors', 'sanitize_text_field' );
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
		echo '<input type="hidden" name="gmr-cleanup-status" value="0">';
		echo '<input type="checkbox" name="gmr-cleanup-status" value="1"', checked( get_option( 'gmr-cleanup-status'), 1, false ), '>';
	}

	/**
	 * Renders authors field.
	 *
	 * @access public
	 */
	public function render_authors_field() {
		echo '<input type="text" name="gmr-cleanup-authors" class="regular-text" value="', esc_attr( get_option( 'gmr-cleanup-authors' ) ), '"><br>';
		echo '<span class="description">Comma separated list of users which articles will be deleted.</span>';
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