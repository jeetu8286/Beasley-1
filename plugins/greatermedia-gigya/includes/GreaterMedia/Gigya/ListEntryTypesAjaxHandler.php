<?php

namespace GreaterMedia\Gigya;

class ListEntryTypesAjaxHandler extends AjaxHandler {

	public function get_action() {
		return 'list_entry_types';
	}

	public function run( $params ) {
		// TODO: filter by params entryType
		$forms      = \RGFormsModel::get_forms( true );
		$entryTypes = array();

		foreach ( $forms as $form ) {
			$entryTypes[] = array(
				'label' => $form->title,
				'value' => $form->id,
			);
		}

		return $entryTypes;
	}

}
