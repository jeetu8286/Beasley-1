<?php get_header(); ?>

<div class="podcast-archive">
	<h1>Podcasts</h1>

	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php get_template_part( 'partials/tile', get_post_type() ); ?>
	<?php endwhile; ?>

	<div>
		<?php previous_posts_link(); ?>
		<?php next_posts_link(); ?>
	</div>
</div>

<?php get_footer(); ?>
