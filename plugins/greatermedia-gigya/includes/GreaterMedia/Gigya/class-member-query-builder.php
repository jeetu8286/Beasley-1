<?php

namespace GreaterMedia\Gigya;

/**
 * MemberQueryBuilder assembles the JSON representation of a Member
 * Query.
 *
 * Assembly may be done manually by providing the attributes of the
 * query as properties, or via prepare which looks up the properties
 * from the POST global.
 *
 * @package GreaterMedia\Gigya
 */
class MemberQueryBuilder {

	/**
	 * The list of constraints for the current member query.
	 *
	 * @access public
	 * @var array
	 */
	public $constraints;

	/**
	 * The generated GQL query for the current list of constraints.
	 *
	 * @access public
	 * @var string
	 */
	public $query;

	/**
	 * Optional direct query that overrides the generated GQL query.
	 *
	 * Note: Must be valid GQL.
	 *
	 * @access public
	 * @var string
	 */
	public $direct_query;

	/**
	 * Loads the MemberQuery attributes from POST.
	 *
	 * @access public
	 * @return void
	 */
	public function prepare() {
		$this->constraints  = $this->load_constraints();
		$this->query        = $this->load_query();
		$this->direct_query = $this->load_direct_query();
	}

	/**
	 * Builds the JSON representation of the MemberQuery properties.
	 *
	 * @access public
	 * @return string The generated JSON for storage.
	 */
	public function build() {
		$content = <<<JSON
{
	"constraints": {$this->constraints},
	"query": "{$this->query}",
	"direct_query": "{$this->direct_query}"
}
JSON;

		return $content;
	}

	/**
	 * Loads the constraints from POST if present. Else returns an empty
	 * json array.
	 *
	 * @access public
	 * @return string
	 */
	public function load_constraints() {
		error_log( print_r($_POST, true) );
		if ( array_key_exists( 'constraints', $_POST ) ) {
			return $_POST['constraints'];
		} else {
			return '[]';
		}
	}

	/**
	 * Loads the generated GQL query from POST if present. Else returns an empty
	 * string.
	 *
	 * @access public
	 * @return string
	 */
	public function load_query() {
		if ( array_key_exists( 'query', $_POST ) ) {
			return $_POST['query'];
		} else {
			return '';
		}
	}

	/**
	 * Loads the direct query from POST if present. Else returns an empty
	 * string.
	 *
	 * @access public
	 * @return string
	 */
	public function load_direct_query() {
		if ( array_key_exists( 'direct_query', $_POST ) ) {
			return $_POST['direct_query'];
		} else {
			return '';
		}
	}

}
