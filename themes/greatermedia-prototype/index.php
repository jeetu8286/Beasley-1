<?php
/**
 * The main template file
 *
 * @package Greater Media Prototype
 * @since 0.1.0
 */

 get_header();
 	do_action( 'show_latest_breaking_news_item' );

 	do_action( 'gmr_get_homepage_layout' );

	while( have_posts() ):
		the_post();
		?>
			<article <?php post_class(); ?>>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php the_excerpt( 'read more >' ); ?>
			</article>
		<?php
	endwhile;
get_footer();
?>

<div class="gigya-screen-set" id="my-screen-set" style="display:none">
</div>
