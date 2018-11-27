<?php

get_header();

if ( ee_is_first_page() ):
	get_template_part( 'partials/archive/title' );
endif;

if ( have_posts() ) :
	?><div class="archive-tiles -grid content-wrap"><?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'partials/tile', get_post_type() );
		endwhile;
	?></div><?php

	ee_load_more();
else :
	ee_the_have_no_posts();
endif;

get_footer();
