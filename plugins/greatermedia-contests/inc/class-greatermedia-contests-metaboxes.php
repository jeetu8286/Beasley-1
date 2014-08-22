<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaContestsMetaboxes {

	function __construct() {

		add_action( 'custom_metadata_manager_init_metadata', array( $this, 'custom_metadata_manager_init_metadata' ), 20, 3 );

	}

	public function custom_metadata_manager_init_metadata() {

		// Groups
		x_add_metadata_group(
			'prizes',
			array('contest'),
			array(
				'label'    => 'Prizes', // Label for the group
			)
		);

		x_add_metadata_group(
			'how-to-enter',
			array('contest'),
			array(
				'label'    => 'How to Enter', // Label for the group
			)
		);

		x_add_metadata_group(
			'rules',
			array('contest'),
			array(
				'label'    => 'Rules', // Label for the group
			)
		);

		x_add_metadata_group(
			'dates',
			array('contest'),
			array(
				'label'    => 'Eligible Dates', // Label for the group
			)
		);

		// Fields
		x_add_metadata_field(
			'prizes-desc',
			array('contest'),
			array(
				'group'                   => 'prizes', // The slug of group the field should be added to. This needs to be registered with x_add_metadata_group first.
				'field_type'              => 'wysiwyg', // The type of field; 'text', 'textarea', 'password', 'checkbox', 'radio', 'select', 'upload', 'wysiwyg', 'datepicker', 'taxonomy_select', 'taxonomy_radio'
				'label'                   => 'What You Win', // Label for the field
			)
		);

		x_add_metadata_field(
			'how-to-enter-desc',
			array('contest'),
			array(
				'group'                   => 'how-to-enter', // The slug of group the field should be added to. This needs to be registered with x_add_metadata_group first.
				'field_type'              => 'wysiwyg', // The type of field; 'text', 'textarea', 'password', 'checkbox', 'radio', 'select', 'upload', 'wysiwyg', 'datepicker', 'taxonomy_select', 'taxonomy_radio'
				'label'                   => null, // Label for the field
			)
		);

		x_add_metadata_field(
			'rules-desc',
			array('contest'),
			array(
				'group'                   => 'rules', // The slug of group the field should be added to. This needs to be registered with x_add_metadata_group first.
				'field_type'              => 'wysiwyg', // The type of field; 'text', 'textarea', 'password', 'checkbox', 'radio', 'select', 'upload', 'wysiwyg', 'datepicker', 'taxonomy_select', 'taxonomy_radio'
				'label'                   => 'Official Contest Rules', // Label for the field
			)
		);

		x_add_metadata_field(
			'start-date',
			array('contest'),
			array(
				'group'                   => 'dates', // The slug of group the field should be added to. This needs to be registered with x_add_metadata_group first.
				'field_type'              => 'datepicker', // The type of field; 'text', 'textarea', 'password', 'checkbox', 'radio', 'select', 'upload', 'wysiwyg', 'datepicker', 'taxonomy_select', 'taxonomy_radio'
				'label'                   => 'Start Date', // Label for the field
			)
		);

		x_add_metadata_field(
			'end-date',
			array('contest'),
			array(
				'group'                   => 'dates', // The slug of group the field should be added to. This needs to be registered with x_add_metadata_group first.
				'field_type'              => 'datepicker', // The type of field; 'text', 'textarea', 'password', 'checkbox', 'radio', 'select', 'upload', 'wysiwyg', 'datepicker', 'taxonomy_select', 'taxonomy_radio'
				'label'                   => 'End Date', // Label for the field
			)
		);

	}
}

$GreaterMediaContestsMetaboxes = new GreaterMediaContestsMetaboxes();