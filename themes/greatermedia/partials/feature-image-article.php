<?php
/**
 * This runs a check to determine if the post has a thumbnail, and that it's not a gallery or video post format.
 */
if ( has_post_thumbnail() && ! \Greater_Media\Fallback_Thumbnails\post_has_gallery() && ! has_post_format( 'video' ) && ! has_post_format( 'audio' )  ) :
	$image_attr = image_attribution();

	?><div class="article__thumbnail">
		<img src="<?php gm_post_thumbnail_url( 'gmr-gallery' ) ?>" alt=""><?php

		if ( ! empty( $image_attr ) ) :
			echo $image_attr;
		endif;
	?></div><?php
endif;
