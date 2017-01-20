<?php
/**
 * Single LiveFyre Media Wall template file
 *
 * @package Greater Media
 * @since   0.1.0
 */

get_header(); ?>

	<div class="container">

		<section class="content">
			<div id="wall"></div>
		</section>

		<aside class="sidebar">
			<?php dynamic_sidebar( 'liveplayer_sidebar' ); ?>
		</aside>

	</div>

<?php get_footer();