<?php
/**
 * Single Post template file
 *
 * @package Greater Media
 * @since   0.1.0
 */

get_header(); ?>

	<main class="main" role="main">

		<div class="container">

			<section class="content">

				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope
					         itemtype="http://schema.org/BlogPosting">

						<header class="article-header">

							<a href="<?php the_permalink(); ?>"><h2 class="entry-title" itemprop="headline"><?php the_title(); ?></h2></a>

							<div class="byline">
								by
								<span class="vcard author"><span class="fn url"><?php the_author_posts_link(); ?></span></span>
								<time datetime="<?php the_time( 'c' ); ?>" class="post-date updated">
									on <?php the_time( 'l, F jS' ); ?> at <?php the_time( 'g:ia' ); ?></time>
							</div>

						</header>

						<section class="entry-content" itemprop="articleBody">

							<?php
								the_content();

								$link_args = array(
									'before' => '<div class="page-numbers">Pages: ',
									'after' => '</div>',
									'link_before' => '<span class="post-pagination-link">',
									'link_after' => '</span>'
								);
								echo '<div class="post-pagination">';
								wp_link_pages( $link_args );
								echo '</div>';

							?>

						</section> <?php // end article section ?>

						<footer class="article-footer">

						</footer>

					</article>

				<?php endwhile;

				else : ?>

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