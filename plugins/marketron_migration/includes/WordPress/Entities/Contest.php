<?php

namespace WordPress\Entities;

class Contest extends Post {

	public $guest_entry_count = 0;

	function get_post_type() {
		return 'contest';
	}

	function add( &$fields ) {
		$contest_id           = $fields['contest_id'];
		$contest_type         = $fields['contest_type'];
		$contest_title        = $fields['contest_title'];
		$contest_start        = $fields['contest_start'];
		$contest_end          = $fields['contest_end'];
		$contest_single_entry = $fields['contest_single_entry'];
		$contest_members_only = $fields['contest_members_only'];
		$contest_survey       = $fields['contest_survey'];
		$contest_confirmation = $fields['contest_confirmation'];
		$contest_entries      = $fields['contest_entries'];

		$contest_start = $this->to_timestamp( $contest_start );
		$contest_end   = $this->to_timestamp( $contest_end );

		$fields['post_title'] = htmlentities( $contest_title );

		$meta = array(
			'form-thankyou'        => $contest_confirmation,
			'contest_type'         => $contest_type,
			'contest-start'        => $contest_start,
			'contest-end'          => $contest_end,
			'contest-members-only' => intval( $contest_members_only ),
			'contest-single-entry' => intval( $contest_single_entry ),
		);

		if ( ! empty( $fields['inline_contest_form'] ) ) {
			$meta['embedded_form'] = json_encode( $fields['inline_contest_form'] );
		} else if ( ! empty( $contest_survey ) ) {
			$meta['embedded_form'] = $this->get_contest_form( $contest_survey );
		}

		$fields['postmeta'] = $meta;
		$fields           = parent::add( $fields );
		$contest_id       = $fields['ID'];

		if ( ! empty( $contest_id ) ) {
			$this->add_contest_entries( $contest_id, $contest_entries );
		}

		if ( ! empty( $contest_shows ) ) {
			$this->set_contest_shows( $contest_id, $contest_shows );
		}

		return $fields;
	}

	function set_contest_shows( $contest_id, $contest_shows ) {
		$entity = $this->get_entity( 'show_taxonomy' );

		foreach ( $contest_shows as $show ) {
			$entity->add( $show, $contest_id );
		}
	}

	function to_timestamp( $input ) {
		return strtotime( $input );
	}

	function get_contest_form( $survey_id ) {
		$entity = $this->get_entity( 'survey' );
		$form   = $entity->get_survey_form( $survey_id );

		if ( ! empty( $form ) ) {
			return json_encode( $form );
		} else {
			return '[]';
		}
	}

	function add_contest_entries( $contest_id, $entries ) {
		$entity         = $this->get_entity( 'contest_entry' );
		$gigya_users    = $this->get_entity( 'gigya_user' );
		//$total        = count( $entries );
		//$progress_bar = new \WordPress\Utils\ProgressBar( $msg, $total );

		foreach ( $entries as $entry ) {
			$entry['contest_id'] = $contest_id;
			$member_id           = $entry['member_id'];

			$entry = $entity->add( $entry );

			if ( ! empty( $member_id ) ) {
				$gigya_users->add_contest_entry( $member_id, $entry );
			} else {
				$this->guest_entry_count++;
			}
		}
	}


}