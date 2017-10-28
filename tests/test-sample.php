<?php
/**
 * Class SampleTest
 *
 * @package Primary_Category_Plugin
 */

/**
 * Sample test case.
 */
class SampleTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	function test_sample() {
		// Replace this with some actual testing code.
		$this->assertTrue( true );
	}

	/**
	 * Test if a constant defined by plugin is available.
	 */
	function test_plugin_loaded() {
		$this->assertTrue( defined( 'PRIMARY_CATEGORY_PLUGIN_DIR' ) );
	}
	/**
	 * Test if a constant defined by plugin is available.
	 */
	function test_plugin_loaded() {
		$this->assertTrue( defined( 'PRIMARY_CATEGORY_PLUGIN_URL' ) );
	}
}
