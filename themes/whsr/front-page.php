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

			<div data-crowdynews-widget="BeasleyBroadcastGroupInc_latest-news"><script src="//widget.crowdynews.com/BeasleyBroadcastGroupInc_latest-news.js" async="true"></script></div>

		</section>

		<?php get_sidebar(); ?>

	</div>

<?php get_footer(); ?>
