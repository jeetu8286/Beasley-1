<?php get_header(); ?>

<?php the_post(); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php get_template_part( 'partials/show/header' ); ?>
	<?php get_template_part( 'partials/featured-media' ); ?>
	<h1><?php the_title(); ?></h1>

	<div>
		<div>
			<?php the_content(); ?>
		</div>

		<?php get_template_part( 'partials/ad-slot' ); ?>
	</div>
</div>

<?php get_footer(); ?>