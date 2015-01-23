<?php

class GreaterMediaLivePlayer {

	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_endpoint' ) );
		add_action( 'template_redirect', array( __CLASS__, 'process_onair_request' ) );
		add_action( 'wp_footer', array( __CLASS__, 'render_live_player' ) );
	}

	public static function render_live_player() {
		if ( ! is_page( 'style-guide' ) ) {
			include __DIR__ . '/tpl.live-player.php';
		}
	}

	/**
	 * Registers on-air endpoint.
	 *
	 * @static
	 * @action init
	 * @access public
	 * @global WP $wp The WP object.
	 * @global WP_Rewrite $wp_rewrite The WP_Rewrite object.
	 */
	public static function register_endpoint() {
		global $wp, $wp_rewrite;

		// register blogroll query vars
		$wp->add_query_var( 'on-air' );

		// register rewrite rule
		$regex = '^on-air/?$';
		$wp_rewrite->add_rule( $regex, 'index.php?on-air=yes', 'top' );

		// flush rewrite rules if it doesn't contain blogroll endpoint
		$rules = $wp_rewrite->wp_rewrite_rules();
		if ( ! isset( $rules[ $regex ] ) ) {
			$wp_rewrite->flush_rules();
		}
	}


	/**
	 * Renders blogroll widget.
	 *
	 * @static
	 * @action template_redirect
	 * @access public
	 */
	public static function process_onair_request() {
		// do nothing if it is not a blogroll request
		if ( ! filter_var( get_query_var( 'on-air' ), FILTER_VALIDATE_BOOLEAN ) ) {
			return;
		}

		// send json error if shows plugin isn't activated
		if ( ! function_exists( 'gmrs_get_scheduled_episodes' ) ) {
			wp_send_json_error();
		}

		$schedule = array();

		$from = current_time( 'timestamp', 1 );
		$from = $from - $from % DAY_IN_SECONDS;
		$to = $from + 3 * DAY_IN_SECONDS;

		$from = date( DATE_ISO8601, $from );
		$to = date( DATE_ISO8601, $to );

		$days = gmrs_get_scheduled_episodes( $from, $to );
		foreach ( $days as $day_of_week => $episodes ) {
			foreach ( $episodes as $episode ) {
				if ( $episode->post_parent && ( $show = get_post( $episode->post_parent ) ) && ShowsCPT::SHOW_CPT == $show->post_type ) {
					$starts = strtotime( $episode->post_date_gmt );
					
					$schedule[] = array(
						'title'  => $show->post_title,
						'starts' => $starts,
						'ends'   => $starts + $episode->menu_order,
					);
				}
			}
		}

		wp_send_json_success( array(
			'tagline'  => get_bloginfo( 'description' ),
			'schedule' => $schedule,
		) );
		exit;
	}

}

GreaterMediaLivePlayer::init();