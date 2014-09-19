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
		$text_domain = $this->get_text_domain();

		return array(
			'name'               => _x( 'Member Query', 'post type general name', $text_domain ),
			'singular_name'      => _x( 'Member Query', 'post type singular name', $text_domain ),
			'menu_name'          => _x( 'Member Queries', 'admin menu', $text_domain ),
			'name_admin_bar'     => _x( 'Member Query', 'add new on admin bar', $text_domain ),
			'add_new'            => _x( 'Add New', 'member query', $text_domain ),
			'add_new_item'       => __( 'Add New Member Query', $text_domain ),
			'new_item'           => __( 'New Member Query', $text_domain ),
			'edit_item'          => __( 'Edit Member Query', $text_domain ),
			'view_item'          => __( 'View Member Query', $text_domain ),
			'all_items'          => __( 'All Member Queries', $text_domain ),
			'search_items'       => __( 'Search Member Queries', $text_domain ),
			'parent_item_colon'  => __( 'Parent Member Queries:', $text_domain ),
			'not_found'          => __( 'No member queries found.', $text_domain ),
			'not_found_in_trash' => __( 'No member queries found in Trash.', $text_domain )
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

	/**
	 * The default text domain for localization.
	 *
	 * @access public
	 * @return string
	 */
	public function get_text_domain() {
		return 'gmr_gigya';
	}

}
