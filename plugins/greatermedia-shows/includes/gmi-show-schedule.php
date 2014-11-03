<?php

// action hooks
add_action( 'admin_menu', 'gmrs_register_schedule_page' );
add_action( 'admin_enqueue_scripts', 'gmrs_enqueue_schedule_scripts' );
add_action( 'admin_action_gmr_add_show_schedule', 'gmrs_add_show_schedule' );
add_action( 'admin_action_gmr_delete_show_schedule', 'gmrs_delete_show_schedule' );
add_action( 'gmr_show_schdeule', 'gmrs_set_active_show', 10, 4 );

// filter hooks
add_filter( 'cron_schedules', 'gmrs_filter_cron_schedules' );

/**
 * Filters cron schedules.
 *
 * @filter cron_schedules
 * @param array $schedules The initial array of cron schedules.
 * @return array The extended array of cron schedules.
 */
function gmrs_filter_cron_schedules( $schedules ) {
	$schedules['weekly'] = array(
		'interval' => WEEK_IN_SECONDS,
		'display' => 'Once Weekly',
	);
	return $schedules;
}

/**
 * Sets active (on air) show.
 *
 * @action gmr_show_schdeule
 * @param int $show_id The show id.
 */
function gmrs_set_active_show( $show_id ) {
	$show = get_post( $show_id );
	if ( ! $show || $show->post_type != ShowsCPT::CPT_SLUG ) {
		$args = func_get_args();
		$next_run = wp_next_scheduled( 'gmr_show_schdeule', $args );
		if ( $next_run ) {
			wp_unschedule_event( $next_run, 'gmr_show_schdeule', $args );
		}

		return;
	}

	if ( $show->post_status == 'publish' ) {
		update_option( 'gmr_active_show', $show_id );
	}
}

/**
 * Enqueues scripts and styles required for show schedule page.
 *
 * @action admin_enqueue_scripts
 * @global string $gmrs_show_schedule_page The show schedule page slug.
 * @param string $current_page The current page slug.
 */
