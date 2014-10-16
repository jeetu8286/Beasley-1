<?php

namespace GreaterMedia\Gigya;

/**
 * MemberQueryPostType manages registration of the `member_query` post
 * type with WordPress.
 *
 * @package GreaterMedia\Gigya
 */
class MemberQueryPostType {

	/**
	 * Registers the `member_query` custom post type.
	 *
	 * @access public
	 * @return void
	 */
	public function register() {
		register_post_type(
			$this->get_post_type_name(), $this->get_options()
		);
	}

	/**
	 * Returns the labels for the `member_query` post type.
	 *
	 * @access public
	 * @return void
	 */
	function get_labels() {
		return array(
			'name'               => _x( 'Member Query', 'post type general name', 'gmr_gigya' ),
			'singular_name'      => _x( 'Member Query', 'post type singular name', 'gmr_gigya' ),
			'menu_name'          => _x( 'Member Queries', 'admin menu', 'gmr_gigya' ),
			'name_admin_bar'     => _x( 'Member Query', 'add new on admin bar', 'gmr_gigya' ),
			'add_new'            => _x( 'Add New', 'member query', 'gmr_gigya' ),
			'add_new_item'       => __( 'Add New Member Query', 'gmr_gigya' ),
			'new_item'           => __( 'New Member Query', 'gmr_gigya' ),
			'edit_item'          => __( 'Edit Member Query', 'gmr_gigya' ),
			'view_item'          => __( 'View Member Query', 'gmr_gigya' ),
			'all_items'          => __( 'All Member Queries', 'gmr_gigya' ),
			'search_items'       => __( 'Search Member Queries', 'gmr_gigya' ),
			'parent_item_colon'  => __( 'Parent Member Queries:', 'gmr_gigya' ),
			'not_found'          => __( 'No member queries found.', 'gmr_gigya' ),
			'not_found_in_trash' => __( 'No member queries found in Trash.', 'gmr_gigya' )
		);
	}

	/**
	 * Returns the configuration options for the `member_query` post
	 * type.
	 *
	 * @access public
	 * @return array
	 */
	public function get_options() {
		return array(
			'labels'             => $this->get_labels(),
			'supports'           => $this->get_supports(),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'page',
			'has_archive'        => false,
			'hierarchical'       => false,
		);
	}


	/**
	 * The `member_query` CPT only uses the title support. Rest of the
	 * meta boxes are custom.
	 *
	 * @access public
	 * @return void
	 */
	public function get_supports() {
		return array( 'title' );
	}

	/**
	 * The post type name for the MemberQuery CPT.
	 *
	 * @access public
	 * @return string
	 */
	public function get_post_type_name() {
		return 'member_query';
	}

}
