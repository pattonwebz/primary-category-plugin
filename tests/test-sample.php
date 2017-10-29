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
	/**
	 * Test if this from our plugin is loaded and exists.
	 */
	function test_metabox_modifications_class() {
		$this->assertTrue( class_exists( 'PWWP_Primary_Category_Metabox_Modifications' ) );
	}
	/**
	 * Test if this from our plugin is loaded and exists.
	 */
	function test_widget_class() {
		$this->assertTrue( class_exists( 'PWWP_Widget_Primary_Categories' ) );
	}

}
