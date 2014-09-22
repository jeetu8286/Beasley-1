<?php ?>
	<title><?php

		global $page, $paged;

		wp_title( '|', true, 'right' );

		bloginfo( 'name' );

		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			echo " | $site_description";

		?></title>
<?php while ( have_posts() ) : the_post(); ?>
	<?php the_title(); ?>
	<?php the_content(); ?>
<?php endwhile; ?>