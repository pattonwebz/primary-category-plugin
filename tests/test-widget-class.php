<?php
/**
 * WidgetClassTest
 *
 * @package Primary_Category_Plugin
 */

/**
 * Tests to make sure the widget is working as expected.
 */

class WidgetClassTest extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
	}

	public function test_widget_class_exists() {
		// if the class exists instansiate and return it's object
		$widget_obj = false;
		if( class_exists( 'PWWP_Widget_Primary_Categories' ) ) {
			$widget_obj = new PWWP_Widget_Primary_Categories;
		}
		// in php 5.4 or below this ::class doesn't work :( .
		//$this->assertInstanceOf( PWWP_Widget_Primary_Categories::class, $widget_obj );
		$this->assertTrue( $widget_obj instanceof PWWP_Widget_Primary_Categories );

		return $widget_obj;
	}

}
