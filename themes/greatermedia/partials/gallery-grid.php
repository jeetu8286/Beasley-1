<article id="post-<?php the_ID(); ?>" <?php post_class( 'gallery__grid--column' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

	<div class="gallery-grid__thumbnail">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'gmr-gallery-grid-thumb' ); ?>
		</a>
	</div>

	<div class="gallery-grid__meta">
		<h3 class="gallery-grid__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
	</div>

</article>