<?php

namespace WordPress\Entities;

class Podcast extends Post {

	public $cached_podcasts = array();

	function get_post_type() {
		return 'podcast';
	}

	function add( &$fields ) {
		$podcast_name = trim( $fields['podcast_name'] );

		if ( array_key_exists( $podcast_name, $this->cached_podcasts ) ) {
			return $this->cached_podcasts[ $podcast_name ];
		}

		$podcast_author = $fields['podcast_author'];

		if ( array_key_exists( 'podcast_meta', $fields ) ) {
			$podcast_meta = $fields['podcast_meta'];
		} else {
			$podcast_meta = array();
		}


		$fields['post_author'] = $this->get_author_id( $podcast_author );
		$fields['post_title']  = $podcast_name;

		$fields['postmeta'] = $podcast_meta;
		$fields             = parent::add( $fields );
		$podcast_id         = $fields['ID'];

		if ( ! empty( $fields['podcast_show'] ) ) {
			$podcast_show = trim( $fields['podcast_show'] );

			if ( ! array_key_exists( 'existing_id', $fields ) ) {
				/*
				 * if podcast already exists, assuming that the
				 * association exists as well
				 */
				$this->set_podcast_show( $podcast_id, $podcast_show );
			}
		}

		$this->cached_podcasts[ $podcast_name ] = $fields;

		return $fields;
	}

	function set_podcast_show( $podcast_id, $podcast_show ) {
		$entity = $this->get_entity( 'show_taxonomy' );
		$entity->add( $podcast_show, $podcast_id );
	}

	function get_podcast_by_name( $name ) {
		if ( array_key_exists( $name, $this->cached_podcasts ) ) {
			return $this->cached_podcasts[ $name ];
		} else {
			return null;
		}
	}

	function get_show_id( $name ) {
		$table = $this->get_table( 'posts' );

		if ( $table->has_row_with_field( 'post_title', $name ) ) {
			$show    = $table->get_row_with_field( 'post_title', $name );
			$show_id = $show['ID'];
		} else {
			$show_id = 0;
		}

		return $show_id;
	}

	function get_author_id( $name ) {
		$table = $this->get_table( 'users' );

		if ( $table->has_row_with_field( 'display_name', $name ) ) {
			$show    = $table->get_row_with_field( 'display_name', $name );
			$show_id = $show['ID'];
		} else {
			$show_id = 0;
		}

		return $show_id;
	}

	function destroy() {
		$this->cached_podcasts = null;
		unset( $this->cached_podcasts );

		parent::destroy();
	}

}
