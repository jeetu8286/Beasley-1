<?php
/**
 * List View Content Template
 * The content template for the list view. This template is also used for
 * the response that is returned on list view ajax requests.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/content.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} ?>

<div id="tribe-events-content" class="tribe-events-list">

	<!-- Notices -->
	<?php tribe_events_the_notices() ?>

	<!-- Events Loop -->
	<?php if ( have_posts() ) : ?>
		<?php do_action( 'tribe_events_before_loop' ); ?>
		<?php tribe_get_template_part( 'list/loop' ) ?>
		<?php do_action( 'tribe_events_after_loop' ); ?>
	<?php endif; ?>

	<!-- List Footer -->
	<?php if ( tribe_has_next_event() ) : ?>
		<?php greatermedia_load_more_button( array( 
			'partial_slug'       => 'tribe-events/list/loop',
			'auto_load'          => true,
			'page_link_template' => add_query_arg( 'tribe_paged', '%d' ),
		) ); ?>
	<?php endif; ?>

</div><!-- #tribe-events-content -->