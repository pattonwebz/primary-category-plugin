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
	 * Test if a constant defined by plugin is available.
	 */
	function test_plugin_loaded_const_dir() {
		$this->assertTrue( defined( 'PRIMARY_CATEGORY_PLUGIN_DIR' ) );
	}
	/**
	 * Test if a constant defined by plugin is available.
	 */
	function test_plugin_loaded_const_url() {
		$this->assertTrue( defined( 'PRIMARY_CATEGORY_PLUGIN_URL' ) );
	}

}
