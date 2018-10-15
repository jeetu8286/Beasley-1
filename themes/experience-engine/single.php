<?php get_header(); ?>

<?php the_post(); ?>

<div>
	<div>
		<?php the_post_thumbnail( 'medium' ); ?>
	</div>
	<h1><?php the_title(); ?></h1>

	<div>
		<?php the_content(); ?>
	</div>
</div>

<?php get_footer(); ?>