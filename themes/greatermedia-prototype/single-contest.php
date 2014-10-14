<?php
get_header();

if ( is_gigya_user_logged_in() ) {
	while ( have_posts() ):
		the_post();
		?>
		<article <?php post_class(); ?>>
			<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

			<?php

				$post_id         = get_the_ID();
				$contest_form_id = get_post_meta( $post_id, 'contest_form_id', true );

				if ( $contest_form_id ) {
					gravity_form( $contest_form_id );
				}

			?>

		</article>
		<?php
	endwhile;
} else {
	echo '<article><h3>Please login</h3></article>';
}
get_footer();

?>
