<?php
/**
 * List View Loop
 * This file sets up the structure for the list loop
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/loop.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

global $more;
$more = false;

while ( have_posts() ) :
	the_post();

	do_action( 'tribe_events_inside_before_loop' );
	get_template_part( 'partials/entry', 'tribe_events' );
	do_action( 'tribe_events_inside_after_loop' );
endwhile;