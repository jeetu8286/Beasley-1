<?php get_header(); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>><?php
	get_template_part( 'partials/show-block' );

	$query = ee_get_show_query();
	if ( $query->have_posts() ) :
		?><div class="archive-tiles">
			<?php ee_the_query_tiles( $query ); ?>
		</div><?php

		ee_load_more( $query );
	endif;
?></div>

<?php get_footer(); ?>