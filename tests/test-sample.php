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

	function test_plugin_loaded() {
		// we load a constant in the plugin so if we're loaded this should be true.
		$this->assertTrue( defined( 'PRIMARY_CATEGORY_PLUGIN_DIR' ) );
	}
}
