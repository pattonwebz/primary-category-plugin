<?php
/**
 * Class PluginActiveTest
 *
 * @package Primary_Category_Plugin
 */

/**
 * PluginActiveTest.
 */
class PluginActiveTest extends WP_UnitTestCase {

	/**
	 * Test if a constant defined by plugin is available.
	 */
	function test_plugin_loaded_const_dir() {
		$this->assertTrue( defined( 'PRIMARY_CATEGORY_PLUGIN_DIR' ) );
		$this->assertTrue( defined( 'PRIMARY_CATEGORY_PLUGIN_URL' ) );
	}

}
