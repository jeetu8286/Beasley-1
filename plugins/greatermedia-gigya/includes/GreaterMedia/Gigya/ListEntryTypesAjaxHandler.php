<?php

namespace GreaterMedia\Gigya;

class ListEntryTypesAjaxHandler extends AjaxHandler {

	public function get_action() {
		return 'list_entry_types';
	}

	public function run( $params ) {
		// TODO: This is a job for wpdb, currently in-memory ...
		// The Tables are wp_posts, wp_postmeta, wp_rg_form
		// +pagination
		$contests_args = array( 'post_type' => 'contest', 'post_status' => 'publish' );
		$query = new \WP_Query( $contests_args );
		$contests = $query->get_posts();
		$entryTypes = [];

		foreach ( $contests as $contest ) {
			$contest_form_id = get_post_meta( $contest->ID, 'contest_form_id', true );
			if ( $contest_form_id ) {
				$form = \GFAPI::get_form( $contest_form_id );
				$entryTypes[] = array(
					'label' => $form['title'],
					'value' => $form['id'],
				);
			}
		}

		return $entryTypes;
	}

}
