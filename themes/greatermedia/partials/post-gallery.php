<?php
/**
 * Partial for Gallery Post Format
 *
 * @package Greater Media
 * @since   0.1.0
 */
?>
<header class="entry-header">

	<div class="entry-type">

		<div class="entry-type--<?php greatermedia_post_formats(); ?>"><?php greatermedia_post_formats(); ?></div>

	</div>

	<div class="entry-byline">
		by
		<span class="vcard entry-byline--author"><span class="fn url"><?php the_author_posts_link(); ?></span></span>
		<time datetime="<?php the_time( 'c' ); ?>" class="entry-byline--date"> on <?php the_time( 'l, F jS' ); ?></time>
		<a href="<?php the_permalink(); ?>/#comments" class="entry-byline--comments_count"><?php comments_number( 'No Comments', '1 Comment', '% Comments' ); ?></a>
	</div>

	<h2 class="entry-title" itemprop="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

</header>

<section class="entry-content" itemprop="articleBody">

	<?php the_content(); ?>

</section>

<footer class="entry-footer">

</footer>