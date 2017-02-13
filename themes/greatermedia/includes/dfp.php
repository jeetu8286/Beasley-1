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
			'dfp_ad_right_rail_pos1'   => 'Right Rail (pos1)',
//			'dfp_ad_right_rail_pos2'   => 'Right Rail (pos2)',
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
		googletag.beasley.targeting = googletag.beasley.targeting || [];
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
		'dfp_ad_right_rail_pos1'   => get_option( 'dfp_ad_right_rail_pos1' ),
		'dfp_ad_right_rail_pos2'   => get_option( 'dfp_ad_right_rail_pos2' ),
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
		'dfp_ad_right_rail_pos1'   => array( array( 300, 600 ), array( 300, 250 ) ),
		'dfp_ad_right_rail_pos2'   => array( array( 300, 600 ), array( 300, 250 ) ),
		'dfp_ad_interstitial'      => array( array( 1, 1 ) ),
		'dfp_ad_playersponsorship' => array( 'fluid' ),
		'dfp_ad_wallpaper'         => array( array( 1, 1 ) ),
	);

	?><script type="text/javascript">
		(function($, googletag) {
			var slotsIndex = 0, needCleanup = false, __ready;

			__ready = function() {
				var unitCodes = <?php echo json_encode( $unit_codes ); ?>,
					sizes = <?php echo json_encode( $sizes ) ?>,
					slots = [],
					unitCode;

				$('[data-dfp-slot]:empty').each(function() {
					var $this = $(this),
						slotType = $this.attr('data-dfp-slot'),
						slotSizes = JSON.parse($this.attr('data-sizes')),
						targeting = JSON.parse($this.attr('data-targeting')),
						id;

					unitCode = unitCodes[slotType] || slotType;
					if (unitCode) {
						id = 'dfp-slot-' + ++slotsIndex;
						$this.html('<div id="' + id + '" class="gmr-ad"></div>');

						targeting.push(['pos', slotsIndex]);

						slots.push([
							'/<?php echo esc_js( $network_id ); ?>/' + unitCode,
							slotSizes || sizes[slotType],
							id,
							targeting,
							slotType
						]);
					}
				});

				googletag.cmd.push(function() {
					var i, j, slot, targeting, sizeMapping;

					if (needCleanup) {
						googletag.destroySlots();
						googletag.pubads().clearTargeting();
					}

					for (i in slots) {
						if ('dfp_ad_interstitial' == slots[i][4] || 'dfp_ad_wallpaper' == slots[i][4]) {
							slot = googletag.defineOutOfPageSlot(slots[i][0], slots[i][2]);
						} else {
							slot = googletag.defineSlot(slots[i][0], slots[i][1], slots[i][2]);
						}

						sizeMapping = false;
						if ('dfp_ad_leaderboard_pos1' == slots[i][4] || 'dfp_ad_leaderboard_pos2' == slots[i][4]) {
							sizeMapping = googletag.sizeMapping()
								.addSize([1024, 200], [[970, 66], [970, 90], [728, 90]])
								.addSize([768, 200], [728, 90])
								.addSize([0, 0], [[320, 50], [320, 100]])
								.build();
//						} else if ('dfp_ad_inlist_infinite' == slots[i][4]) {
//							sizeMapping = googletag.sizeMapping()
//								.addSize([768, 200], [])
//								.addSize([0, 0], [300, 250])
//								.build();
						} else if ('dfp_ad_incontent_pos1' == slots[i][4] || 'dfp_ad_incontent_pos2' == slots[i][4]) {
							sizeMapping = googletag.sizeMapping()
								.addSize([1024, 200], [])
								.addSize([0, 0], [300, 250])
								.build();
						} else if ('dfp_ad_right_rail_pos1' == slots[i][4] || 'dfp_ad_right_rail_pos2' == slots[i][4]) {
							sizeMapping = googletag.sizeMapping()
								.addSize([1024, 200], [[300, 600], [300, 250]])
								.addSize([0, 0], [])
								.build();
						} else if ($(document.getElementById(slots[i][2])).parents('.widget_gmr-dfp-widget').length > 0) {
							sizeMapping = googletag.sizeMapping()
								.addSize([1024, 200], slots[i][1])
								.addSize([0, 0], [])
								.build();
						}

						if (sizeMapping) {
							slot.defineSizeMapping(sizeMapping);
						}

						for (j in slots[i][3]) {
							slot.setTargeting(slots[i][3][j][0], slots[i][3][j][1]);
						}

						slot.addService(googletag.pubads());
					}

					while ((targeting = googletag.beasley.targeting.pop())) {
						googletag.pubads().setTargeting(targeting[0], targeting[1]);
					}

					googletag.pubads().enableSingleRequest();
					googletag.pubads().collapseEmptyDivs(true);

					googletag.enableServices();
				});

				googletag.cmd.push(function() {
					for (var i in slots) {
						googletag.display(slots[i][2]);
					}
				});
			};

			$(document).on('pjax:end', function() {
				needCleanup = true;
				__ready();
			}).on('gmr_lazy_load_end', function() {
				needCleanup = false;
				__ready();
			}).ready(__ready);
		})(jQuery, googletag);
	</script><?php
}
add_action( 'wp_footer', 'greatermedia_dfp_footer', 1000 );

