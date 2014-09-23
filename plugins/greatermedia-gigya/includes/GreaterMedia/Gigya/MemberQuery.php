<?php

namespace GreaterMedia\Gigya;

/**
 * MemberQuery represents the content of the MemberQueryPostType. It is
 * a json representation of the constraints, query and direct query that
 * make up an individual MemberQueryPostType.
 *
 * @package GreaterMedia\Gigya
 */
class MemberQuery {

	/**
	 * The WP_Post or plain array corresponding to the MemberQuery.
	 *
	 * @access public
	 * @var array|WP_Post
	 */
	public $post;

	/**
	 * The properties that make up a MemberQuery.
	 *
	 * @access public
	 * @var array
	 */
	public $properties;

	/**
	 * Stores the post object corresponding to the member query and
	 * parse it's post_content into the MemberQuery properties.
	 */
	public function __construct( $post ) {
		$this->post = $post;
		$this->properties = $this->parse( $this->content_for( $post ) );
	}

	/**
	 * Extracts content from the specified post object. For WP_Post
	 * objects it's post_content property is used.
	 *
	 * And the post_content key is use for arrays.
	 *
	 * @access public
	 * @param WP_Post|array $post
	 * @return string The json representation of a MemberQuery.
	 */
	public function content_for( $post ) {
		if ( $post instanceof \WP_Post ) {
			return $post->post_content;
		} else {
			return $post['post_content'];
		}
	}

	/**
	 * Parses the JSON representation of a MemberQuery into a PHP array.
	 *
	 * @access public
	 * @param string The JSON to parse
	 * @return array
	 */
	public function parse( $content ) {
		if ( is_array( $this->post ) ) {
			$content = wp_unslash( $content );
		}

		$json = json_decode( $content, true );
		if ( ! is_array( $json ) ) {
			$json = array();
		}

		if ( ! array_key_exists( 'constraints', $json ) ) {
			$json['constraints'] = array();
		}

		if ( ! array_key_exists( 'query', $json ) ) {
			$json['query'] = '';
		}

		if ( ! array_key_exists( 'direct_query', $json ) ) {
			$json['direct_query'] = '';
		}

		return $json;
	}

	/**
	 * Returns the parsed constraints for the current member query.
	 *
	 * @access public
	 * @return array
	 */
	public function get_constraints() {
		return $this->properties['constraints'];
	}

	/**
	 * Returns the generated GQL for the current member query.
	 *
	 * @access public
	 * @return string
	 */
	public function get_query() {
		return $this->properties['query'];
	}

	/**
	 * Returns the overriding direct query if present.
	 *
	 * @access public
	 * @return string
	 */
	public function get_direct_query() {
		return $this->properties['direct_query'];
	}

	/**
	 * Converts the MemberQuery back to it's JSON representation.
	 *
	 * @access public
	 * @return string JSON representation of the current MemberQuery.
	 */
	public function to_json() {
		return json_encode( $this->properties );
	}

}
