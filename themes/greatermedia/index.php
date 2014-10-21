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

			<aside class="live-player">

				<div class="now-playing--logo">

				</div>

				<?php do_action( 'gm_live_player' ); ?>

				<div class="now-playing--title">

				</div>

				<div class="now-playing--artist">

				</div>

				<div class="live-player--social">

					<ul>
						<li></li>
						<li></li>
						<li></li>
						<li></li>
					</ul>

				</div>

				<div class="live-player--next">
					Up Next: <span class="live-player--next--artist">Pierre Robert</span>
				</div>

			</aside>

		</div>

	</main>

<?php get_footer();