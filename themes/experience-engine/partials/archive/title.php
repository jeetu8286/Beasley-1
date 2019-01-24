<?php

echo '<div class="archive-title content-wrap">';
	get_template_part( 'partials/archive/icon', get_query_var( 'post_type' ) );
	the_archive_title( '<h1>', '</h1>' );
	ee_add_to_favorites( get_queried_object()->slug );
echo '</div>';
