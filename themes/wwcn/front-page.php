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

		<style>
			.cnwidget.cnfp .cnheader,
			.cnwidget.cnfp .cnfilter-menu.cnfilter-dropdown .cnmenu-services .cnfiltermenu-header {
			background: #ea0017;
			}
			.cnwidget.cnfp .cntweet .cnitem-desc a {
			color: #ea0017;
			}
			.cnwidget.cnfp .cnitem-footer {
			border-bottom: 4px solid #ea0017;
			}
		</style>

			<div data-crowdynews-widget="BeasleyBroadcastGroupInc_993-espn-social-spot-full-page"><script src="//widget.crowdynews.com/BeasleyBroadcastGroupInc_993-espn-social-spot-full-page.js" async="true"></script></div>

		</section>

		<?php get_sidebar(); ?>

	</div>

<?php get_footer(); ?>
