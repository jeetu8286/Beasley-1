<?php

// action hooks
add_action( 'admin_menu', 'gmrs_register_episode_page' );
add_action( 'admin_enqueue_scripts', 'gmrs_enqueue_episode_scripts' );
add_action( 'admin_action_gmr_add_show_episode', 'gmrs_add_show_episode' );
add_action( 'admin_action_gmr_delete_show_episode', 'gmrs_delete_show_episode' );
add_action( 'future_to_publish', 'gmrs_prolong_show_episode' );

/**
 * Creates new episode each time the current one is published.
 *
 * @param WP_Post $post Currently published episode.
 */
function gmrs_prolong_show_episode( $post ) {
	if ( ShowsCPT::EPISODE_CPT != $post->post_type || ! $post->menu_order ) {
		return;
	}

	$new_post = $post->to_array();
	unset( $new_post['ID'] );

	$new_post['post_date'] = date( DATE_ISO8601, strtotime( $post->post_date ) + WEEK_IN_SECONDS );
	$new_post['post_date_gmt'] = date( DATE_ISO8601, strtotime( $post->post_date_gmt ) + WEEK_IN_SECONDS );
	$new_post['post_status'] = 'future';

	wp_insert_post( $new_post );
}

/**
 * Enqueues scripts and styles required for show episode page.
 *
 * @action admin_enqueue_scripts
 * @global string $gmrs_show_episode_page The show episode page slug.
 * @param string $current_page The current page slug.
 */
function gmrs_enqueue_episode_scripts( $current_page ) {
	global $gmrs_show_episode_page;
	if ( $gmrs_show_episode_page == $current_page ) {
		$postfix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_style( 'meta_box', GMEDIA_SHOWS_URL . "assets/css/greatermedia_shows{$postfix}.css", null, GMEDIA_SHOWS_VERSION );
		wp_enqueue_style( 'jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );

		wp_enqueue_script( 'meta_box', GMEDIA_SHOWS_URL . "assets/js/greatermedia_shows{$postfix}.js", array( 'jquery', 'jquery-ui-datepicker' ), GMEDIA_SHOWS_VERSION, true );
	}
}

/**
 * Registers show episode page.
 *
 * @action admin_menu
 * @global string $gmrs_show_episode_page The show episode page slug.
 */
function gmrs_register_episode_page() {
	global $gmrs_show_episode_page;
	$gmrs_show_episode_page = add_submenu_page( 'edit.php?post_type=' . ShowsCPT::SHOW_CPT, 'Show Schedule', 'Schedule', 'manage_options', 'episode-schedule', 'gmrs_render_episode_schedule_page' );
}

/**
 * Adds new show episode.
 *
 * @action admin_action_gmr_add_show_episode
 */
function gmrs_add_show_episode() {
	check_admin_referer( 'gmr_add_show_episode' );

	$data = filter_input_array( INPUT_POST, array(
		'show'   => array( 'filter' => FILTER_VALIDATE_INT, 'options' => array( 'min_range' => 1 ) ),
		'date'   => FILTER_DEFAULT,
		'time'   => array( 'filter' => FILTER_VALIDATE_INT, 'options' => array( 'min_range' => 0 ) ),
		'repeat' => FILTER_VALIDATE_BOOLEAN,
	) );

	if ( empty( $data['show'] ) || ! ( $show = get_post( $data['show'] ) ) || $show->post_type != ShowsCPT::SHOW_CPT ) {
		wp_die( 'The show has not been found.' );
	}

	if ( ( $date = strtotime( $data['date'] ) ) === false ) {
		wp_die( 'Wrong date has been selected.' );
	}

	$date += $data['time'];
	$date_gmt = $date - get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
	if ( $date_gmt < time() ) {
		wp_die( 'Please, select a date in a future.' );
	}

	$inserted = wp_insert_post( array(
		'post_title'    => $show->post_title,
		'post_type'     => ShowsCPT::EPISODE_CPT,
		'post_status'   => 'future',
		'post_date'     => date( DATE_ISO8601, $date ),
		'post_date_gmt' => date( DATE_ISO8601, $date_gmt ),
		'post_parent'   => $show->ID,
		'menu_order'    => $data['repeat'] ? 1 : 0,
	) );

	$cookie_path = parse_url( admin_url( '/' ), PHP_URL_PATH );
	setcookie( 'gmr_show_id', $show->ID, 0, $cookie_path );
	setcookie( 'gmr_show_time', $data['time'], 0, $cookie_path );
	setcookie( 'gmr_show_date', strtotime( $data['date'] ), 0, $cookie_path );

	$redirect = add_query_arg( array( 'created' => $inserted ? 1 : 0, 'deleted' => false ), wp_get_referer() );
	wp_redirect( $redirect );
	exit;
}

/**
 * Deletes show episode.
 *
 * @action admin_action_gmr_delete_show_episode
 */
function gmrs_delete_show_episode() {
	$episode_id = filter_input( INPUT_GET, 'episode', FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) );
	check_admin_referer( 'gmr_delete_show_episode_' . $episode_id );

	$episode = get_post( $episode_id );
	if ( ! $episode || ShowsCPT::EPISODE_CPT != $episode->post_type ) {
		wp_die( 'The episode was not found.' );
	}

	$deleted = wp_delete_post( $episode_id, true );
	
	$redirect = add_query_arg( array( 'created' => false, 'deleted' => $deleted ? 1 : 0 ), wp_get_referer() );
	wp_redirect( $redirect );
	exit;
}

