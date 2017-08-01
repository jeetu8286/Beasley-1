<?php

namespace GreaterMedia\HomepageCountdownClock;

use \WP_Query;

function current_countdown_clock_query() {

	$post = new WP_Query(
		array(
			'post_type'              => gmr_countdownclocks_slug(),
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'update_post_term_cache' => false,
			'order'									 => 'ASC',
			'orderby'								 => 'meta_value_num',
			'meta_key'							 => 'countdown-date',
			'meta_value'						 => time(),
			'meta_compare'					 => '>',
		)
	);

	return $post;
}

/**
 * Return home page countdown clock ad slot name.
 * @return string
 */
function get_dfp_ad_slot() {
	return 'dfp_ad_homepage_countdown_clock';
}