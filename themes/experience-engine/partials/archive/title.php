<?php

$modifier = false;
if ( is_post_type_archive() ) {
	$modifier = get_query_var( 'post_type' );
}

echo '<div class="archive-title content-wrap">';
	get_template_part( 'partials/archive/icon', $modifier );
	the_archive_title( '<h1>', '</h1>' );
echo '</div>';
