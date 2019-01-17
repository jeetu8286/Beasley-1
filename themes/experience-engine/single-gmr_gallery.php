<?php ee_setup_gallery_view_metadata(); // must be called before get_header(); ?>

<?php get_header(); ?>

<?php the_post(); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php get_template_part( 'partials/show/header' ); ?>

	<header class="post-info">
		<h1>
			<?php the_title(); ?>
		</h1>

		<div class="post-meta">
			<?php get_template_part( 'partials/content/meta' ); ?>
		</div>

	</header>

	<div class="entry-content content-wrap">

		<div class="description">
				
			<?php get_template_part( 'partials/featured-media' ); ?>

			<?php the_content(); ?>
			<?php get_template_part( 'partials/gallery/listicle' ); ?>

			<?php get_template_part( 'partials/content/categories' ); ?>
			<?php get_template_part( 'partials/content/tags' ); ?>
		</div>

		<?php get_template_part( 'partials/ads/sidebar-sticky' ); ?>

	</div>

</div>

<?php get_footer(); ?>