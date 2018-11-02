<?php get_header(); ?>

<h1>Podcasts</h1>

<div style="display:flex">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php get_template_part( 'partials/tile', get_post_type() ); ?>
	<?php endwhile; ?>
</div>

<?php ee_load_more(); ?>

<?php get_footer(); ?>
