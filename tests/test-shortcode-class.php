<?php
/**
 * ShortcodeClassTest
 *
 * @package Primary_Category_Plugin
 */

/**
 * Tests to make sure the shortcode widget iw working as expected.
 * TODO: Add tests to make sure things return the same when passed different atts meaning same thing (id, slug, name).
 */
class ShortcodeClassTest extends WP_UnitTestCase {

	private $post_with_meta;

	private $term_for_meta;

	/**
	 * Test if this class from our plugin is loaded and exists.
	 */

	public function setUp() {

		parent::setUp();
		// create a post for use in test.
		$this->post_with_meta = $this->factory->post->create_and_get();

		// create a term to use in testing.
		$this->term_for_meta = $this->factory->term->create_and_get();

		// set some meta on the post.
		update_post_meta( $this->post_with_meta->ID, '_pwwp_pc_selected_id', $this->term_for_meta->term_id );
		update_post_meta( $this->post_with_meta->ID, '_pwwp_pc_selected_slug', $this->term_for_meta->slug );
		update_post_meta( $this->post_with_meta->ID, '_pwwp_pc_selected', $this->term_for_meta->name );

	}

	public function test_shortcode_class_exists() {
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
		// this term id we're asking for shouldn't exist in any post meta.
		$shortcode_html = $shortcode_obj->primary_category_query_shortcode( array( 'id' => 9999999 ) );
		// is a string?
		$this->assertInternalType( 'string', $shortcode_html );
		// should contain this string indicating that shortcode returns a fail.
		$this->assertContains( '<p class="pwwp-pc-none">', $shortcode_html, "Doesn't have the failed string." );
		// and it shouldn't contain this string that would be present on success.
		$this->assertNotContains( '<ul class="pwwp-pc-query-wrapper">', $shortcode_html, "Has the success string (which is a failure when we're testing a fail)" );
	}
	/**
	 * @depends test_shortcode_class_exists
	 */
	public function test_shortcode_return_posts( $shortcode_obj ) {
		$shortcode_html = $shortcode_obj->primary_category_query_shortcode( array( 'id' => $this->post_with_meta->term_id ) );
		// is a string?
		$this->assertInternalType( 'string', $shortcode_html );
		// should NOT contain this string indicating that shortcode returns a fail.
		$this->assertNotContains( '<p class="pwwp-pc-none">', $shortcode_html, "Doesn't have the failed string (which is a failure when we're testing a fail)." );
		// and it should contain this string that would be present on success.
		$this->assertNotContains( '<ul class="pwwp-pc-query-wrapper">', $shortcode_html, "Has the success string." );
	}

	/**
	 * @depends test_shortcode_class_exists
	 */
	public function test_shortcode_shortcode_passing_no_required_args( $shortcode_obj ) {
		$shortcode_html = $shortcode_obj->primary_category_query_shortcode( array() );
		// is a string?
		$this->assertInternalType( 'string', $shortcode_html );
		$this->assertContains( '<!-- no id, slug or name passed to shortcode -->', $shortcode_html, "Reurned no failure comment" );
		$this->assertNotContains( '<ul class="pwwp-pc-query-wrapper">', $shortcode_html, "Has the the success string (which is fail here)." );
	}

	/**
	 * Shortcodes can be generated through id, slug, or nicename of a term.
	 * This test makes sure that the html returned is matching regardless of
	 * the atts passed to the shortcode function.
	 *
	 * @depends test_shortcode_class_exists
	 */
	public function test_shortcode_success_returns_same_for_different_atts( $shortcode_obj ) {
		$shortcode_html['id'] = $shortcode_obj->primary_category_query_shortcode( array( 'id' => $this->post_with_meta->term_id ) );
		$shortcode_html['slug'] = $shortcode_obj->primary_category_query_shortcode( array( 'slug' => $this->post_with_meta->slug ) );
		$shortcode_html['name'] = $shortcode_obj->primary_category_query_shortcode( array( 'name' => $this->post_with_meta->name  ) );
		// is a string?
		$this->assertEquals( $shortcode_html['id'], $shortcode_html['slug'] );
		$this->assertEquals( $shortcode_html['id'], $shortcode_html['name'] );
		$this->assertEquals( $shortcode_html['slug'], $shortcode_html['name'] );

	}


}
