<?php
/**
 * The front page template file
 *
 * @package Greater Media
 * @since   0.1.0
 */

get_header();

?>

	<div class="container">

<!--	<div class="cycle-slideshow"
			data-cycle-timeout="5000"
			data-cycle-prev=".slick-prev"
			data-cycle-next=".slick-next"
			data-cycle-slides="> div.feature-post-slide"
			data-cycle-auto-height=container
			data-cycle-pager=".slick-dots" >
			<div class="feature-post-slide">
				<div class="slide-content">
					<img src="http://files.greatermedia.com/uploads/sites/2/2016/10/Roger-Waters-2017-Hero-1175x572.jpg" alt="">
					<h3 class="slide-heading">This is the Post Title</h3>
				</div>
			</div>
			<div class="feature-post-slide">
				<div class="slide-content">
					<img src="http://placehold.it/800x400" alt="">
					<h3 class="slide-heading">This is the Post Title</h3>
				</div>
			</div>
			<div class="feature-post-slide">
				<div class="slide-content">
					<img src="http://placehold.it/800x400" alt="">
					<h3 class="slide-heading">This is the Post Title</h3>
				</div>
			</div>
			<div class="feature-post-slide">
				<div class="slide-content">
					<img src="http://placehold.it/800x400" alt="">
					<h3 class="slide-heading">This is the Post Title</h3>
				</div>
			</div>
			<div class="slick-arrow">
				<a href=# class="slick-prev">Prev</a>
				<a href=# class="slick-next">Next</a>
			</div>
		</div>
		<div class="slick-dots"></div>  -->

		<?php do_action( 'do_frontpage_highlights' ); ?>

		<section class="content">

			<?php if ( is_news_site() ) : ?>
				<h2 class="content__heading"><?php _e( 'News', 'greatermedia' ); ?></h2>
			<?php else : ?>
				<?php get_template_part( 'partials/ad-in-loop' ); ?>
				<h2 class="content__heading"><?php _e( 'Latest from ', 'greatermedia' ); ?><?php bloginfo( 'name' ); ?></h2>
			<?php endif; ?>

			<?php if ( have_posts() ) : ?>

				<?php get_template_part( 'partials/loop', 'front-page' ); ?>
				<?php greatermedia_load_more_button( array( 'partial_slug' => 'partials/loop', 'auto_load' => true ) ); ?>

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

		<?php get_sidebar(); ?>

	</div>

<?php get_footer(); ?>
