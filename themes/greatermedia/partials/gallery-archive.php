<h2 class="content__heading" itemprop="headline">Latest Galleries</h2>
<?php
$featured = greatermedia_get_featured_gallery();
if ( $featured ) :
	$GLOBALS['post'] = $featured;
	setup_postdata( $featured );

	?><div class="gallery__featured">
		<div class="gallery__featured--primary gallery__grid-album">
			<?php get_template_part( 'partials/gallery-featured', 'primary' ); ?>
		</div>
	</div>

	<?php wp_reset_postdata(); ?>

	<?php if ( ! is_post_type_archive( 'gmr_gallery' ) ) : ?>
		<div class="gallery__grid gallery__grid-album">
			<?php get_template_part( 'partials/loop-album' ); ?>
		</div>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>

	<div class="gallery__grid gallery__grid-album">
		<?php get_template_part( 'partials/loop-gallery' ); ?>
	</div>

<?php else : ?>

	<article id="post-not-found" class="hentry cf">

		<header class="article-header">
			<?php if ( 'show' == get_post_type() ) : ?>
				<h2 class="entry__title"><?php the_title(); ?> does not have galleries... yet!</h2>
			<?php else : ?>
				<h2 class="entry__title">There are currently no galleries</h2>
			<?php endif; ?>
		</header>

		<?php if ( 'show' == get_post_type() ) : ?>
			<section class="entry__content">
				<a href="<?php the_permalink(); ?>" class="gallery__back--btn">Back</a>
			</section>
		<?php endif; ?>

	</article>

<?php endif; ?>