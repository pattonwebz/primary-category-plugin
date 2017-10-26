<?php
 /**
  * metaboxes.php
  *
  * Adds and modifies metaboxes on the post editor to enable tagging of specific
  * categories as 'primary' category for that post.
  *
  * Exposes 1 staticly accessible function: get_primary_category( $post_id );
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
		// on post save we want to update some term meta
		add_action( 'save_post', array( $this, 'save_term_metadata' ) );
	}

	public function output_primary_category_admin_script( $hook ) {
		// get some variables we'll use in the script.#
		global $post;

		// if on post-new or post pages then enqueue our scripts.
		if ( 'post-new.php' === $hook || 'post.php' === $hook ) {
			wp_enqueue_script( 'pwwp-pc-functions', PRIMARY_CATEGORY_PLUGIN_URL . 'js/primary-category-functions.js', array( 'jquery' ) );
			$post_id = $post->ID;
			$current_primary_category = self::get_primary_category( $post_id );
			$current_primary_category_id = get_post_meta( $post_id, '_pwwp_pc_selected_id', true );
			// wrap with single quotes.
			// inline a script containing some data we'll want easy access to in edit screens.
			wp_add_inline_script( 'pwwp-pc-functions', '
//<![CDATA[
	var pwwp_pc_data;
	pwwp_pc_data = {
		post_ID: ' . $post_id . ',
		primary_category: "' . esc_js( $current_primary_category ) . '",
		primary_category_id: "' . $current_primary_category_id . '",
	};
//]]>' );
		}

	}

	public static function get_primary_category( $id = 0, $echo = false ) {
		// default return value will be false to indicate failure.
		$value = false;
		if ( (int) $id > 0 ) {
			// since we have a non zero id check if there is a metadata item to use.
			$value = get_post_meta( $id, '_pwwp_pc_selected', true );
		}
		if ( false !== $echo ) {
			echo $value;
		} else {
			return $value;
		}
	}

	public function save_primary_category_metadata() {
		// TODO: sanitize!!!
		$post_id = (int) $_POST['ID'];
		$term_nicename = $_POST['category'];

		$old_term_id = $_POST['old_term_id'];

		// if we have an old term id remove this post from the term meta.
		if ( $old_term_id ) {
			$old_meta = get_term_meta( $old_term_id, '_pwwp_pc_selected_id' );

			// find the key of any match for this $post_id.
			if ( ( $key = array_search( $post_id, $old_meta ) ) !== false ) {
				// if we got a match unset it from the array.
				unset( $old_meta[$key] );
				// update old terms metadata to remove this post id.
				$r_old = update_term_meta( $old_term_id, '_pwwp_pc_selected_id', $old_meta );
			}
		}

		$term = get_term_by( 'name', $term_nicename, 'category' );
		// if $term is not an error...
		if ( ! is_wp_error( $term ) ) {
			$term_id = $term->term_id;
			$term_slug = $term->slug;
			// update the post meta with these items.
			$results['id'] = update_post_meta( $post_id, '_pwwp_pc_selected_id', $term_id );
			$results['slug'] = update_post_meta( $post_id, '_pwwp_pc_selected_slug', $term_slug );
			$results['nicename'] = update_post_meta( $post_id, '_pwwp_pc_selected', $term_nicename );
		}

		// loop through the results to generate a response.
		foreach ( $results as $result ) {
			if ( $result ) {
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
		}

		// wp_die() triggers the return of the response.
		wp_die();

	}

	public function save_term_metadata( $post_id ) {
		$term_id = get_post_meta( $post_id, '_pwwp_pc_selected_id', true );
		if ( $term_id ) {
			$term_meta_value = get_term_meta( $term_id, '_pwwp_pc_selected_id', true );
			if ( $term_meta_value ) {
				$term_meta_value[] = (int) $post_id;
			} else {
				$term_meta_value = array(
					(int) $post_id,
				);
			}
			$r = update_term_meta( $term_id, '_pwwp_pc_selected_id', $term_meta_value );
		}
	}
}

$pwwp_pc_metabox = new pwwp_primary_category_metabox_modifications;
