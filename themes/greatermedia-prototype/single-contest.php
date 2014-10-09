<?php
get_header();

if ( is_gigya_user_logged_in() ) {
	while ( have_posts() ):
		the_post();
		?>
		<article <?php post_class(); ?>>
			<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

			<?php
				$contest_form_id = 1;
				gravity_form( $contest_form_id );
			?>

			<?php GMI_Gigya_Share::display_share_buttons(); ?>
		</article>
		<?php
	endwhile;
} else {
	echo '<article><h3>Please login</h3></article>';
}
get_footer();

?>
