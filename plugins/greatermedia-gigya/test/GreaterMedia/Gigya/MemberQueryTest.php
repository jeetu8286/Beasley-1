<?php

namespace GreaterMedia\Gigya;

class MemberQueryTest extends \WP_UnitTestCase {

	public $query;
	public $post_id;

	function setUp() {
		parent::setUp();

		$this->post_id = $this->factory->post->create();
		$this->query   = new MemberQuery( $this->post_id );
	}

	function query_for( $content ) {
		$post_id = $this->factory->post->create();
		update_post_meta( $post_id, 'member_query_constraints', $content );

		$query   = new MemberQuery( $post_id );

		return $query;
	}

	function test_it_can_store_a_wp_post_id() {
		$post_id = $this->factory->post->create();
		$query   = new MemberQuery( $post_id );
		$this->assertEquals( $post_id, $query->post_id );
	}

	function test_it_can_find_content_from_wp_post_meta() {
		$query = $this->query_for( '{"a":1}' );
		$this->assertEquals( '{"a":1}', $query->content_for( $query->post_id ) );
	}

	function test_it_can_parse_empty_content() {
		$actual = $this->query->parse( '' );
		$this->assertEmpty( $actual['constraints'] );
	}

	function test_it_knows_a_member_querys_constraints() {
		$content = '[ "a", "b", "c" ]';
		$query = $this->query_for( $content );
		$expected = array( 'a', 'b', 'c' );

		$this->assertEquals( $expected, $query->get_constraints() );
	}

	function test_it_knows_suffixes_for_gigya_types() {
		$query = $this->query_for( '[]' );
		$this->assertEquals( '_i', $query->suffix_for( 'integer' ) );
		$this->assertEquals( '_f', $query->suffix_for( 'float' ) );
		$this->assertEquals( '_s', $query->suffix_for( 'string' ) );
		$this->assertEquals( '_t', $query->suffix_for( 'text' ) );
		$this->assertEquals( '_b', $query->suffix_for( 'boolean' ) );
		$this->assertEquals( '_d', $query->suffix_for( 'date' ) );
	}

	function test_it_uses_string_suffix_for_unknown_types() {
		$query = $this->query_for( '[]' );
		$this->assertEquals( '_s', $query->suffix_for( 'foo' ) );
	}

	function test_it_knows_name_of_a_field() {
		$actual = $this->query->field_name_for( 'entryType', 'string' );
		$this->assertEquals( 'data.entries.entryType_s', $actual );
	}

	function test_it_can_build_clause_for_constraint() {
		$constraint = array(
			'type'        => 'profile:city',
			'operator'    => 'contains',
			'conjunction' => 'and',
			'valueType'   => 'string',
			'value'       => 'New York',
		);

		$actual = $this->query->clause_for_constraint( $constraint );
		$expected = "data.entries.entryType_s = 'profile:city' and data.entries.entryValue_s contains 'New York'";
		$this->assertEquals( $expected, $actual );
	}

	function test_it_can_build_clause_for_record_constraint() {
		$constraint = array(
			'type'         => 'record:contest',
			'operator'     => 'equals',
			'conjunction'  => 'and',
			'valueType'    => 'string',
			'value'        => 'New York',
			'entryTypeID'  => 100,
			'entryFieldID' => 200,
		);

		$actual = $this->query->clause_for_constraint( $constraint );
		$expected = "data.entries.entryType_s = 'record:contest' and data.entries.entryTypeID_i = 100 and data.entries.entryFieldID_i = 200 and data.entries.entryValue_s = 'New York'";
		$this->assertEquals( $expected, $actual );
	}

	function test_it_can_build_query_for_profile_and_record_contraints() {
		$constraints = array(
			array(
				'type'        => 'profile:city',
				'operator'    => 'contains',
				'conjunction' => 'and',
				'valueType'   => 'string',
				'value'       => 'New York',
			),
			array(
				'type'         => 'record:contest',
				'operator'     => 'equals',
				'conjunction'  => 'and',
				'valueType'    => 'string',
				'value'        => 'New York',
				'entryTypeID'  => 100,
				'entryFieldID' => 200,
			),
		);

		$actual = $this->query->clause_for( $constraints );
		$expected = "data.entries.entryType_s = 'profile:city' and data.entries.entryValue_s contains 'New York' and data.entries.entryType_s = 'record:contest' and data.entries.entryTypeID_i = 100 and data.entries.entryFieldID_i = 200 and data.entries.entryValue_s = 'New York'";
		$this->assertEquals( $expected, $actual );
	}

	function test_it_can_build_query_from_constraints() {
		$constraints = array(
			array(
				'type'        => 'profile:city',
				'operator'    => 'contains',
				'conjunction' => 'and',
				'valueType'   => 'string',
				'value'       => 'New York',
			),
			array(
				'type'         => 'record:contest',
				'operator'     => 'equals',
				'conjunction'  => 'and',
				'valueType'    => 'string',
				'value'        => 'New York',
				'entryTypeID'  => 100,
				'entryFieldID' => 200,
			),
		);

		$this->query = $this->query_for( json_encode( $constraints ) );
		$actual = $this->query->to_gql();
		$expected = "select UID from entries where data.entries.entryType_s = 'profile:city' and data.entries.entryValue_s contains 'New York' and data.entries.entryType_s = 'record:contest' and data.entries.entryTypeID_i = 100 and data.entries.entryFieldID_i = 200 and data.entries.entryValue_s = 'New York'";
		$this->assertEquals( $expected, $actual );
	}

	function test_it_can_save_constraints_in_post_meta() {
		$constraints = array(
			array(
				'type'        => 'profile:city',
				'operator'    => 'contains',
				'conjunction' => 'and',
				'valueType'   => 'string',
				'value'       => 'New York',
			),
			array(
				'type'         => 'record:contest',
				'operator'     => 'equals',
				'conjunction'  => 'and',
				'valueType'    => 'string',
				'value'        => 'New York',
				'entryTypeID'  => 100,
				'entryFieldID' => 200,
			),
		);

		$_POST['constraints'] = json_encode( $constraints );

		$this->post_id = $this->factory->post->create();
		$this->query = new MemberQuery( $this->post_id );
		$this->query->build_and_save();

		$new_query = new MemberQuery( $this->query->post_id );
		$actual = $new_query->get_constraints();
		$this->assertEquals( $constraints, $actual );
	}

	function test_it_can_build_gql_from_user_generated_json() {
		$json = '[{"type":"system:isActive","valueType":"boolean","operator":"equals","conjunction":"and","value":false}] ';
	}

}
