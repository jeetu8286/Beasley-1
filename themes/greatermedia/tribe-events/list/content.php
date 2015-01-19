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
		<?php tribe_get_template_part( 'list/loop' ) ?>
	<?php endif; ?>

</div><!-- #tribe-events-content -->