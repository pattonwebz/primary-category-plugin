<?php
/**
 * Class SampleTest
 *
 * @package Primary_Category_Plugin
 */

/**
 * Sample test case.
 */
class ShortcodeClassTest extends WP_UnitTestCase {

	public $metabox_obj;

	public $term;

	private $post;

	public function setUp() {

		$this->term = $this->factory->term->create_and_get( array(
			'name' => 'test term',
			'taxonomy' => 'category',
		) );
		error_log( print_r( $this->term, true ), 0 );
		$this->post = $this->factory->post->create_and_get();

		update_post_meta( $this->post->id, '_pwwp_pc_category', $this->term->name );



	}

	/**
	 * Test if this class from our plugin is loaded and exists.
	 */
	function test_shortcode_class_exists() {
		// if the class exists instansiate and return it's object
		$shortcode_obj = false;
		if( class_exists( 'PWWP_PC_Query_Shortcode' ) ) {
			$shortcode_obj = new PWWP_PC_Query_Shortcode;
		} else {
		}
		$this->assertInstanceOf( PWWP_PC_Query_Shortcode::class, $shortcode_obj );
		return $shortcode_obj;
	}

	/**
	 * @depends test_shortcode_class_exists
	 */
	public function test_shortcode_return_no_posts( $shortcode_obj ) {
		// this term id we're asking for shouldn't exist.
		$shortcode_html = $shortcode_obj::primary_category_query_shortcode( array( 'id' => 9999 ) );
		// is a string?
		$this->assertInternalType( 'string', $shortcode_html );
		// should contain this string indicating that shortcode returns a fail.
		$this->assertContains( '<p class="pwwp-pc-none">', $shortcode_html, "Doesn't have the failed string." );
		// and it shouldn't contain this string that would be present on success.
		$this->assertNotContains( '<ul class="pwwp-pc-query-wrapper">', $shortcode_html, "Has the success string (which is a failure when we're testing a fail)" );
	}

}
