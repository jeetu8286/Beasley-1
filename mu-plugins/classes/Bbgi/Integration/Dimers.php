<?php
/**
 * Module responsible for managing Google Doubleclick for Publishers
 *
 * Registers ACF metabox (checkbox) for marking content as sensitive
 * Updates Single Targeting to denote sensitve content if meta is present.
 */
namespace Bbgi\Integration;

class Dimers extends \Bbgi\Module
{

	public function register()
	{
		add_action('dimers_widget',$this('dimers_render_div'));
	}

	public function dimers_render_div() {

		$post = get_post();
		$category_match = false;

		$categories = get_the_category( $post );

		foreach ( $categories as $category ) {
			if ( $category->slug == 'sports-betting' ) {
				$category_match = true;
			}
		}

		if ( $category_match == true ) {
			echo '<div class="dimers"></div>';
		}
	}
}
