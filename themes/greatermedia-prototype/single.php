<?php
/**
 * The main template file
 *
 * @package Greater Media Prototype
 * @since   0.1.0
 */

get_header();

while ( have_posts() ):
	the_post();
	?>
	<article <?php post_class(); ?>>
		<h1><a href="<?php the_permalink(); ?>" class="pjaxer"><?php the_title(); ?></a></h1>
		<?php the_content( 'read more >' ); ?>
		<?php
			// GMI_Gigya_Share::display_share_buttons();

			if ( is_singular( 'post' ) ) {
				//comments_template();
			}
		?>
	</article>
<?php
endwhile;
get_footer();
