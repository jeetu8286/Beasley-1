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
				background: #212f64;
				}
				.cnwidget.cnfp .cntweet .cnitem-desc a {
				color: #212f64;
				}
				.cnwidget.cnfp .cnitem-footer {
				border-bottom: 4px solid #212f64;
				}
			</style>

			<div data-crowdynews-widget="BeasleyBroadcastGroupInc_610-sports-main"><script src="//widget.crowdynews.com/BeasleyBroadcastGroupInc_610-sports-main.js" async="true"></script></div>

		</section>

		<?php get_sidebar(); ?>

	</div>

<?php get_footer(); ?>
