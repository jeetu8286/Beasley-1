<?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

		<header class="article-header">

			<div class="article-types">

				<div class="article-type--<?php greatermedia_post_formats(); ?>"><?php greatermedia_post_formats(); ?></div>

			</div>

			<div class="byline">
				by
				<span class="vcard author"><span class="fn url"><?php the_author_posts_link(); ?></span></span>
				<time datetime="<?php the_time( 'c' ); ?>" class="post-date updated"> on <?php the_time( 'l, F jS' ); ?></time>
				<a href="<?php the_permalink(); ?>/#comments" class="article-comments--count"><?php comments_number( 'No Comments', '1 Comment', '% Comments' ); ?></a>
			</div>

			<h2 class="entry-title" itemprop="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		</header>

		<?php

			$image_formats = has_post_format( array( 'gallery', 'image' ) );

			if ( has_post_thumbnail() && ( $image_formats || false == get_post_format() ) ) { ?>

			<section class="entry-thumbnail">

				<?php the_post_thumbnail( 'gm-article-thumbnail' ); ?>

			</section>

		<?php } ?>


		<?php
			if ( false == get_post_format() ) {
		?>
			<section class="entry-content" itemprop="articleBody">

				<?php the_excerpt(); ?>

			</section> <?php // end article section ?>

		<?php }

			$formats = has_post_format( array( 'video', 'audio' ) );

			if ( $formats ) {

		?>

			<section class="entry-content" itemprop="articleBody">

				<?php the_content(); ?>

			</section> <?php // end article section ?>

		<?php } ?>

		<footer class="article-footer">

		</footer>

	</article>

<?php endwhile;