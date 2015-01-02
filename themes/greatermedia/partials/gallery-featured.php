<div class="gallery__featured">

	<div class="gallery__featured--primary">

	<?php
		$gallery_count = 0;

		while ( have_posts() && $gallery_count < 3 ) : $gallery_count++; the_post();

		if ( $gallery_count == 1 ) : ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'gallery__featured--item' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

		<div class="gallery__featured--thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'gmr-gallery-grid-featured' ); ?>
			</a>
		</div>

		<div class="gallery__featured--caption">

			<h3 class="gallery__featured--title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>

		</div>

	</article>

	<?php
		endif;
		endwhile;
	?>

	</div>

</div>