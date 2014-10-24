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

						<?php /* <header class="entry-header">

							<div class="entry-type">

								<div class="entry-type--<?php greatermedia_post_formats(); ?>"><?php greatermedia_post_formats(); ?></div>

							</div>

							<div class="entry-byline">
								by
								<span class="vcard entry-author"><span class="fn url"><?php the_author_posts_link(); ?></span></span>
								<time datetime="<?php the_time( 'c' ); ?>" class="entry-date"> on <?php the_time( 'l, F jS' ); ?></time>
								<a href="<?php the_permalink(); ?>/#comments" class="entry-comments--count"><?php comments_number( 'No Comments', '1 Comment', '% Comments' ); ?></a>
							</div>

							<div class="show entry-show">
								<div class="show-attr--logo"></div>
								<div class="show-attr--name">Show Name</div>
							</div>

							<div class="personality entry-personality">
								<div class="personality-attr--img"></div>
								<div class="personality-attr--name">Personality Name</div>
							</div>

							<h2 class="entry-title" itemprop="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

						</header> */ ?>

						<?php

							if ( has_post_format( 'video' ) ) {

								get_template_part( 'partials/post', 'video' );

							} elseif ( has_post_format( 'audio') ) {

								get_template_part( 'partials/post', 'audio' );

							} elseif ( has_post_format( 'link') ) {

								get_template_part( 'partials/post', 'link' );

							} elseif ( has_post_format( 'gallery') ) {

								get_template_part( 'partials/post', 'gallery' );

							} else {

								get_template_part( 'partials/post', 'standard' );

							}

						?>

						<footer class="entry-footer">

							<div class="entry-byline">
								<div class="entry-author--img">

								</div>
								<div class="entry-author--name"><?php the_author_posts_link(); ?></div>
								<time datetime="<?php the_time( 'c' ); ?>" class="entry-date"><?php the_time( 'M. j, Y' ); ?></time>
							</div>

							<div class="entry-type">

								<div class="entry-type--<?php greatermedia_post_formats(); ?>"><?php greatermedia_post_formats(); ?></div>

							</div>

							<div class="entry-comments">

								<div class="entry-comments--count">

									<a href="<?php the_permalink(); ?>#comments"><?php comments_number( '0', '1', '%' ); ?></a>

								</div>

							</div>

						</footer>

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

			<?php

			get_sidebar( 'live-player');

			?>

		</div>

	</main>

<?php get_footer(); ?>