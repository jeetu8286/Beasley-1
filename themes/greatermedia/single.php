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

			<?php
				if ( has_post_thumbnail() ) :
					the_post_thumbnail( 'full', array( 'class' => 'single__featured-img' ) );
				endif;
			?>

			<?php
				while ( have_posts() ) : the_post(); ?>
		
					<section class="content">

						<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

							<img class="ad__inline--right" src="http://placehold.it/300x250&amp;text=inline ad">
	
							<header class="entry__header">

								<time class="entry__date" datetime="<?php echo get_the_time(); ?>"><?php the_date('F j'); ?></time>
								<h2 class="entry__title" itemprop="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<a href="http://www.facebook.com/sharer/sharer.php?u=[URL]&title=[TITLE]" class="icon-facebook social-share-link"></a>
								<a href="http://twitter.com/home?status=[TITLE]+[URL]" class="icon-twitter social-share-link"></a>
								<a href="https://plus.google.com/share?url=[URL]" class="icon-google-plus social-share-link"></a>

							</header>

							<section class="entry-content" itemprop="articleBody">

								<?php the_content(); ?>

							</section>

							<footer class="entry-footer">

								<div class="entry-author">
									<div class="entry-author--img">
										<img src="http://placecreature.com/40/40">
									</div>
									<div class="entry-author--meta">
										<div class="entry-author--name"><?php the_author_posts_link(); ?></div>
										<time datetime="<?php the_time( 'c' ); ?>" class="entry-date"><?php the_time( 'M. j, Y' ); ?></time>
									</div>
								</div>

								<div class="entry-type">

									<div class="entry-type--<?php greatermedia_post_formats(); ?>"><?php greatermedia_post_formats(); ?></div>

								</div>

								<?php
								// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}

					?>

							</footer>

						</article>

					</section>
	
			<?php endwhile; ?>

		</div>

	</main>

<?php get_footer();