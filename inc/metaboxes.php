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
		// AJAX request to update post_meta based on selection of Primary Category.
		add_action( 'wp_ajax_pwwp_pc_save_primary_category', array( $this, 'save_primary_category_metadata' ), 10 );
	}

	public function output_primary_category_admin_script( $hook ) {
		// get some variables we'll use in the script.#
		global $post;

		// if on post-new or post pages then enqueue our scripts.
		if ( 'post-new.php' === $hook || 'post.php' === $hook ) {
			wp_enqueue_script( 'pwwp-pc-functions', PRIMARY_CATEGORY_PLUGIN_URL . 'js/primary-category-functions.js', array( 'jquery' ) );
			$post_id = $post->ID;
			$current_primary_category = self::get_primary_category( $post_id );
			// wrap with single quotes.
			// when it's empty set it to just empty string (2 single quotes).
			$current_primary_category = "'" . esc_js( $current_primary_category ) . "'";
			// inline a script containing some data we'll want easy access to in edit screens.
			wp_add_inline_script( 'pwwp-pc-functions', '
//<![CDATA[
	var pwwp_pc_data;
	pwwp_pc_data = {
		post_ID: ' . $post_id . ',
		primary_category: ' . $current_primary_category . '
	};
//]]>' );
		}

	}

	public static function get_primary_category( $id = 0, $echo = false ) {
		// default return value will be false to indicate failure.
		$value = false;
		if ( (int)$id > 0 ) {
			// since we have a non zero id check if there is a metadata item to use.
			$value = get_post_meta( $id, 'pwwp_pc_selected', true );
			error_log( print_r( $value, true ), 0 );
		}
		if( false !== $echo ){
			echo $value;
		} else {
			return $value;
		}
	}

	public function save_primary_category_metadata() {
			// TODO: sanitize!!!
			//$whatever = $_POST['pwwp_pc_primary_category'];
			//$whatever = $_POST['action'];
			$id = $_POST['ID'];
			$whatever = $_POST['category'];
			$result = update_post_meta( $id, 'pwwp_pc_selected', $whatever );
			if ( $result ){
				// this is a successful update.
				if ( true === $result ) {
					// this was an update.
					echo 'value updated.';
				} else {
					// this was a new key.
					echo 'new key: ' . $result;
				}
			} else {
				echo 'fail';
			}

		wp_die();

	}
}

$pwwp_pc_metabox = new pwwp_primary_category_metabox_modifications;
