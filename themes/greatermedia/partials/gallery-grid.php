<div class="gallery__grid">

	<?php

	if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>


		<article id="post-<?php the_ID(); ?>" <?php post_class( 'gallery__grid--column' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

			<div class="gallery__grid--thumbnail">
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'gmr-gallery-grid-thumb' ); ?>
				</a>
			</div>

			<div class="gallery__grid--meta">
				<h3 class="gallery__grid--title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h3>
			</div>

		</article>

	<?php endwhile;

		greatermedia_gallery_album_nav();

		wp_reset_postdata();

	else : ?>

		<article id="post-not-found" class="hentry cf">
			<header class="article-header">
				<h1><?php _e( 'Oops, Post Not Found!', 'antenna_theme' ); ?></h1>
			</header>
			<section class="entry-content">
				<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'antenna_theme' ); ?></p>
			</section>
		</article>

	<?php endif; ?>

</div>