<?php
/**
 * Checks for IE8 or lower, and sets appropriate cache bucket for these users. This is required if we're going to make
 * the inline podcast play/pause buttons work properly (use the live player) on browsers that support it, and allows
 * still using media element if not.
 *
 * If vary_cache_on_function() is not present, we don't do this at all, since we won't be able to create an appropriate
 * cache bucket. We just always assume greater than IE8, since this will be the majority of users, and the better
 * experience overall
 */

function is_greater_than_ie8() {
	if ( ! function_exists( 'vary_cache_on_function' ) ) {
		return true;
	}

	vary_cache_on_function(
		'preg_match(\'/MSIE (.*?);/\', $_SERVER[\'HTTP_USER_AGENT\'], $matches);
		if( count($matches) < 2 ){
			preg_match(\'/Trident\/\d{1,2}.\d{1,2}; rv:([0-9]*)/\', $_SERVER[\'HTTP_USER_AGENT\'], $matches);
		}

		if (count($matches)>1){
			$version = $matches[1];

			if ( $version <= 8 ) {
				return false;
			}
			return true;
		}'
	);

	preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
	if( count($matches) < 2 ){
		preg_match('/Trident\/\d{1,2}.\d{1,2}; rv:([0-9]*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
	}

	if (count($matches)>1){
		//Then we're using IE
		$version = $matches[1];

		if ( $version <= 8 ) {
			return false;
		}
		return true;
	}
}
