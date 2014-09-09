<?php

namespace GmrGigya;

require_once __DIR__ . '/class-member-query-post-type.php';
require_once __DIR__ . '/class-preview-meta-box.php';
require_once __DIR__ . '/class-direct-query-meta-box.php';
require_once __DIR__ . '/class-query-builder-meta-box.php';
require_once __DIR__ . '/class-member-query.php';

class Plugin {

	public $plugin_file;

	function __construct( $plugin_file ) {
		$this->plugin_file = $plugin_file;
	}

	function enable() {
		add_action( 'init', array( $this, 'initialize' ) );
		add_action( 'add_meta_boxes', array( $this, 'initialize_meta_boxes' ), 10, 2 );
		add_filter( 'wp_insert_post_data', array( $this, 'serialize_member_query' ), 10, 2 );
	}

	function initialize() {
		$member_query_post_type = new MemberQueryPostType();
		$member_query_post_type->register();
	}

	function initialize_meta_boxes( $post_type, $post ) {
		$member_query     = new MemberQuery( $post );
		$preview_meta_box = new PreviewMetaBox( $member_query );
		$preview_meta_box->register();

		$direct_query_meta_box = new DirectQueryMetaBox( $member_query );
		$direct_query_meta_box->register();

		$gigya_social_meta_box = new GigyaSocialMetaBox( $member_query );
		$gigya_social_meta_box->register();

		$this->initialize_scripts( $member_query );
		$this->initialize_styles( $member_query );
	}

	function initialize_scripts( $member_query ) {

	}

	function initialize_styles( $member_query ) {

	}

	function serialize_member_query( $sanitized_data, $raw_data = null ) {
		$member_query = new MemberQuery ( $raw_data );
		$sanitized_data['post_content'] = $member_query->to_json();

		return $sanitized_data;
	}

}
