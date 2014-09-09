<?php

namespace GmrGigya;

class MemberQuery {

	public $post;
	public $attributes = null;

	function __construct( $post ) {
		$this->post = $post;
		$this->attributes = $this->parse($this->content_for( $post ));
	}

	function content_for( $post ) {
		if ( $post instanceof \WP_Post ) {
			return $post->post_content;
		} else {
			return $post['post_content'];
		}
	}

	function parse( $content ) {
		$json = json_decode( $content, true );
		if ( ! is_array( $json ) ) {
			$json = array();
		}

		if ( ! array_key_exists( 'constraints', $json ) ) {
			$json['constraints'] = array();
		}

		if ( ! array_key_exists( 'direct_query', $json ) ) {
			$json['direct_query'] = '';
		}

		return $json;
	}

	function has_direct_query() {
		return $this->attributes['direct_query'] !== '';
	}

	function get_direct_query() {
		return $this->attributes['direct_query'];
	}

	function get_constraints() {
		return $this->attributes['constraints'];
	}

	function to_json() {
		return json_encode( $this->attributes );
	}

}
