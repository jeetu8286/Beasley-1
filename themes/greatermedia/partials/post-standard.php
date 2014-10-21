<?php
/**
 * Partial for Standard Post Format
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
		<span class="vcard entry-author"><span class="fn url"><?php the_author_posts_link(); ?></span></span>
		<time datetime="<?php the_time( 'c' ); ?>" class="entry-date"> on <?php the_time( 'l, F jS' ); ?></time>
		<a href="<?php the_permalink(); ?>/#comments" class="entry-comments--count"><?php comments_number( 'No Comments', '1 Comment', '% Comments' ); ?></a>
	</div>

	<div class="entry-show">
		<div class="show-logo"></div>
		<div class="show-name">Show Name</div>
	</div>

	<div class="entry-personality">
		<div class="personality-avatar"></div>
		<div class="personality-name">Personality Name</div>
	</div>

	<h2 class="entry-title" itemprop="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

</header>

<?php if ( has_post_thumbnail() ) { ?>

	<section class="entry-thumbnail">

		<?php the_post_thumbnail( 'gm-article-thumbnail' ); ?>

	</section>

<?php } ?>

<section class="entry-content" itemprop="articleBody">

	<?php the_excerpt(); ?>

</section>

<footer class="entry-footer">

	<div class="entry-categories">
		<ul class="entry-list entry-list--categories">
			<li class="entry-list--item">Category</li>
			<li class="entry-list--item">Category</li>
		</ul>
	</div>

	<div class="entry-tags">
		<ul class="entry-list entry-list--tags">
			<li class="entry-list--item">Tag</li>
			<li class="entry-list--item">Tag</li>
			<li class="entry-list--item">Tag</li>
			<li class="entry-list--item">Tag</li>
		</ul>
	</div>

</footer>