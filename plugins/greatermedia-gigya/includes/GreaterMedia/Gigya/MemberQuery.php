<?php

namespace GreaterMedia\Gigya;

/**
 * MemberQuery represents the content of the MemberQueryPostType. It is
 * a json representation of the constraints, query and direct query that
 * make up an individual MemberQueryPostType.
 *
 * The MemberQuery JSON is stored in postmeta corresponding to it's
 * parent MemberQuery CPT.
 *
 * @package GreaterMedia\Gigya
 */
class MemberQuery {

	/**
	 * The id of the post to which the current MemberQuery belongs.
	 *
	 * @access public
	 * @var integer
	 */
	public $post_id;

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
	public function __construct( $post_id ) {
		$this->post_id = $post_id;
		$this->properties = $this->parse( $this->content_for( $post_id ) );
	}

	/**
	 * Fetches the raw JSON content for a post using postmeta lookups.
	 *
	 * @access public
	 * @param integer $post
	 * @return string The json representation of a MemberQuery.
	 */
	public function content_for( $post_id ) {
		return get_post_meta( $post_id, 'member_query_json', true );
	}

	/**
	 * Parses the JSON representation of a MemberQuery into a PHP array.
	 *
	 * @access public
	 * @param string The JSON to parse
	 * @return array
	 */
	public function parse( $content ) {
		if ( $content !== '' ) {
			$json = json_decode( $content, true );

			if ( ! is_array( $json ) ) {
				$json = array();
			}
		} else {
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
	 * Builds the MemberQuery from POST data.
	 *
	 * @access public
	 * @return string JSON built from the POST data.
	 */
	public function build() {
		$constraints  = $this->load_post_param( 'constraints', '[]' );
		$query        = $this->load_post_param( 'query', '' );
		$direct_query = $this->load_post_param( 'direct_query', '' );

		$content = <<<JSON
{
	"constraints": {$constraints},
	"query": "{$query}",
	"direct_query": "{$direct_query}"
}
JSON;

		return $content;
	}

	/**
	 * Returns the value of a POST parameter if present or returns the
	 * specified default.
	 *
	 * @access public
	 * @param string $name The name of the parameter
	 * @param mixed $default The default value to return if absent
	 * @return string
	 */
	public function load_post_param( $name, $default ) {
		if ( array_key_exists( $name, $_POST ) ) {
			return $_POST[ $name ];
		} else {
			return $default;
		}
	}

	/**
	 * Builds the MemberQuery JSON from POST and saves to the current
	 * post_id's postmeta.
	 *
	 * Post Meta key is 'member_query_json'
	 *
	 * @access public
	 * @return void
	 */
	public function build_and_save() {
		$json = $this->build();
		update_post_meta( $this->post_id, 'member_query_json', $json );
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

	/**
	 * Returns the GQL to execute for this query. If a direct query is
	 * present it is used, else the generated query is returned.
	 *
	 * @access public
	 * @param bool $count Optionally Whether to return an aggregate query
	 * @param int $limit Optional row limit to apply to the query
	 * @return string
	 */
	public function to_gql( $count = false, $limit = null ) {
		$direct_query = $this->get_direct_query();
		$query = $direct_query === '' ? $this->get_query() : $direct_query;

		if ( $count ) {
			$query = str_replace( '*', 'count(*)', $query );
		}

		if ( is_int( $limit ) ) {
			$query .= " limit $limit";
		}

		return $query;
	}

}