function greatermedia_display_dfp_slot( $slot, $sizes = false, $echo = true, $class = '' ) {
	static $targeting = null, $position = 0;

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
			$post_id = get_queried_object_id();
			$targeting[] = array( 'cpostid', $post_id );

			if ( class_exists( 'ShowsCPT' ) && defined( 'ShowsCPT::SHOW_TAXONOMY' ) ) {
				$terms = get_the_terms( $post_id, ShowsCPT::SHOW_TAXONOMY );
				if ( $terms && ! is_wp_error( $terms ) ) {
					$targeting[] = array( 'shows', implode( ",", wp_list_pluck( $terms, 'slug' ) ) );
				}
			}

			if ( class_exists( 'GMP_CPT' ) && defined( 'GMP_CPT::PODCAST_POST_TYPE' ) && defined( 'GMP_CPT::EPISODE_POST_TYPE' ) ) {
				$podcast = false;

				$post = get_post( $post_id );
				$post_type = get_post_type( $post );
				if ( GMP_CPT::PODCAST_POST_TYPE == $post_type ) {
					$podcast = $post->post_name;
				}

				if ( GMP_CPT::EPISODE_POST_TYPE == $post_type ) {
					$parent_podcast_id = wp_get_post_parent_id( $post );
					if ( $parent_podcast_id && ! is_wp_error( $parent_podcast_id ) ) {
						$parent_podcast = get_post( $parent_podcast_id );
						$podcast = $parent_podcast->post_name;
					}
				}

				if ( $podcast ) {
					$targeting[] = array( 'podcast', $podcast );
				}
			}

			$categories = wp_get_post_categories( get_queried_object_id() );
			if ( ! empty( $categories ) ) {
				$categories = array_filter( array_map( 'get_category', $categories ) );
				$categories = wp_list_pluck( $categories, 'slug' );
				$targeting[] = array( 'category', implode( ',', $categories ) );
			}
		}
	}

	$remnant_slots = array(
		'dfp_ad_leaderboard_pos1',
		'dfp_ad_leaderboard_pos2',
		'dfp_ad_right_rail_pos1',
		'dfp_ad_right_rail_pos2',
		'dfp_ad_incontent_pos1',
		'dfp_ad_incontent_pos2',
	);

	$single_targeting = array();
	if ( in_array( $slot, $remnant_slots ) ) {
		$single_targeting[] = array( 'remnant', 'yes' );
	}

	$html = '';
	if ( $render_targeting ) {
		$html .= '<script type="text/javascript">googletag.beasley.targeting=' . json_encode( $targeting ) . ';</script>';
	}

	$html .= '<div class="' . $class . '" data-dfp-slot="' . esc_attr( $slot ) . '" data-sizes="' . esc_attr( json_encode( $sizes ) ) . '" data-targeting="' . esc_attr( json_encode( $single_targeting ) ) . '"></div>';

	if ( $echo ) {
		echo $html;
	}

	return $html;
}
add_action( 'dfp_tag', 'greatermedia_display_dfp_slot', 10, 2 );

function greatermedia_display_dfp_outofpage() {
	do_action( 'dfp_tag', 'dfp_ad_interstitial' );
	do_action( 'dfp_tag', 'dfp_ad_wallpaper' );
}
add_action( 'get_footer', 'greatermedia_display_dfp_outofpage' );

function greatermedia_display_dfp_incontent( $content ) {
	$parts = explode( '</p>', $content );
	$new_content = '';

	for ( $i = 0, $len = count( $parts ); $i < $len; $i++ ) {
		$new_content .= $parts[ $i ] . '</p>';

		// in-content pos1 slot after first 2 paragraphs
		if ( 1 == $i || $len < 2 ) {
			$new_content .= greatermedia_display_dfp_slot( 'dfp_ad_incontent_pos1', false, false, 'ad__in-content ad__in-content--mobile' );
		}

		// in-content pos2 slot after 6th parapraph
		if ( 5 == $i && $len > 6 ) {
			$new_content .= greatermedia_display_dfp_slot( 'dfp_ad_incontent_pos2', false, false, 'ad__in-content ad__in-content--mobile' );
		}
	}

	return $new_content;
}
add_filter( 'the_content', 'greatermedia_display_dfp_incontent', 1000 );

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
				do_action( 'dfp_tag', $instance['unit-code'], $sizes );
			echo $args['after_widget'];
		}
	}

}
