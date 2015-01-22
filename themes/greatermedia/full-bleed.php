<?php
/**
 * Template Name: Full Bleed
 */
get_header(); ?>

	<main class="main" role="main">

		<?php the_post(); ?>

		<div class="container">

			<?php the_content(); ?>

		</div>
		
	</main>

<?php get_footer();