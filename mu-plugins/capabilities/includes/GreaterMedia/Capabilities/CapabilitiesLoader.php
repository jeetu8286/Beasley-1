<?php

namespace GreaterMedia\Capabilities;

class CapabilitiesLoader {

	public $role_defaults = array(
		'administrator' 			=> true,
		'editor'        			=> true,
		'editor_with_export'	=> true,
		'author'       				=> false,
		'subscriber'    			=> false,
		'contributor'   			=> false,
		'dj'            			=> false,
	);

	/*
	 * Capabilities loader should never overwrite the capabilities of built-in
	 * Wordpress post types.  Identify those here so they won't be affected in
	 * change_roles.
	 *
	 */
	public $internal_capabilities = array(
		'read',
		'edit_post',
		'read_post',
		'delete_post',
		'create_posts',
		'edit_posts',
		'edit_others_posts',
		'publish_posts',
		'read_private_posts',
		'delete_posts',
		'delete_private_posts',
		'delete_published_posts',
		'delete_others_posts',
		'edit_private_posts',
		'edit_published_posts'
	);

	function load( $post_type ) {
		$this->change_roles( $post_type, 'add' );
	}

	function unload( $post_type ) {
		$this->change_roles( $post_type, 'remove' );
	}

	function change_roles( $post_type, $action ) {
		$roles        = $this->get_roles();
		$capabilities = $this->find( $post_type );

		foreach ( $roles as $role_name => $role_info ) {
			$role         = get_role( $role_name );
			$role_default = $this->get_role_default( $role_name );

			foreach ( $capabilities as $capability ) {
				if ( ! in_array( $capability, $this->internal_capabilities ) ){
					if ( $action === 'add' ) {
						//error_log( "add_cap: $post_type - $role_name - " . $capability );
						$role->add_cap( $capability, $role_default );
					} else if ( $action === 'remove' ) {
						//error_log( "remove_cap: $post_type - $role_name - " . $capability );
						$role->remove_cap( $capability );
					}
				}
			}
		}
	}

	function find( $post_type ) {
		$post_type_obj = get_post_type_object( $post_type );

		if ( $post_type_obj && property_exists( $post_type_obj, 'cap' ) ) {
			return $post_type_obj->cap;
		} else {
			error_log( "Error: Empty Capabilities for $post_type" );
			return array();
		}
	}

	function get_roles() {
		return get_editable_roles();
	}

	function get_role_default( $role_name ) {
		if ( array_key_exists( $role_name, $this->role_defaults ) ) {
			return $this->role_defaults[ $role_name ];
		} else {
			return false;
		}
	}

}