/**
 * Renders show episode schedule page.
 */
function gmrs_render_episode_schedule_page() {
	$active_show = isset( $_COOKIE['gmr_show_id'] ) ? $_COOKIE['gmr_show_id'] : false;
	$active_time = isset( $_COOKIE['gmr_show_time'] ) ? $_COOKIE['gmr_show_time'] : false;
	$active_date = date( 'M j, Y', isset( $_COOKIE['gmr_show_date'] ) ? $_COOKIE['gmr_show_date'] : strtotime( 'tomorrow' ) );
	
	$episodes = gmrs_get_scheduled_episodes();
	$precision = 0.5; // 1 - each hour, 0.5 - each 30 mins, 0.25 - each 15 mins

	$days = array();
	$start = current( get_weekstartend( date( DATE_ISO8601 ) ) );
	$offset = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;

	$shows = new WP_Query( array(
		'post_type'           => ShowsCPT::SHOW_CPT,
		'post_status'         => 'publish',
		'posts_per_page'      => 500,
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
		'orderby'             => 'title',
		'order'               => 'ASC',
	) );

	?><div id="show-schedule" class="wrap">
		<h2>Show Schedule</h2>

		<?php foreach ( array( 'created', 'deleted' ) as $action ) : ?>
			<?php if ( isset( $_GET[ $action ] ) ) : ?>
				<?php if ( filter_input( INPUT_GET, $action, FILTER_VALIDATE_BOOLEAN ) ) : ?>
					<div class="updated"><p>The episode has been <?php echo $action; ?> successfully.</p></div>
				<?php else : ?>
					<div class="updated error"><p>The episode has not been <?php echo $action; ?>.</p></div>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; ?>

		<form id="schedule-form" action="admin.php" method="post">
			<?php wp_nonce_field( 'gmr_add_show_episode' ); ?>
			<input type="hidden" name="action" value="gmr_add_show_episode">
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
						<td><?php
							if ( ! empty( $episodes[ $day ] ) ) :
								for ( $i = 0, $len = count( $episodes[ $day ] ); $i < $len; $i++ ) :
									$episode = $episodes[ $day ][ $i ];

									$height = $i + 1 < $len
										? strtotime( $episodes[ $day ][ $i + 1 ]->post_date ) % DAY_IN_SECONDS
										: DAY_IN_SECONDS;
									$height = ( $height - ( strtotime( $episode->post_date ) % DAY_IN_SECONDS ) ) * 60 / HOUR_IN_SECONDS;

									?><div class="show-<?php echo esc_attr( $episode->post_parent ); ?>"
										 style="height: <?php echo $height ?>px;background-color:<?php echo gmrs_show_color( $episode->post_parent, 0.15 ) ?>;border-color:<?php echo gmrs_show_color( $episode->post_parent, 0.75 ) ?>;"
										 data-hover-color="<?php echo gmrs_show_color( $episode->post_parent, 0.6 ) ?>">
										
										<small>
											<?php echo date( 'h:i A', strtotime( $episode->post_date_gmt ) + $offset ); ?>
											<?php echo ! empty( $episode->menu_order ) ? '(weekly)' : ''; ?><br>
											<?php echo date( 'M-d h:i A', strtotime( $episode->post_date_gmt ) + $offset ) ?>
										</small>

										<b><?php echo esc_html( $episode->post_title ); ?></b>
										
										<div>
											<?php $delete_url = 'admin.php?action=gmr_delete_show_episode&episode=' . $episode->ID ?>
											<a href="<?php echo esc_url( wp_nonce_url( $delete_url, 'gmr_delete_show_episode_' . $episode->ID ) ) ?>" onclick="return showNotice.warn();">Delete</a>
										</div>
									</div><?php
								endfor;
							endif;
						?></td>
					<?php endforeach; ?>
				</tr>
			</tbody>
		</table>
	</div><?php
}

