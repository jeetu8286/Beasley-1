<?php get_header(); ?>

<?php the_post(); ?>

<div>
	<?php get_template_part( 'partials/show-block' ); ?>
</div>

<?php get_footer(); ?>