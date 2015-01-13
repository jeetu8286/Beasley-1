<?php

namespace Greater_Media\Posts_Screen_Thumbnails;

class Thumbnail_Size
{
	public function __construct()
	{
		add_action( 'after_setup_theme', array( $this, 'register_thumbnail_size' ) );
	}
	
	public function register_thumbnail_size()
	{
		add_image_size( 'greater_media/thumbnail_column', 80, 60, true );
	}
}
