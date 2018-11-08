<?php get_header(); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php get_template_part( 'partials/show-block' ); ?>
	<?php get_template_part( 'partials/show/featured' ); ?>
	<?php get_template_part( 'partials/show/favorites' ); ?>
	<?php get_template_part( 'partials/show/recent' ); ?>
</div>

<?php get_footer(); ?>