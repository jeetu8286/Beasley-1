<?php

function greatermedia_dfp_customizer( \WP_Customize_Manager $wp_customize ) {
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

	$settings = array(
		'dfp_settings' => array(
			'dfp_network_code'     => 'Network Code',
			'dfp_targeting_market' => 'Market Targeting Value',
			'dfp_targeting_genre'  => 'Genre Targeting Value',
			'dfp_targeting_ctest'  => 'CTest Targeting Value',
		),
		'dfp_unit_codes' => array(
			'dfp_ad_incontent_pos1'    => 'In Content (pos1)',
			'dfp_ad_incontent_pos2'    => 'In Content (pos2)',
			'dfp_ad_inlist_infinite'   => 'In List (infinite)',
			'dfp_ad_interstitial'      => 'Out-of-Page',
			'dfp_ad_leaderboard_pos1'  => 'Leaderboard (pos1)',
			'dfp_ad_leaderboard_pos2'  => 'Leaderboard (pos2)',
			'dfp_ad_playersponsorship' => 'Player Sponsorship',
			'dfp_ad_wallpaper'         => 'Wallpaper',
		),
	);

	foreach ( $settings as $section => $items ) {
		foreach ( $items as $key => $label ) {
			$wp_customize->add_setting( $key, array( 'type' => 'option' ) );
			$wp_customize->add_control( new \WP_Customize_Control( $wp_customize, $key, array(
				'label'    => $label,
				'section'  => $section,
				'settings' => $key,
			) ) );
		}
	}
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
		googletag.beasley.targeting = googletag.beasley.targeting || [];

		googletag.beasley.defineSlot = function(slot, sizes, targeting) {
			var id = 'dfp-slot-' + ++googletag.beasley.slotsIndex;
			googletag.beasley.slots.push([id, slot, sizes, targeting || []]);
			document.writeln('<div id="' + id + '" class="gmr-ad"></div>');
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
		'dfp_ad_interstitial'      => false,
		'dfp_ad_playersponsorship' => array( 'fluid' ),
		'dfp_ad_wallpaper'         => array( array( 1, 1 ) ),
	);

	?><script type="text/javascript">
		(function($, googletag) {
			var __ready = function() {
				var unitCodes = <?php echo json_encode( $unit_codes ); ?>,
					sizes = <?php echo json_encode( $sizes ) ?>,
					slots = [],
					slot, unitCode;

				while ((slot = googletag.beasley.slots.pop())) {
					unitCode = unitCodes[slot[1]] || slot[1];
					if (unitCode) {
						slots.push([
							'/<?php echo esc_js( $network_id ); ?>/' + unitCode,
							slot[2] || sizes[slot[1]],
							slot[0],
							slot[3]
						]);
					}
				}

				googletag.cmd.push(function() {
					var i, j, slot, targeting;

					googletag.destroySlots();
					googletag.pubads().clearTargeting();

					for (i in slots) {
						slot = false !== slots[i][1]
							? googletag.defineSlot(slots[i][0], slots[i][1], slots[i][2])
							: googletag.defineOutOfPageSlot(slots[i][0], slots[i][2]);

						slot.addService(googletag.pubads());
						for (j in slots[i][3]) {
							slot.setTargeting(slots[i][3][j][0], slots[i][3][j][1]);
						}
					}

					while ((targeting = googletag.beasley.targeting.pop())) {
						googletag.pubads().setTargeting(targeting[0], targeting[1]);
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
		})(jQuery, googletag);
	</script><?php
}
add_action( 'wp_footer', 'greatermedia_dfp_footer', 1000 );

function greatermedia_display_dfp_slot( $slot, $sizes = false ) {
	static $targeting = null, $position = 0;

	$slots = array(
		'leaderboard-top-of-site'    => 'dfp_ad_leaderboard_pos1',
		'leaderboard-footer-desktop' => 'dfp_ad_leaderboard_pos2',
		'mrec-body'                  => 'dfp_ad_inlist_infinite',
	);

	if ( isset( $slots[ $slot ] ) ) {
		$slot = $slots[ $slot ];
	}

	$render_targeting = false;
	if ( is_null( $targeting ) ) {
		$render_targeting = true;
		$targeting = array(
			array( 'cdomain', parse_url( home_url( '/' ), PHP_URL_HOST ) ),
			array( 'cpage', untrailingslashit( current( explode( '?', $_SERVER['REQUEST_URI'], 2 ) ) ) ), // strip query part and trailing slash of the current uri
			array( 'ctest', trim( get_option( 'dfp_targeting_ctest' ) ) ),
			array( 'genre', trim( get_option( 'dfp_targeting_genre' ) ) ),
			array( 'market', trim( get_option( 'dfp_targeting_market' ) ) ),
		);

		if ( is_singular() ) {
			$targeting[] = array( 'cpostid', get_queried_object_id() );
		}
	}

	echo '<script type="text/javascript">';
		$render_targeting && printf( 'googletag.beasley.targeting = %s;', json_encode( $targeting ) );
		echo 'googletag.beasley.defineSlot("', esc_js( $slot ), '", ', json_encode( $sizes ), ', [["pos", ', intval( ++$position ), ']]);';
	echo '</script>';
}
add_action( 'acm_tag', 'greatermedia_display_dfp_slot', 10, 2 );

function greatermedia_display_dfp_outofpage() {
	do_action( 'acm_tag', 'dfp_ad_interstitial' );
}
add_action( 'get_footer', 'greatermedia_display_dfp_outofpage' );

function greatermedia_register_dfp_widget() {
	register_widget( 'GreatermediaDfpWidget' );
}
add_action( 'widgets_init', 'greatermedia_register_dfp_widget' );

class GreatermediaDfpWidget extends \WP_Widget {

	public static $sizes = array(
		'mrec'      => '300x250',
		'half-page' => '300x600',
	);

	public function __construct() {
		$widget_ops = array( 'description' => 'Displays DFP slot.' );
		parent::__construct( 'gmr-dfp-widget', 'DFP widget', $widget_ops );
	}

	public function update( $new_instance, $old_instance ) {
		$new_instance['unit-code'] = sanitize_text_field( $new_instance['unit-code'] );
		$new_instance['sizes'] = sanitize_text_field( implode( ',', (array) $new_instance['sizes'] ) );

		return $new_instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( $instance, array(
			'unit-code' => '',
			'sizes'     => '',
		) );

		$sizes = explode( ',', $instance['sizes'] );

		?><p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'unit-code' ) ); ?>">Unit Code:</label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'unit-code' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'unit-code' ) ); ?>" value="<?php echo esc_attr( $instance['unit-code'] ); ?>">
		</p>
		<p>
			<label>Sizes:</label><br>
			<?php foreach ( self::$sizes as $key => $label ) : ?>
				<label>
					<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'sizes[]' ) ); ?>" value="<?php echo esc_attr( $key ); ?>"<?php checked( in_array( $key, $sizes ) ); ?>>
					<?php echo esc_html( $label ); ?>
				</label><br>
			<?php endforeach; ?>
		</p><?php
	}

	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, array(
			'unit-code' => '',
			'sizes'     => '',
		) );

		$sizes = array();
		foreach ( explode( ',', $instance['sizes'] ) as $size ) {
			if ( isset( self::$sizes[ $size ] ) ) {
				$sizes[] = array_map( 'intval', explode( 'x', self::$sizes[ $size ] ) );
			}
		}

		if ( ! empty( $instance['unit-code'] ) && ! empty( $sizes ) ) {
			echo $args['before_widget'];
				do_action( 'acm_tag', $instance['unit-code'], $sizes );
			echo $args['after_widget'];
		}
	}

}