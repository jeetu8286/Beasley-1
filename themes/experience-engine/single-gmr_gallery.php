<?php get_header(); ?>

<?php the_post(); ?>

<div>
	<?php get_template_part( 'partials/show-information' ); ?>
	<?php get_template_part( 'partials/show-navigation' ); ?>
</div>

<?php get_footer(); ?>