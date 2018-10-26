<?php get_header(); ?>

<?php the_post(); ?>

<div>
	<?php get_template_part( 'partials/featured-media' ); ?>
	<h1><?php the_title(); ?></h1>

	<div>
		<?php the_content(); ?>
	</div>

	<div>
		<?php $query = ee_get_episodes_query(); ?>
		<?php if ( $query->have_posts() ) : ?>
			<?php while ( $query->have_posts() ) : ?>
				<?php $query->the_post(); ?>
				<?php get_template_part( 'partials/tile', get_post_type() ); ?>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>