/**
 * Returns scheduled episodes.
 *
 * @param int $show_id The show id.
 * @return array The array of scheduled episodes.
 */
function gmrs_get_scheduled_episodes() {
	$query = new WP_Query();
	
	$posts = $query->query( array(
		'post_type'           => ShowsCPT::EPISODE_CPT,
		'post_status'         => 'any',
		'posts_per_page'      => 500,
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'ASC',
		'date_query'          => array(
			array(
				'after'     => date( DATE_ISO8601, time() ),
				'before'    => date( 'Y-m-d 23:59:59', strtotime( '+1 week' ) ),
				'inclusive' => true,
				'column'    => 'post_date_gmt'
			),
		),
	) );

	$episodes = array();
	foreach ( $posts as $post ) {
		$dayofweek = date( 'N', strtotime( $post->post_date ) );
		if ( ! isset( $episodes[ $dayofweek ] ) ) {
			$episodes[ $dayofweek ] = array();
		}

		$show = get_post( $post->post_parent );
		if ( ! $show ) {
			continue;
		}
		
		$post->post_title = $show->post_title;
		
		$episodes[ $dayofweek ][] = $post;
	}

	foreach ( $episodes as &$dayofweek ) {
		usort( $dayofweek, 'gmrs_sort_episodes' );
	}

	return $episodes;
}

/**
 * Sorts episodes by time.
 *
 * @param WP_Post $a The first episode.
 * @param WP_Post $b The second episode.
 * @return int Returns 0 if time equals, -1 if a less b and 1 otherwise.
 */
function gmrs_sort_episodes( $a, $b ) {
	$time_a = strtotime( $a->post_date ) % DAY_IN_SECONDS;
	$time_b = strtotime( $b->post_date ) % DAY_IN_SECONDS;

	if ( $time_a == $time_b ) {
		return 0;
	}

	return $time_a < $time_b ? -1 : 1;
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

/**
 * Returns current show episode.
 *
 * @return WP_Post|null The show episode object on success, otherwise NULL.
 */
function gmrs_get_current_show_episode() {
	$query = new WP_Query();
	$episodes = $query->query( array(
		'post_type'           => ShowsCPT::EPISODE_CPT,
		'post_status'         => 'any',
		'posts_per_page'      => 1,
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
		'date_query'          => array(
			array(
				'before'    => current_time( 'mysql' ),
				'inclusive' => true,
			),
		),
	) );

	if ( empty( $episodes ) ) {
		return null;
	}

	return current( $episodes );
}

/**
 * Returns current show.
 *
 * @return WP_Post|null The show object on success, otherwise NULL.
 */
function gmrs_get_current_show() {
	$episode = gmrs_get_current_show_episode();
	if ( ! empty( $episode ) ) {
		$show = get_post( $episode->post_parent );
		if ( $show && ShowsCPT::SHOW_CPT == $show->post_type ) {
			return $show;
		}
	}

	return null;
}
