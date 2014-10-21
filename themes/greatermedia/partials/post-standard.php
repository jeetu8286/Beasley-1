<?php
/**
 * Partial for Standard Post Format
 *
 * @package Greater Media
 * @since   0.1.0
 */
?>
<?php if ( has_post_thumbnail() ) { ?>

	<section class="entry-thumbnail">

		<?php the_post_thumbnail( 'gm-article-thumbnail' ); ?>

	</section>

<?php } ?>

<section class="entry-content" itemprop="articleBody">

	<?php if ( is_single() ) {

		the_content();

	} else {

		the_excerpt();

	} ?>

</section>