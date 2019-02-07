<?php

namespace Bbgi\Integration;

class Dfp extends \Bbgi\Module {

	private static $_sensitive_types = array( 'post', 'gmr_gallery' );

	/**
	 * Registers this module.
	 *
	 * @access public
	 */
	public function register() {
		add_action( 'wp_loaded', $this( 'register_metabox' ), 20 );
		add_filter( 'dfp_single_targeting', $this( 'update_single_targeting' ) );
	}

	/**
	 * Registers meta box.
	 *
	 * @access public
	 */
	public function register_metabox() {
		$fields = $location = array();

		$fields[] = array(
			'key'               => 'field_sensitive_content',
			'label'             => 'Sensitive Content',
			'name'              => 'sensitive_content',
			'type'              => 'true_false',
			'instructions'      => '',
			'required'          => 0,
			'conditional_logic' => 0,
			'message'           => '',
			'default_value'     => 0,
			'ui'                => 1,
			'ui_on_text'        => '',
			'ui_off_text'       => '',
		);

		foreach ( self::$_sensitive_types as $type ) {
			$location[] = array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => $type,
				),
			);
		}

		acf_add_local_field_group( array(
			'key'                   => 'group_dfp_settings',
			'title'                 => 'DFP Settings',
			'fields'                => $fields,
			'location'              => $location,
			'menu_order'            => 0,
			'position'              => 'side',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => 1,
			'description'           => '',
		) );
	}

	/**
	 * Updates single slot targeting.
	 *
	 * @access public
	 * @param array $targeting
	 * @return array
	 */
	public function update_single_targeting( $targeting ) {
		foreach ( self::$_sensitive_types as $type ) {
			if ( is_singular( $type ) ) {
				$field = get_field( 'sensitive_content', get_queried_object_id() );
				if ( filter_var( $field, FILTER_VALIDATE_BOOLEAN ) ) {
					$targeting[] = array( 'sensitive', 'yes' );
				}
			}
		}

		return $targeting;
	}

}
