<?php

namespace GreaterMedia\Gigya;

require_once INCLUDES . '/GreaterMedia/Gigya/class-direct-query-meta-box.php';

class DirectQueryMetaBoxTest extends \WP_UnitTestCase {

	public $box;

	function setUp() {
		parent::setUp();

		$this->box = new DirectQueryMetaBox( null );
	}

	function test_it_has_a_unique_id() {
		$this->assertEquals( 'direct_query', $this->box->get_id() );
	}

	function test_it_has_a_title() {
		$this->assertEquals( 'Direct Query', $this->box->get_title() );
	}

	function test_it_has_a_side_context() {
		$this->assertEquals( 'side', $this->box->get_context() );
	}

	function test_it_has_low_priority() {
		$this->assertEquals( 'low', $this->box->get_priority() );
	}

	function test_it_has_a_template_file() {
		$this->assertEquals( 'direct_query', $this->box->get_template() );
	}

	function test_its_template_file_exists() {
		$path = $this->box->get_template_path( $this->box->get_template() );
		$this->assertTrue( file_exists( $path ) );
	}

}
