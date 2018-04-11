<?php

get_header();

get_template_part( 'content-gallery', !! get_query_var( 'view' ) ? 'slideshow' : '' );

get_footer();
