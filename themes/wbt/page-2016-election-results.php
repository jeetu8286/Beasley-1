<?php
/**
 * Single Post template file
 *
 * @package Greater Media
 * @since   0.1.0
 */

get_header();
?>

<div class="container">
	<section class="content">
		<article id="post-242" class="article cf post-242 page type-page status-publish hentry" role="article" itemscope="" itemtype="http://schema.org/BlogPosting">
			<header class="article__header">
				<h2 class="article__title" itemprop="headline"><?php the_title(); ?></h2>
				<?php get_template_part( 'partials/social-share' ); ?>
			</header>
			<section class="article__content" itemprop="articleBody">
				<?php the_content(); ?>

				<div class="ad__in-loop ad__in-loop--desktop">
					<?php do_action( 'acm_tag_gmr_variant', 'leaderboard-body', 'desktop' ); ?>
				</div>
				<div class="ad__in-loop ad__in-loop--mobile">
					<?php do_action( 'acm_tag_gmr_variant', 'mrec-lists', 'mobile' ); ?>
				</div>

				<iframe src="http://interactives.ap.org/2016/general-election/?SITE=WBTAMELN" class="ap-embed" width="100%" height="900" style="border: 1px solid #eee;">
					Your browser does not support the <code>iframe</code> HTML tag. Try viewing this in a modern browser like Chrome, Safari, Firefox or Internet Explorer 9 or later.
				</iframe>

			</section>
		</section>
	</article>
</div>

<?php get_footer(); ?>
