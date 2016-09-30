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

	function load( $post_type ) {
		$this->change_roles( $post_type, 'add' );
	}

	function unload( $post_type ) {
		$this->change_roles( $post_type, 'remove' );
	}

	function change_roles( $post_type, $action ) {
		$roles        = $this->get_roles();
		$capabilities = $this->find( $post_type );

		// Determine built-in capabilities so we can avoid modifying them for
		// custom post types.

		$_builtin_caps = array();

		$_post_types = get_post_types( array( '_builtin' => true ), 'objects' );
		foreach ( $_post_types as $_post_type ) {
			$_builtin_caps = array_merge( $_builtin_caps, array_values( (array) $_post_type->cap ) );
		}

		$_builtin_caps = array_unique( $_builtin_caps );

		foreach ( $roles as $role_name => $role_info ) {
			$role         = get_role( $role_name );
			$role_default = $this->get_role_default( $role_name );

			foreach ( $capabilities as $capability ) {
				if ( ! in_array( $capability, $_builtin_caps ) ){
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
