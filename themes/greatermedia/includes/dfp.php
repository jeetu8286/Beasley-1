<?php

function greatermedia_dfp_customizer( \WP_Customize_Manager $wp_customize ) {
	$wp_customize->add_setting( 'dfp_network_code', array( 'type' => 'option' ) );
	$wp_customize->add_setting( 'dfp_ad_leaderboard_pos1', array( 'type' => 'option' ) );
	$wp_customize->add_setting( 'dfp_ad_leaderboard_pos2', array( 'type' => 'option' ) );
	$wp_customize->add_setting( 'dfp_ad_incontent_pos1', array( 'type' => 'option' ) );
	$wp_customize->add_setting( 'dfp_ad_incontent_pos2', array( 'type' => 'option' ) );
	$wp_customize->add_setting( 'dfp_ad_inlist_infinite', array( 'type' => 'option' ) );
	$wp_customize->add_setting( 'dfp_ad_interstitial', array( 'type' => 'option' ) );
	$wp_customize->add_setting( 'dfp_ad_playersponsorship', array( 'type' => 'option' ) );
	$wp_customize->add_setting( 'dfp_ad_wallpaper', array( 'type' => 'option' ) );

	$wp_customize->add_panel( 'dfp' , array(
		'title'    => 'DoubleClick for Publishers',
		'priority' => 30,
	) );

	$wp_customize->add_section( 'dfp_settings', array(
		'title' => 'Settings',
		'panel' => 'dfp',
	) );

	$wp_customize->add_section( 'dfp_unit_codes', array(
		'title' => 'Unit Codes',
		'panel' => 'dfp',
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'dfp_network_code', array(
		'label'    => 'Network Code',
		'section'  => 'dfp_settings',
		'settings' => 'dfp_network_code',
	) ) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'dfp_ad_incontent_pos1', array(
		'label'    => 'In Content (pos1)',
		'section'  => 'dfp_unit_codes',
		'settings' => 'dfp_ad_incontent_pos1',
	) ) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'dfp_ad_incontent_pos2', array(
		'label'    => 'In Content (pos2)',
		'section'  => 'dfp_unit_codes',
		'settings' => 'dfp_ad_incontent_pos2',
	) ) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'dfp_ad_inlist_infinite', array(
		'label'    => 'In List (infinite)',
		'section'  => 'dfp_unit_codes',
		'settings' => 'dfp_ad_inlist_infinite',
	) ) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'dfp_ad_interstitial', array(
		'label'    => 'Out-of-Page',
		'section'  => 'dfp_unit_codes',
		'settings' => 'dfp_ad_interstitial',
	) ) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'dfp_ad_leaderboard_pos1', array(
		'label'    => 'Leaderboard (pos1)',
		'section'  => 'dfp_unit_codes',
		'settings' => 'dfp_ad_leaderboard_pos1',
	) ) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'dfp_ad_leaderboard_pos2', array(
		'label'    => 'Leaderboard (pos2)',
		'section'  => 'dfp_unit_codes',
		'settings' => 'dfp_ad_leaderboard_pos2',
	) ) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'dfp_ad_playersponsorship', array(
		'label'    => 'Player Sponsorship',
		'section'  => 'dfp_unit_codes',
		'settings' => 'dfp_ad_playersponsorship',
	) ) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'dfp_ad_wallpaper', array(
		'label'    => 'Wallpaper',
		'section'  => 'dfp_unit_codes',
		'settings' => 'dfp_ad_wallpaper',
	) ) );
}

add_action( 'customize_register', 'greatermedia_dfp_customizer' );

function greatermedia_dfp_output() {
	$network_id = trim( get_option( 'dfp_network_code' ) );
	if ( empty( $network_id ) ) {
		return;
	}

	$leaderboard_pos1 = get_option( 'dfp_ad_leaderboard_pos1' );
	$leaderboard_pos2 = get_option( 'dfp_ad_leaderboard_pos2' );
	$incontent_pos1 = get_option( 'dfp_ad_incontent_pos1' );
	$incontent_pos2 = get_option( 'dfp_ad_incontent_pos2' );
	$inlist_infinite = get_option( 'dfp_ad_inlist_infinite' );
	$interstitial = get_option( 'dfp_ad_interstitial' );
	$playersponsorship = get_option( 'dfp_ad_playersponsorship' );
	$wallpaper = get_option( 'dfp_ad_wallpaper' );

	?><script async="async" src="https://www.googletagservices.com/tag/js/gpt.js"></script>
	<script>
		var googletag = googletag || {};
		googletag.cmd = googletag.cmd || [];

		googletag.cmd.push(function() {
			googletag.defineSlot('/<?php echo esc_js( $network_id ); ?>/<?php echo esc_js( $incontent_pos1 ); ?>', [300, 250], 'div-gpt-ad-1484200509775-0').addService(googletag.pubads());
			googletag.defineSlot('/<?php echo esc_js( $network_id ); ?>/<?php echo esc_js( $incontent_pos2 ); ?>', [300, 250], 'div-gpt-ad-1484200509775-1').addService(googletag.pubads());
			googletag.defineSlot('/<?php echo esc_js( $network_id ); ?>/<?php echo esc_js( $inlist_infinite ); ?>', [300, 250], 'div-gpt-ad-1484200509775-2').addService(googletag.pubads());
			googletag.defineSlot('/<?php echo esc_js( $network_id ); ?>/<?php echo esc_js( $interstitial ); ?>', [-1, -1], 'div-gpt-ad-1484200509775-3').addService(googletag.pubads());
			googletag.defineSlot('/<?php echo esc_js( $network_id ); ?>/<?php echo esc_js( $leaderboard_pos1 ); ?>', [[728, 90], [970, 90], [970, 66], [320, 50], [320, 100]], 'div-gpt-ad-1484200509775-4').addService(googletag.pubads());
			googletag.defineSlot('/<?php echo esc_js( $network_id ); ?>/<?php echo esc_js( $leaderboard_pos2 ); ?>', [[728, 90], [970, 90], [320, 50], [320, 100]], 'div-gpt-ad-1484200509775-5').addService(googletag.pubads());
			//googletag.defineSlot('/<?php echo esc_js( $network_id ); ?>/WMMR_FM_PlayerSponsorship', ['fluid'], 'div-gpt-ad-1484200509775-6').addService(googletag.pubads());
			//googletag.defineSlot('/<?php echo esc_js( $network_id ); ?>/WMMR_FM_RightRail_pos1', [[300, 600], [300, 250]], 'div-gpt-ad-1484200509775-7').addService(googletag.pubads());
			//googletag.defineSlot('/<?php echo esc_js( $network_id ); ?>/WMMR_FM_RightRail_pos2', [[300, 600], [300, 250]], 'div-gpt-ad-1484200509775-8').addService(googletag.pubads());
			//googletag.defineSlot('/<?php echo esc_js( $network_id ); ?>/WMMR_FM_Wallpaper', [1, 1], 'div-gpt-ad-1484200509775-9').addService(googletag.pubads());
			googletag.pubads().enableSingleRequest();
			googletag.pubads().collapseEmptyDivs();
			googletag.pubads().disableInitialLoad();
			googletag.pubads().enableAsyncRendering();
			googletag.enableServices();
		});
	</script><?php
}

//add_action( 'wp_head', 'greatermedia_dfp_output', 1000 );