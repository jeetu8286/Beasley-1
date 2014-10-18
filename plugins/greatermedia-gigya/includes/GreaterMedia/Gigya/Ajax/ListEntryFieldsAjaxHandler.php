<?php

namespace GreaterMedia\Gigya\Ajax;

class ListEntryFieldsAjaxHandler extends AjaxHandler {

	public function get_action() {
		return 'list_entry_fields';
	}

	public function run( $params ) {
		$form_id      = $params['entryTypeID'];
		$form         = \GFAPI::get_form( $form_id );
		$form_fields  = $form['fields'];
		$entry_fields = array();

		foreach ( $form_fields as $form_field ) {
			$entry_fields[] = $this->entry_field_for( $form_field );
		}

		return $entry_fields;
	}

	public function entry_field_for( $form_field ) {
		$entry_field            = array();
		$entry_field['label']   = $form_field['label'];
		$entry_field['value']   = $form_field['id'];
		$entry_field['type']    = $form_field['type'];
		$entry_field['choices'] = $this->choices_for( $form_field );

		return $entry_field;
	}

	public function choices_for( $form_field ) {
		$type    = $form_field['type'];
		$choices = array();

		if ( $type === 'select' || $type === 'checkbox' ) {
			$choices = $this->choices_for_select( $form_field );
		} elseif ( $type === 'text' || $type === 'email' ) {
			$choices = array();
		} elseif ( $type === 'number' ) {
			$choices = array();
		} elseif ( $type === 'boolean' ) {
			$choices = array(
				array( 'label' => 'yes', 'value' => true ),
				array( 'label' => 'no', 'value' => false ),
			);
		}

		return $choices;
	}

	public function choices_for_select( $form_field ) {
		$form_choices = $form_field['choices'];
		$choices      = array();

		foreach ( $form_choices as $form_choice ) {
			$choices[] = array(
				'label' => $form_choice['text'],
				'value' => $form_choice['value'],
			);
		}

		return $choices;
	}

}
