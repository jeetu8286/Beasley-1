<?php
get_header();

if ( is_gigya_user_logged_in() ) {
	while ( have_posts() ):
		the_post();
		?>
		<article <?php post_class(); ?>>
			<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

			<?php
				/* TODO: TEMPORARY HACK, to demo a contest form */
				$site_url = get_site_url();
				$domain   = parse_url( $site_url, PHP_URL_HOST );

				if ( strpos( $domain, '10up' ) !== false ) {
					$contest_form_id = 4;
				} else {
					$contest_form_id = 1;
				}

				gravity_form( $contest_form_id );
			?>

		</article>
		<?php
	endwhile;
} else {
	echo '<article><h3>Please login</h3></article>';
}
get_footer();

?>
