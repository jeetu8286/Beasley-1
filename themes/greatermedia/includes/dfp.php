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

function greatermedia_dfp_head() {
	?><script async="async" src="https://www.googletagservices.com/tag/js/gpt.js"></script>
	<script>
		var googletag = googletag || {};
		googletag.cmd = googletag.cmd || [];

		googletag.beasley = googletag.beasley || {};
		googletag.beasley.slots = googletag.beasley.slots || [];
		googletag.beasley.slotsIndex = googletag.beasley.slotsIndex || 0;

		googletag.beasley.defineSlot = function(slot, sizes) {
			var id = 'dfp-slot-' + ++googletag.beasley.slotsIndex;
			googletag.beasley.slots.push([id, slot, sizes]);
			document.writeln('<div id="' + id + '"></div>');
		};
	</script><?php
}
add_action( 'wp_head', 'greatermedia_dfp_head', 7 );

function greatermedia_dfp_footer() {
	$network_id = trim( get_option( 'dfp_network_code' ) );
	if ( empty( $network_id ) ) {
		return;
	}

	$unit_codes = array(
		'dfp_ad_leaderboard_pos1'  => get_option( 'dfp_ad_leaderboard_pos1' ),
		'dfp_ad_leaderboard_pos2'  => get_option( 'dfp_ad_leaderboard_pos2' ),
		'dfp_ad_incontent_pos1'    => get_option( 'dfp_ad_incontent_pos1' ),
		'dfp_ad_incontent_pos2'    => get_option( 'dfp_ad_incontent_pos2' ),
		'dfp_ad_inlist_infinite'   => get_option( 'dfp_ad_inlist_infinite' ),
		'dfp_ad_interstitial'      => get_option( 'dfp_ad_interstitial' ),
		'dfp_ad_playersponsorship' => get_option( 'dfp_ad_playersponsorship' ),
		'dfp_ad_wallpaper'         => get_option( 'dfp_ad_wallpaper' ),
	);

	$sizes = array(
		'dfp_ad_leaderboard_pos1'  => array( array( 728, 90 ), array( 970, 90 ), array( 970, 66 ), array( 320, 50 ), array( 320, 100 ) ),
		'dfp_ad_leaderboard_pos2'  => array( array( 728, 90 ), array( 970, 90 ), array( 320, 50 ), array( 320, 100 ) ),
		'dfp_ad_incontent_pos1'    => array( array( 300, 250 ) ),
		'dfp_ad_incontent_pos2'    => array( array( 300, 250 ) ),
		'dfp_ad_inlist_infinite'   => array( array( 300, 250 ) ),
		'dfp_ad_interstitial'      => array( array( -1, -1 ) ),
		'dfp_ad_playersponsorship' => array( 'fluid' ),
		'dfp_ad_wallpaper'         => array( array( 1, 1 ) ),
	);

	?><script type="text/javascript">
		(function($) {
			var __ready = function() {
				var unitCodes = <?php echo json_encode( $unit_codes ); ?>,
					sizes = <?php echo json_encode( $sizes ) ?>,
					slots = [],
					slot;

				while ((slot = googletag.beasley.slots.pop())) {
					slots.push([
						'/<?php echo esc_js( $network_id ); ?>/' + (unitCodes[slot[1]] || slot[1]),
						slot[2] || sizes[slot[1]] || [[300, 250]],
						slot[0]
					]);
				}

				googletag.cmd.push(function() {
					var i, slot;

					for (i in slots) {
						slot = slots[i];
						googletag.defineSlot(slot[0], slot[1], slot[2]).addService(googletag.pubads());
					}

					googletag.pubads().enableSingleRequest();
					googletag.pubads().collapseEmptyDivs();

					googletag.enableServices();
				});

				googletag.cmd.push(function() {
					for (var i in slots) {
						googletag.display(slots[i][2]);
					}
				});
			};

			$(document).on('pjax:end', __ready).ready(__ready);
		})(jQuery);
	</script><?php
}
add_action( 'wp_footer', 'greatermedia_dfp_footer', 1000 );

function greatermedia_display_dfp_slot( $slot, $sizes = false ) {
	$slots = array(
		'leaderboard-top-of-site'    => 'dfp_ad_leaderboard_pos1',
		'smartphone-wide-banner'     => 'dfp_ad_leaderboard_pos1',
		'leaderboard-footer-desktop' => 'dfp_ad_leaderboard_pos2',
		'leaderboard-footer-mobile'  => 'dfp_ad_leaderboard_pos2',
		'mrec-body'                  => 'dfp_ad_inlist_infinite',
	);

	if ( isset( $slots[ $slot ] ) ) {
		$slot = $slots[ $slot ];
	} else {
		return;
	}

	?><script type="text/javascript">
		googletag.beasley.defineSlot('<?php echo esc_js( $slot ); ?>', <?php echo json_encode( $sizes ); ?>);
	</script><?php
}
add_action( 'acm_tag', 'greatermedia_display_dfp_slot', 10, 2 );