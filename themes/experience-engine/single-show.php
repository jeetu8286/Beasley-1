<?php get_header(); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php get_template_part( 'partials/show-block' ); ?>
</div>

<?php get_footer(); ?>