<?php

namespace WordPress\Entities;

class Show extends Post {

	function get_post_type() {
		return 'show';
	}

	function add( &$fields ) {
		$show_name   = $fields['show_name'];
		$show_author = $fields['show_author'];

		if ( array_key_exists( 'show_meta', $fields ) ) {
			$show_meta = $fields['show_meta'];
		} else {
			$show_meta = array(
				'show_homepage' => 1,
				'show_homepage_galleries' => 1,
				'show_homepage_podcasts' => 1,
				'show_homepage_videos' => 0,
			);
		}

		$fields['post_author'] = $this->get_show_author_id( $show_author );
		$fields['post_title']  = $show_name;
		$fields['postmeta']    = $show_meta;

		$fields = parent::add( $fields );

		$show_taxonomy_entity = $this->get_entity( 'show_taxonomy' );
		$show_taxonomy_entity->add( $show_name );

		return $fields;
	}

	function get_show_author_id( $name ) {
		$table = $this->get_table( 'posts' );

		if ( $table->has_row_with_field( 'show_name', $name ) ) {
			$show    = $table->get_row_with_field( 'show_name', $name );
			$show_id = $show['ID'];
		} else {
			$show_id = 0;
		}

		return $show_id;
	}

}
