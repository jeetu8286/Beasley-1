<?php
/**
 * Archive template file for Galleries
 *
 * @package Greater Media
 * @since   0.1.0
 *
 * @todo this template file still needs to be layed out according to the design
 */

get_header(); ?>

	<main class="main" role="main">

		<div class="container">

			<section class="gallery__featured">

				<h2 class="page__title" itemprop="headline"><?php _e( 'Galleries', 'greatermedia' ); ?></h2>

				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'gallery-grid__column' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

					<div class="gallery__featured--thumbnail">

					</div>

					<div class="gallery__featured--caption">

						<h3 class="gallery__featured--title">
							Title
						</h3>

					</div>

				</article>

				<?php endwhile; ?>

					<div class="posts-pagination">

						<div class="posts-pagination--previous"><?php next_posts_link( '<i class="fa fa-angle-double-left"></i>Previous' ); ?></div>
						<div class="posts-pagination--next"><?php previous_posts_link( 'Next<i class="fa fa-angle-double-right"></i>' ); ?></div>

					</div>

				<?php else : ?>

					<article id="post-not-found" class="hentry cf">

						<header class="article-header">

							<h1><?php _e( 'Oops, Post Not Found!', 'greatermedia' ); ?></h1>

						</header>

						<section class="entry-content">

							<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'greatermedia' ); ?></p>

						</section>

					</article>

				<?php endif; ?>

			</section>

			<section class="gallery-grid">

				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'gallery-grid__column' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

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

				<?php endwhile; ?>

					<div class="posts-pagination">

						<div class="posts-pagination--previous"><?php next_posts_link( '<i class="fa fa-angle-double-left"></i>Previous' ); ?></div>
						<div class="posts-pagination--next"><?php previous_posts_link( 'Next<i class="fa fa-angle-double-right"></i>' ); ?></div>

					</div>

				<?php else : ?>

					<article id="post-not-found" class="hentry cf">

						<header class="article-header">

							<h1><?php _e( 'Oops, Post Not Found!', 'greatermedia' ); ?></h1>

						</header>

						<section class="entry-content">

							<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'greatermedia' ); ?></p>

						</section>

					</article>

				<?php endif; ?>

			</section>

		</div>

	</main>

<?php get_footer(); ?>