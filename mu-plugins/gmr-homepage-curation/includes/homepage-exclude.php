<?php
/**
 * Allow posts to be excluded from home page searches.
 *
 * @package GreaterMedia\HomepageCuration
 */

namespace GreaterMedia\HomepageCuration;

function add_meta_boxes() {
	$screens = apply_filters( 'gmr-homepage-exclude-post-types', [ 'post' ] );
	add_meta_box( 'keep-off-homepage', 'Keep Off Homepage', __NAMESPACE__ . '\render_meta_box', $screens, 'side' );
}
add_action( 'add_meta_boxes', __NAMESPACE__ . '\add_meta_boxes' );

function render_meta_box() {
	load_template( 'metabox-keep-off-homepage.php' );
}
