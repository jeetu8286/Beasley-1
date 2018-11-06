<?php

get_header();

if ( ee_is_first_page() ) :
	the_archive_title( '<h1 class="archive-title">', '</h1>' );
endif;

echo '<div class="archive-tiles">';
	while ( have_posts() ) :
		the_post();
		get_template_part( 'partials/tile', get_post_type() );
	endwhile;
echo '</div>';

ee_load_more();

get_footer();
