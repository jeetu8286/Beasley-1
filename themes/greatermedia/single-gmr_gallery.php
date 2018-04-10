<?php

get_header();

if ( get_query_var( 'view' ) ) {
	get_template_part( 'content-gallery-slideshow' );
} else {
	get_template_part( 'content-gallery' );
}

get_footer();
