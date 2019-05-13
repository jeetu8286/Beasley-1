<?php

get_header();

ee_switch_to_article_blog();
the_post();

?><div id="post-<?php the_ID(); ?>" <?php post_class( 'single' ); ?>>

	<?php if ( ee_get_current_show() ) : ?>
		<div class="content-wrap">
			<?php get_template_part( 'partials/show/header' ); ?>
		</div>
	<?php endif; ?>

	<header class="post-info">
		<?php if ( bbgi_featured_image_layout_is( null, 'top' ) || bbgi_featured_image_layout_is( null, 'poster' ) ) : ?>
			<?php get_template_part( 'partials/featured-media', 'autoheight' ); ?>
		<?php endif; ?>

		<h1>
			<?php the_title(); ?>
		</h1>

		<?php if( is_singular( 'post' ) ) : ?>
			<div class="post-meta">
				<?php get_template_part( 'partials/content/meta' ); ?>
			</div>
		<?php endif; ?>
	</header>

	<div class="entry-content content-wrap">
		<div class="description">
			<?php if ( bbgi_featured_image_layout_is( null, 'inline' ) ) : ?>
				<?php get_template_part( 'partials/featured-media', 'autoheight' ); ?>
			<?php endif; ?>
			<?php ee_the_content_with_ads(); ?>

			<?php if ( is_singular( 'post' ) ) : ?>
				<div class="profile">
					<?php echo get_the_author_meta( 'description' ); ?>
				</div>
			<?php endif; ?>

			<?php get_template_part( 'partials/content/categories' ); ?>
			<?php get_template_part( 'partials/content/tags' ); ?>
		</div>

		<?php get_template_part( 'partials/ads/sidebar-sticky' ); ?>
	</div>

	<?php get_template_part( 'partials/related-articles' );	?>
</div><?php

restore_current_blog();
get_footer();
