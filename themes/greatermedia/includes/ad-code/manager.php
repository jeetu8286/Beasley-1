<?php

global $ad_code_manager;

if ( ! is_a( $ad_code_manager, 'Ad_Code_Manager' ) ) {
	return;
}

// register action hooks
add_action( 'init', 'greatermedia_init_ad_code_manager' );

// register filter hooks
add_filter( 'acm_register_provider_slug', 'greatermedia_register_ad_code_providers' );
add_filter( 'acm_provider_slug', 'greatermedia_set_current_provider' );

// remove existing hooks
remove_action( 'init', array( $ad_code_manager, 'action_load_providers' ) );

/**
 * Initializes Ad Code Manager plugin.
 *
 * @global \Ad_Code_Manager $ad_code_manager
 */
function greatermedia_init_ad_code_manager() {
	global $ad_code_manager;

	$ad_code_manager->providers = apply_filters( 'acm_register_provider_slug', $ad_code_manager->providers );
	$ad_code_manager->current_provider_slug = apply_filters( 'acm_provider_slug', $ad_code_manager->get_option( 'provider' ) );

	if ( isset( $ad_code_manager->providers->{$ad_code_manager->current_provider_slug} ) ) {
		$ad_code_manager->current_provider = new $ad_code_manager->providers->{$ad_code_manager->current_provider_slug}['provider'];
	}

	if ( is_object( $ad_code_manager->current_provider ) ) {
		$ad_code_manager->current_provider->whitelisted_script_urls = apply_filters( 'acm_whitelisted_script_urls', $ad_code_manager->current_provider->whitelisted_script_urls );
	}
}

/**
 * Registers DFP provider.
 *
 * @return \stdClass
 */
function greatermedia_register_ad_code_providers( $providers ) {
	require_once __DIR__ . '/class-dfp-acm-provider.php';
	require_once __DIR__ . '/class-dfp-acm-wp-list-table.php';

	$providers->dfp = array(
		'provider' => 'DFP_ACM_Provider',
		'table'    => 'DFP_ACM_WP_List_Table',
	);

	return $providers;
}

/**
 * Returns DFP provider slug.
 *
 * @return string
 */
function greatermedia_set_current_provider() {
	return 'dfp';
}