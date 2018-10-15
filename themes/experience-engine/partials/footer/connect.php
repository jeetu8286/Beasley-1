<?php

if ( ! has_nav_menu( 'connect-nav' ) ) :
	return;
endif;

wp_nav_menu( array( 'theme_location' => 'connect-nav' ) );
