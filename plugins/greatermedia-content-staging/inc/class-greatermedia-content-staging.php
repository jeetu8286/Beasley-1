<?php

class GreaterMediaContentStaging {

	function __construct() {

		add_action( 'wpmu_options', array( $this, 'wpmu_options' ) );
		add_action( 'update_wpmu_options', array( $this, 'update_wpmu_options' ) );

	}

	/**
	 * Default option values
	 */
	public static function defaults() {

		return array(
			'staging_blog' => 0,
		);

	}

	/**
	 * wpmu_options handler. Adds a Content Staging section to the Network Settings page
	 *
	 * @uses get_site_option
	 */
	public function wpmu_options() {

		$all_sites = wp_get_sites(
			array(
				'limit'    => PHP_INT_MAX,
				'archived' => false,
				'spam'     => false,
				'deleted'  => false,
			)
		);

		$settings     = get_site_option( 'gm_content_staging', self::defaults() );
		$staging_blog = isset( $settings['staging_blog'] ) ? intval( $settings['staging_blog'] ) : 0;

		include trailingslashit( GREATER_MEDIA_CONTENT_STAGING_PATH ) . 'tpl/settings.tpl.php';

	}

	/**
	 * update_wpmu_options handler. Updates options for the Content Staging section
	 *
	 * @uses update_site_option
	 */
	public function update_wpmu_options() {

		if ( ! isset( $_POST['gm_content_staging'] ) ) {
			return;
		}

		$settings = array(
			'staging_blog' => isset( $_POST['gm_content_staging']['staging_blog'] ) ? intval( $_POST['gm_content_staging']['staging_blog'] ) : 0
		);

		update_site_option( 'gm_content_staging', $settings );

	}

	/**
	 * Get the blog ID of the "content staging" blog
	 *
	 * @return int blog_id
	 */
	public static function get_staging_blog() {

		$settings     = get_site_option( 'gm_content_staging', self::defaults() );
		$staging_blog = isset( $settings['staging_blog'] ) ? intval( $settings['staging_blog'] ) : 0;

		return $staging_blog;

	}
}

$GreaterMediaContentStaging = new GreaterMediaContentStaging ();