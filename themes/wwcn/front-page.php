<?php
/**
 * The front page template file
 *
 * @package Greater Media
 * @since   0.1.0
 */

get_header();

?>

	<div class="container">

		<?php do_action( 'do_frontpage_highlights' ); ?>

		<section class="content">

			<div data-crowdynews-widget="BeasleyBroadcastGroupInc_993-espn-social-spot-full-page"><script src="//widget.crowdynews.com/BeasleyBroadcastGroupInc_993-espn-social-spot-full-page.js" async="true"></script></div>

		</section>

		<?php get_sidebar(); ?>

	</div>

<?php get_footer(); ?>
