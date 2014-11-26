<?php

namespace GreaterMedia\Gigya\Sync;

class LauncherTest extends \WP_UnitTestCase {

	public $launcher;

	function setUp() {
		parent::setUp();

		$this->launcher = new Launcher();
	}

	function tearDown() {
		parent::tearDown();
	}

	function test_it_can_initialize_tasks_lazily() {
		$this->assertEmpty( $this->launcher->tasks );

		$this->launcher->get_tasks();
		$this->assertNotEmpty( $this->launcher->tasks );
	}

	function test_it_can_register_async_actions() {
		$this->launcher->register();
		$actual = has_action( 'sync_initializer_async_job' );
		$this->assertEquals( 2, $actual );
	}

	function test_it_can_lookup_tasks_lazily() {
		$actual = $this->launcher->get_task( 'initializer' );
		$this->assertInstanceOf(
			'GreaterMedia\Gigya\Sync\InitializerTask', $actual
		);
	}

	function test_it_launches_initializer_task_with_correct_params() {
		$this->launcher->launch( 1, 'export' );
		$actual = wp_async_task_last_added();

		$this->assertEquals( 1, $actual['params']['member_query_id'] );
		$this->assertEquals( 'export', $actual['params']['mode'] );
	}

	function test_it_enqueues_initializer_on_launch() {
		$this->launcher->register();
		$this->launcher->launch( 1, 'export' );
		$actual = $this->launcher->get_task( 'initializer' );

		wp_async_task_run_last();

		$this->assertEquals( 1, $actual->get_param( 'member_query_id' ) );
		$this->assertEquals( 'export', $actual->get_param( 'mode' ) );
	}

}
