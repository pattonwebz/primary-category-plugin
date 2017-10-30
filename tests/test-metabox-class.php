<?php
/**
 * MetaboxClassTest
 *
 * @package Primary_Category_Plugin
 */

/**
 * Tests to make sure the metabox is working as expected.
 */

class MetaboxClassTest extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
	}

	public function test_widget_class_exists() {
		// if the class exists instansiate and return it's object
		$metabox_obj = false;
		if( class_exists( 'PWWP_Primary_Category_Metabox_Modifications' ) ) {
			$metabox_obj = new PWWP_Primary_Category_Metabox_Modifications;
		}
		// in php 5.4 or below this ::class doesn't work :( .
		//$this->assertInstanceOf( PWWP_Primary_Category_Metabox_Modifications::class, $metabox_obj );
		$this->assertTrue( $metabox_obj instanceof PWWP_Primary_Category_Metabox_Modifications );
		return $metabox_obj;
	}

}
