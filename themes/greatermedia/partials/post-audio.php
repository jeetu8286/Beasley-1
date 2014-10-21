<?php
/**
 * Partial for Audio Post Format
 *
 * @package Greater Media
 * @since   0.1.0
 */
?>
<header class="article-header">

	<div class="article-types">

		<div class="article-type--<?php greatermedia_post_formats(); ?>"><?php greatermedia_post_formats(); ?></div>

	</div>

	<div class="byline">
		by
		<span class="vcard author"><span class="fn url"><?php the_author_posts_link(); ?></span></span>
		<time datetime="<?php the_time( 'c' ); ?>" class="post-date updated"> on <?php the_time( 'l, F jS' ); ?></time>
		<a href="<?php the_permalink(); ?>/#comments"
		   class="article-comments--count"><?php comments_number( 'No Comments', '1 Comment', '% Comments' ); ?></a>
	</div>

	<h2 class="entry-title" itemprop="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

</header>

<section class="entry-content" itemprop="articleBody">

	<?php the_content(); ?>

</section>

<footer class="article-footer">

</footer>