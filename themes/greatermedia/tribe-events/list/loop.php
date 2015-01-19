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

do_action( 'tribe_events_before_loop' );

while ( have_posts() ) :
	the_post();

	do_action( 'tribe_events_inside_before_loop' );
	get_template_part( 'partials/entry', 'tribe_events' );
	do_action( 'tribe_events_inside_after_loop' );
endwhile;

do_action( 'tribe_events_after_loop' );

if ( tribe_has_next_event() ) :
	$_request = $_REQUEST;
	$_request['tribe_paged'] = '%d';

	greatermedia_load_more_button( array(
		'partial_slug'       => 'tribe-events/list/loop',
		'auto_load'          => true,
		'page_link_template' => add_query_arg( $_request, tribe_get_events_link() ),
	) );
endif;