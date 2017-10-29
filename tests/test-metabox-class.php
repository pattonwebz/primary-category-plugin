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
		} else {
		}
		$this->assertInstanceOf( PWWP_Primary_Category_Metabox_Modifications::class, $metabox_obj );
		return $metabox_obj;
	}

}
