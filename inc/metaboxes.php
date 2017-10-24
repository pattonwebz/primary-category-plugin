<?php
 /**
  * metaboxes.php
  *
  * Adds and modifies metaboxes on the post editor to enable tagging of specific
  * categories as 'primary' category for that post.
  *
  * @package  Primary Category Plugin
  */

class pwwp_primary_category_metabox_modifications {

	// constructor function to add actions necessary for adding the primary
	// category functions to the editor screen.
	public function __construct() {
		// Output the script in the post edit and new post pages or admin.
		add_action( 'admin_enqueue_scripts', array( $this, 'output_primary_category_admin_script' ), 10 );
	}

	public function output_primary_category_admin_script( $hook ) {
		// get some variables we'll use in the script.#
		global $post;

		// if on post-new or post pages then enqueue our scripts.
		if ( 'post-new.php' === $hook || 'post.php' === $hook ) {
			wp_enqueue_script( 'pwwp-pc-functions', PRIMARY_CATEGORY_PLUGIN_URL . 'js/primary-category-functions.js', array( 'jquery' ) );
			$post_id = $post->ID;
			wp_add_inline_script( 'pwwp-pc-functions', '
//<![CDATA[
	var pwwp_pc_data;
	pwwp_pc_data = { post_ID: ' . $post_id . ' };
//]]>' );
		}

	}
}

$pwwp_pc_metabox = new pwwp_primary_category_metabox_modifications;
