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
		<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		<?php the_content( 'read more >' ); ?>
		<?php
			//GMI_Gigya_Share::display_share_buttons();

			if ( is_singular( 'post' ) ) {
				//comments_template();
			}

			if ( is_singular( GMI_Personality::CPT_SLUG ) ) {
				echo '<h3>Posts Written by ' . get_the_title() . '</h3>';
				?>
				<ol>
					<?php
						$author_id = get_post_meta( get_the_ID(), 'assoc_user', true );
						$author_posts = new WP_Query( array(
							'author' => $author_id
						) );
						if ( $author_id && $author_posts->have_posts()):
							while( $author_posts->have_posts() ):
								$author_posts->the_post();
								?>
									<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
								<?php
							endwhile;
						endif;
					?>
				</ol>
				<?php
			}
		?>
	</article>
<?php
endwhile;
get_footer();
