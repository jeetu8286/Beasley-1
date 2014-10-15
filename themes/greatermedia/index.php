<?php
/**
 * The main template file
 *
 * @package Greater Media
 * @since   0.1.0
 */

get_header(); ?>

	<main class="main" role="main">

		<div class="container">

			<section class="content">

				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

						<?php

							$image_formats = has_post_format(
								array(
									'gallery', 'image',
								)
							);

							if ( has_post_thumbnail() && ( $image_formats || false == get_post_format() ) ) { ?>

							<section class="article-thumbnail">

								<?php the_post_thumbnail( 'gm-article-thumbnail' ); ?>

							</section>

						<?php } ?>

						<header class="article-header">

							<a href="<?php the_permalink(); ?>"><h2 class="entry-title" itemprop="headline"><?php the_title(); ?></h2></a>

							<div class="byline">
								by
								<span class="vcard author"><span class="fn url"><?php the_author_posts_link(); ?></span></span>
								<time datetime="<?php the_time( 'c' ); ?>" class="post-date updated">
									on <?php the_time( 'l, F jS' ); ?> at <?php the_time( 'g:ia' ); ?></time>
							</div>

						</header>

						<?php
							if ( false == get_post_format() ) {
						?>
							<section class="entry-content" itemprop="articleBody">

								<?php the_excerpt(); ?>

							</section> <?php // end article section ?>

						<?php } ?>

						<footer class="article-footer">

						</footer>

					</article>

				<?php endwhile; ?>

					<div class="pagination">

						<div class="pagination-previous"><?php next_posts_link( '<i class="fa fa-angle-double-left"></i>Previous' ); ?></div>
						<div class="pagination-next"><?php previous_posts_link( 'Next<i class="fa fa-angle-double-right"></i>' ); ?></div>

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

			<aside class="sidebar" role="complementary">

				Sidebar area

			</aside>

		</div>

	</main>

<?php get_footer();