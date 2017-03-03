<?php

function greatermedia_init_dfp_settings( $group, $page ) {
	add_settings_section( 'greatermedia_dfp_settings', 'DoubleClick for Publishers', 'greatermedia_render_dfp_settings_section', $page );

	register_setting( $group, 'dfp_network_code', 'sanitize_text_field' );
	register_setting( $group, 'dfp_targeting_market', 'sanitize_text_field' );
	register_setting( $group, 'dfp_targeting_genre', 'sanitize_text_field' );
	register_setting( $group, 'dfp_targeting_ctest', 'sanitize_text_field' );
	register_setting( $group, 'dfp_ad_leaderboard_pos1', 'sanitize_text_field' );
	register_setting( $group, 'dfp_ad_leaderboard_pos2', 'sanitize_text_field' );
	register_setting( $group, 'dfp_ad_incontent_pos1', 'sanitize_text_field' );
	register_setting( $group, 'dfp_ad_incontent_pos2', 'sanitize_text_field' );
	register_setting( $group, 'dfp_ad_inlist_infinite', 'sanitize_text_field' );
	register_setting( $group, 'dfp_ad_interstitial', 'sanitize_text_field' );
	register_setting( $group, 'dfp_ad_wallpaper', 'sanitize_text_field' );
	register_setting( $group, 'dfp_ad_playersponsorship', 'sanitize_text_field' );
}
add_action( 'greatermedia-settings-register-settings', 'greatermedia_init_dfp_settings', 10, 2 );

function greatermedia_render_dfp_settings_section() {
	echo '<div class="gmr__option">';
		echo '<label for="dfp_network_code" class="gmr__option--label">Network Code</label>';
		echo '<input type="text" class="gmr__option--input" name="dfp_network_code" id="dfp_network_code" value="', esc_attr( get_option( 'dfp_network_code' ) ), '">';
		echo '<div class="gmr-option__field--desc"></div>';
	echo '</div>';

	$settings = array(
		'dfp_targeting_market' => 'Market Targeting Value',
		'dfp_targeting_genre'  => 'Genre Targeting Value',
		'dfp_targeting_ctest'  => 'CTest Targeting Value',
	);

	echo '<hr>';
	echo '<h3>Global Targeting</h3>';

	foreach ( $settings as $key => $label ) {
		echo '<div class="gmr__option">';
			echo '<label for="', esc_attr( $key ), '" class="gmr__option--label">', esc_html( $label ), '</label>';
			echo '<input type="text" class="gmr__option--input" name="', esc_attr( $key ), '" id="', esc_attr( $key ), '" value="', esc_attr( get_option( $key ) ), '">';
			echo '<div class="gmr-option__field--desc"></div>';
		echo '</div>';
	}

	$settings = array(
		'dfp_ad_leaderboard_pos1'  => 'Header Leaderboard',
		'dfp_ad_leaderboard_pos2'  => 'Footer Leaderboard',
		'dfp_ad_incontent_pos1'    => 'In Content (pos1)',
		'dfp_ad_incontent_pos2'    => 'In Content (pos2)',
		'dfp_ad_inlist_infinite'   => 'In List (infinite)',
		'dfp_ad_interstitial'      => 'Out-of-Page',
		'dfp_ad_wallpaper'         => 'Wallpaper',
		'dfp_ad_playersponsorship' => 'Player Sponsorship',
	);

	echo '<hr>';
	echo '<h3>Unit Codes</h3>';

	foreach ( $settings as $key => $label ) {
		echo '<div class="gmr__option">';
			echo '<label for="', esc_attr( $key ), '" class="gmr__option--label">', esc_html( $label ), '</label>';
			echo '<input type="text" class="gmr__option--input" name="', esc_attr( $key ), '" id="', esc_attr( $key ), '" value="', esc_attr( get_option( $key ) ), '">';
			echo '<div class="gmr-option__field--desc"></div>';
		echo '</div>';
	}
}