function gmrs_enqueue_schedule_scripts( $current_page ) {
	global $gmrs_show_schedule_page;
	if ( $gmrs_show_schedule_page == $current_page ) {
		$postfix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_style( 'meta_box', GMEDIA_SHOWS_URL . "assets/css/greatermedia_shows{$postfix}.css", null, GMEDIA_SHOWS_VERSION );
		wp_enqueue_style( 'jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );

		wp_enqueue_script( 'meta_box', GMEDIA_SHOWS_URL . "assets/js/greatermedia_shows{$postfix}.js", array( 'jquery', 'jquery-ui-datepicker' ), GMEDIA_SHOWS_VERSION, true );
	}
}

/**
 * Registers schow schedule page.
 *
 * @action admin_menu
 * @global string $gmrs_show_schedule_page The show schedule page slug.
 */
function gmrs_register_schedule_page() {
	global $gmrs_show_schedule_page;
	$gmrs_show_schedule_page = add_submenu_page( 'edit.php?post_type=' . ShowsCPT::CPT_SLUG, 'Show Schedule', 'Schedule', 'manage_options', 'show-schedule', 'gmrs_render_schedule_page' );
}

/**
 * Adds new show schedule.
 *
 * @action admin_action_gmr_add_show_schedule
 */
function gmrs_add_show_schedule() {
	check_admin_referer( 'gmr_add_show_schedule' );

	$data = filter_input_array( INPUT_POST, array(
		'show'   => array( 'filter' => FILTER_VALIDATE_INT, 'options' => array( 'min_range' => 1 ) ),
		'date'   => FILTER_DEFAULT,
		'time'   => array( 'filter' => FILTER_VALIDATE_INT, 'options' => array( 'min_range' => 0 ) ),
		'repeat' => FILTER_VALIDATE_BOOLEAN,
	) );

	if ( empty( $data['show'] ) || ! ( $show = get_post( $data['show'] ) ) || $show->post_type != ShowsCPT::CPT_SLUG ) {
		wp_die( 'The show has not been found.' );
	}

	if ( ( $date = strtotime( $data['date'] ) ) === false ) {
		wp_die( 'Wrong date has been selected.' );
	}

	$date += $data['time'] - get_option( 'gmt_offset' ) * HOUR_IN_SECONDS; // convert to UTC
	if ( $date < time() ) {
		$date += WEEK_IN_SECONDS;
	}

	if ( $data['repeat'] ) {
		wp_schedule_event( $date, 'weekly', 'gmr_show_schdeule', $data );
	} else {
		wp_schedule_single_event( $date, 'gmr_show_schdeule', $data );
	}

	$cookie_path = parse_url( admin_url( '/' ), PHP_URL_PATH );
	setcookie( 'gmr_show_id', $show->ID, 0, $cookie_path );
	setcookie( 'gmr_show_time', $data['time'], 0, $cookie_path );
	setcookie( 'gmr_show_date', strtotime( $data['date'] ), 0, $cookie_path );

	wp_redirect( wp_get_referer() );
	exit;
}

/**
 * Deletes show schedule.
 *
 * @action admin_action_gmr_delete_show_schedule
 */
function gmrs_delete_show_schedule() {
	check_admin_referer( 'gmr_delete_show_schedule' );

	$crons = _get_cron_array();
	$next_run = filter_input( INPUT_GET, 'next' );
	$sig = filter_input( INPUT_GET, 'sig' );

	if( ! isset( $crons[$next_run]['gmr_show_schdeule'][$sig] ) ) {
		wp_die( 'Show schedule has not been found.' );
	}

	$args = $crons[$next_run]['gmr_show_schdeule'][$sig]['args'];
	wp_unschedule_event( $next_run, 'gmr_show_schdeule', $args );

	wp_redirect( wp_get_referer() );
	exit;
}

/**
 * Renders show schedule page.
 */
function gmrs_render_schedule_page() {
	$active_show = isset( $_COOKIE['gmr_show_id'] ) ? $_COOKIE['gmr_show_id'] : false;
	$active_time = isset( $_COOKIE['gmr_show_time'] ) ? $_COOKIE['gmr_show_time'] : false;
	$active_date = date( 'M j, Y', isset( $_COOKIE['gmr_show_date'] ) ? $_COOKIE['gmr_show_date'] : time() );

	$events = gmrs_get_scheduled_events();
	$precision = 0.5; // 1 - each hour, 0.5 - each 30 mins, 0.25 - each 15 mins

	$days = array();
	$start = current( get_weekstartend( date( DATE_ISO8601 ) ) );

	$shows = new WP_Query( array(
		'post_type'           => ShowsCPT::CPT_SLUG,
		'post_status'         => 'publish',
		'posts_per_page'      => -1,
		'ignore_sticky_posts' => true,
		'orderby'             => 'title',
		'order'               => 'ASC',
	) );

	?><div id="show-schedule" class="wrap">
		<h2>Show Schedule</h2>

		<form id="schedule-form" action="admin.php" method="post">
			<?php wp_nonce_field( 'gmr_add_show_schedule' ); ?>
			<input type="hidden" name="action" value="gmr_add_show_schedule">
			<input type="hidden" id="start-from-date-value" name="date" value="<?php echo $active_date; ?>">

			<input type="submit" class="button button-primary" value="Add to the schedule">

			Add
			<select name="show">
				<?php while ( $shows->have_posts() ) : ?>
					<?php $show = $shows->next_post(); ?>
					<option value="<?php echo esc_attr( $show->ID ); ?>"<?php selected( $show->ID, $active_show ); ?>>
						<?php echo esc_html( $show->post_title ); ?>
					</option>
				<?php endwhile; ?>
			</select>
			show, which occurs
			<select name="repeat">
				<option value="1">every week at this time</option>
				<option value="0">once, this week only</option>
			</select>
			and starts from
			<input type="text" id="start-from-date" value="<?php echo $active_date; ?>" required>
			at
			<select name="time">
				<?php for ( $i = 0, $count = 24 / $precision; $i < $count; $i++ ) : ?>
					<?php $time = HOUR_IN_SECONDS * $precision * $i; ?>
					<option value="<?php echo $time; ?>"<?php selected( $time, $active_time ); ?>>
						<?php echo date( 'h:i A', $time ); ?>
					</option>
				<?php endfor; ?>
			</select>
		</form>

		<table id="schedule-table">
			<thead>
				<tr>
					<?php for ( $i = 0; $i < 7; $i++, $start += DAY_IN_SECONDS ) : ?>
						<?php $days[] = date( 'N', $start ); ?>
						<th><?php echo date( 'l', $start ); ?></th>
					<?php endfor; ?>
				</tr>
			</thead>

			<tbody>
				<tr>
					<?php foreach ( $days as $day ) : ?>
						<td>
							<?php if ( ! empty( $events[ $day ] ) ) : ?>
								<?php for ( $i = 0, $len = count( $events[ $day ] ); $i < $len; $i++ ) : ?>
									<?php $event = $events[ $day ][ $i ]; ?>
									<?php $height = ( ( $i + 1 < $len ? $events[ $day ][ $i + 1 ]->args['time'] : DAY_IN_SECONDS ) - $event->args['time'] ) * 60 / HOUR_IN_SECONDS; ?>

									<div class="show-<?php echo esc_attr( $event->show->ID ); ?>"
										 style="height: <?php echo $height ?>px;background-color:<?php echo gmrs_show_color( $event->show->ID, 0.15 ) ?>;border-color:<?php echo gmrs_show_color( $event->show->ID, 0.75 ) ?>;"
										 data-hover-color="<?php echo gmrs_show_color( $event->show->ID, 0.6 ) ?>">

										<div>
											<b><?php echo esc_html( $event->show->post_title ); ?></b>
											<small><?php echo date( 'h:i A', $event->args['time'] ), ' ', $event->schedule ? '(weekly)' : ''; ?></small>
										</div>

										<div>
											<?php $delete_url = add_query_arg( array( 'sig' => $event->sig, 'next' => $event->next_run ), 'admin.php?action=gmr_delete_show_schedule' ); ?>
											<a href="<?php echo esc_url( wp_nonce_url( $delete_url, 'gmr_delete_show_schedule' ) ) ?>" onclick="return showNotice.warn();">Delete</a>
										</div>
									</div>
								<?php endfor; ?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
			</tbody>
		</table>
	</div><?php
}

/**
 * Returns scheduled events.
 *
 * @param int $show_id The show id.
 * @return array The array of scheduled events.
 */
function gmrs_get_scheduled_events() {
	$events = $matches = array();
	foreach( _get_cron_array() as $time => $cron ) {
		foreach( $cron as $hook => $dings ) {
			foreach( $dings as $sig => $data ) {
				if ( 'gmr_show_schdeule' == $hook ) {
					$show = get_post( current( $data['args'] ) );
					if ( ! $show ) {
						continue;
					}

					$dayofweek = date( 'N', strtotime( $data['args']['date'] ) + $data['args']['time'] );
					if ( ! isset( $events[ $dayofweek ] ) ) {
						$events[ $dayofweek ] = array();
					}

					$events[ $dayofweek ][] = (object) array(
						'hook'     => $hook,
						'time'     => date( DATE_ISO8601, $time ),
						'next_run' => $time,
						'sig'      => $sig,
						'args'     => $data['args'],
						'schedule' => $data['schedule'],
						'interval' => isset( $data['interval'] ) ? $data['interval'] : null,
						'show'     => $show,
					);
				}
			}
		}
	}

	foreach ( $events as $dayofweek => &$events_list ) {
		usort( $events_list, 'gmrs_sort_scheduled_events' );
	}

	return $events;
}

/**
 * Sorts scheduled shows by time.
 *
 * @param object $event_a The first scheduled show object.
 * @param object $event_b The second scheduled show object.
 * @return int 0 if shows start at the same time, -1 if first show starts earlier than the second and 1 in other case.
 */
function gmrs_sort_scheduled_events( $event_a, $event_b ) {
	if ( $event_a->args['time'] == $event_b->args['time'] ) {
		return 0;
	}

	return $event_a->args['time'] < $event_b->args['time'] ? -1 : 1;
}

/**
 * Generates color by show id.
 *
 * @param int $show_id The show id.
 * @param float $opacity The color opacity.
 * @return string CSS rgba string.
 */
function gmrs_show_color( $show_id, $opacity ) {
	$hash = sha1( $show_id );
	return sprintf(
		'rgba(%d, %d, %d, %f)',
		hexdec( substr( $hash, 0, 2 ) ),
		hexdec( substr( $hash, 2, 2 ) ),
		hexdec( substr( $hash, 4, 2 ) ),
		$opacity
	);
}