<?php
/**
 * This runs a check to determine if the post has a thumbnail, and that it's not a gallery or video post format.
 */
if ( has_post_thumbnail() && ! \Greater_Media\Fallback_Thumbnails\post_has_gallery() && ! has_post_format( 'video' ) && ! has_post_format( 'audio' )  ) :
	$image_attr = image_attribution();
	$size = \Greater_Media\Flexible_Feature_Images\feature_image_preference_is( get_the_ID(), 'poster' )
		? 'gm-article-thumbnail'
		: 'gmr-gallery';

	?><div class="article__thumbnail">
		<img src="<?php gm_post_thumbnail_url( $size ); ?>" alt=""><?php

		if ( ! empty( $image_attr ) ) :
			echo $image_attr;
		endif;
	?></div><?php
endif;
