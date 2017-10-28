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
	function test_plugin_loaded_const_dir() {
		$this->assertTrue( defined( 'PRIMARY_CATEGORY_PLUGIN_DIR' ) );
	}
	/**
	 * Test if a constant defined by plugin is available.
	 */
	function test_plugin_loaded_const_url() {
		$this->assertTrue( defined( 'PRIMARY_CATEGORY_PLUGIN_URL' ) );
	}

	function test_metabox_modifications_class() {
		$this->assertTrue( class_exists( 'PWWP_Primary_Category_Metabox_Modifications' ) );
	}
	function test_widget_class() {
		$this->assertTrue( class_exists( 'PWWP_Widget_Primary_Categories' ) );
	}
	function test_metabox_shortcode_class() {
		$this->assertTrue( class_exists( 'PWWP_PC_Query_Shortcode' ) );
	}

}
