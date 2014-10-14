<?php
/**
 * Personality archive template
 *
 * @package Greater Media Prototype
 * @since   0.1.0
 */

get_header();

while ( have_posts() ):
	the_post();
?>
	<article <?php post_class(); ?>>
		<h3>
			<span style="margin-right: 0.5em"><?php gmi_print_personality_photo( null, 50 ); ?></span>
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
	</article>
<?php
	endwhile;
	wp_reset_postdata();
	get_footer();
?>
