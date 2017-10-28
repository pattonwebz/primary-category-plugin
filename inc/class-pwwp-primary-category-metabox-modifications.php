<?php
 /**
  * The class-pwwp-primary-category-metabox-modifications.php file.
  *
  * Adds and modifies metaboxes on the post editor to enable tagging of specific
  * categories as 'primary' category for that post.
  *
  * Exposes 1 staticly accessible function: get_primary_category( $post_id );
  *
  * @package  Primary Category Plugin
  */

/* Check if Class Exists. */
if ( ! class_exists( 'PWWP_Primary_Category_Metabox_Modifications' ) ) {

	/**
	 * PWWP_Primary_Category_Metabox_Modifications class.
	 *
	 * @author: William Patton
	 * @since 1.0.0
	 */
	class PWWP_Primary_Category_Metabox_Modifications {

		/**
		 * Constructor function to add actions necessary for adding the primary
		 * category functions to the editor screen.
		 */
		public function __construct() {
			// Output the script in the post edit and new post pages or admin.
			add_action( 'admin_enqueue_scripts', array( $this, 'output_primary_category_admin_script' ), 10 );
			// AJAX request to update post_meta based on selection of Primary Category.
			add_action( 'wp_ajax_pwwp_pc_save_primary_category', array( $this, 'save_primary_category_metadata' ), 10 );
			// on post save we want to update some term meta depending on categories selected at savetime.
			add_action( 'save_post', array( $this, 'on_save_term_metadata_check' ), 10, 3 );
		}

		/**
		 * Output edit screen admin script and localized data.
		 *
		 * @param  string $hook text name for the hook currently running.
		 */
		public function output_primary_category_admin_script( $hook ) {
			// get some variables we'll use in the script.
			global $post;

			// if on 'post-new' or 'post' pages then enqueue our scripts.
			if ( 'post-new.php' === $hook || 'post.php' === $hook ) {
				// add the main script to edit page.
				wp_enqueue_script( 'pwwp-pc-functions', PRIMARY_CATEGORY_PLUGIN_URL . 'js/primary-category-functions.js', array( 'jquery' ) );

				$post_id = $post->ID;
				// get the current primary category.
				$current_primary_category = self::get_primary_category( $post_id );
				// get the id of the current primary category.
				$current_primary_category_id = get_post_meta( $post_id, '_pwwp_pc_selected_id', true );

				wp_localize_script( 'pwwp-pc-functions', 'pwwp_pc_data', [
					'ajax_url'            => admin_url( 'admin-ajax.php' ),
					'nonce'               => wp_create_nonce( 'pwwp-pc-functions' ),
					'post_id'             => $post_id,
					'primary_category'    => esc_js( $current_primary_category ),
					'primary_category_id' => (int) $current_primary_category_id,
				]);

			}

		}

		/**
		 * Get the current primary category for whatever post ID is passed.
		 *
		 * @param  integer $id   id of post to get primary category of.
		 * @param  boolean $echo either echo or not.
		 * @return string        a string containing a category nicename.
		 */
		public static function get_primary_category( $id = 0, $echo = false ) {
			// default return value will be false to indicate failure.
			$value = false;
			if ( (int) $id > 0 ) {
				// since we have a non zero id check if there is a metadata item to use.
				$value = get_post_meta( $id, '_pwwp_pc_selected', true );
			}
			if ( false !== $echo ) {
				echo esc_html( $value );
			} else {
				return $value;
			}
		}

		/**
		 * This is used to save some post meta, fired on AJAX request.
		 */
		public function save_primary_category_metadata() {
			// first check nonce to ensure this is a post that we expect.
			if ( ! check_ajax_referer( 'pwwp-pc-functions', 'nonce' ) ) {
				wp_send_json_error( 'Invalid security token sent.' );
				wp_die();
			}
			// TODO: sanitize!!!
			$post_id = intval( $_POST['ID'] );
			$term_nicename = sanitize_text_field( wp_unslash( $_POST['category'] ) );

			/**
			 * Before any setting of items, lets do some unsetting of term meta.
			 */
			// get any already set value for primary category id on this post.
			$old_term_id = get_post_meta( $post_id, '_pwwp_pc_selected_id', true );

			self::reset_old_meta( $post_id, $old_term_id );

			$results = self::update_new_meta( $post_id, $term_nicename );

			// printing out the results isn't the best...
			if ( $results ) {
				// loop through the results to generate a response.
				wp_send_json_success( $results );
				wp_die();
			}

			// shoould already be dead before here, if not...
			wp_die();

		}

		/**
		 * This is used to save some term meta, fired on 'save_post' hook.
		 *
		 * @param  integer $post_id a number of a post id.
		 * @param  object  $post    a WP_Post object contianing the updated content.
		 * @param  boolean $update  true if update, false if new post.
		 */
		public function on_save_term_metadata_check( $post_id, $post, $update ) {

			$terms = wp_get_post_terms( $post_id, 'category' );
			// if we have a terms array and it's not an error.
			if ( is_array( $terms ) && ! is_wp_error( $terms ) ) {
				// get the term id for any primary category set to the post.
				$primary_category_id = get_post_meta( $post_id, '_pwwp_pc_selected_id', true );
				if ( $primary_category_id ) {

					$found_in = array();
					foreach ( $terms as $term ) {
						$meta = get_term_meta( $term->term_id, '_pwwp_pc_selected_id', true );
						if ( $meta ) {
							// check are we supposed to be in this array?
							$add_to_array = false;
							if ( (int) $primary_category_id === (int) $term->term_id ) {
								// we are supposed to be in the array.
								$add_to_array = true;
							}

							// find out if we're in the list of ids.
							$key = array_search( $post_id, $meta );
							if ( false === $key ) {
								// we're not in the array.
								if ( $add_to_array ) {
									$meta[] = $post_id;
									update_term_meta( $term->term_id, '_pwwp_pc_selected_id', $meta );
									$found_in[] = $term->$term_id;
								}
							} else {
								// we are in the array, are we supposed to be?
								if ( ! $add_to_array ) {
									// not supposed to be in this array, unset.
									unset( $meta[ $key ] );
								} else {
									$found_in[] = $term->term_id;
								}
							}
						} else {

						}
					}
					// if we have an array of term ids we are found in...
					if ( ! empty( $found_in ) ) {
						// make sure we are in the term tagged as primary.
						$key = array_search( $primary_category_id, $found_in );
						if ( false === $key ) {
							// We were not found in the array when we should be...
							$meta = get_term_meta( $primary_category_id, '_pwwp_pc_selected_id', true );
							$meta[] = $post_id;
							update_term_meta( $primary_category_id, '_pwwp_pc_selected_id', $meta );
						} elseif ( is_array( $key ) ) {
							// we got an array.. we are in more than 1 term when we shouldn't be.
						}
					} else {
						// we weren't found in any of our terms... add us.
						$nope = false;
						foreach ( $terms as $term ) {
							if ( $term->term_id === $primary_category_id ) {
								// we are tagged with this term but not in the term meta, add us.
								$meta = get_term_meta( $term->term_id, '_pwwp_selected_id', true );
								$meta[] = $post_id;
								update_term_meta( $term->$term_id, '_pwwp_selected_id', $meta );
								$nope = true;
							}
						}
						// since we got this far and $nope wasn't updated then
						// we were not able to figure out what category we
						// should be in... remove the PC from the post meta :(.
						if ( ! $nope ) {
							delete_post_meta( $post_id, '_pwwp_selected' );
							delete_post_meta( $post_id, '_pwwp_selected_id' );
							delete_post_meta( $post_id, '_pwwp_selected_slug' );
						}
					}
				}// End if().
			}// End if().

		}

		/**
		 * Rests any old metadata tied to a specific term.
		 *
		 * @param integer $post_id     id of a post.
		 * @param integer $old_term_id id of a term.
		 */
		private static function reset_old_meta( $post_id = 0, $old_term_id = 0 ) {

			// if we have an old term id remove this post from the term meta.
			if ( $old_term_id && $post_id ) {
				// get the meta for our key, false = we want the array.
				$old_meta = get_term_meta( $old_term_id, '_pwwp_pc_selected_id', true );
				if ( $old_meta && count( $old_meta ) > 0 ) {
					if ( is_array( $old_meta ) ) {
						// find the key of any match for this $post_id.
						$key = array_search( $post_id, $old_meta );
						if ( false !== $key ) {

							if ( count( $old_meta ) > 1 ) {
								// if we got a match unset it from the array.
								unset( $old_meta[ $key ] );
								// update old terms metadata to remove this post id.
								$r_old = update_term_meta( $old_term_id, '_pwwp_pc_selected_id', $old_meta );
							} else {
								$r_old = delete_term_meta( $old_term_id, '_pwwp_pc_selected_id' );
							}
						} else {
							if ( $post_id === $old_meta ) {
								// delete the entire term meta.
								$r_old = delete_term_meta( $old_term_id, '_pwwp_pc_selected_id' );
							}
						}
					}
				}
			}

		}

		/**
		 * Updates the new term with this posts id.
		 *
		 * @param  integer $post_id       id of a post.
		 * @param  string  $term_nicename nicename of a term.
		 */
		private static function update_new_meta( $post_id = 0, $term_nicename = '' ) {

			$results = false;
			if ( $term_nicename && $post_id ) {

				/**
				 * Now update the meta values for the key.
				 */
				$term = get_term_by( 'name', $term_nicename, 'category' );
				// if $term is object not an error...
				if ( is_object( $term ) && ! is_wp_error( $term ) ) {
					$term_id = $term->term_id;
					$term_slug = $term->slug;
					// update the post meta with these items.
					$results['id'] = update_post_meta( $post_id, '_pwwp_pc_selected_id', $term_id );
					$results['slug'] = update_post_meta( $post_id, '_pwwp_pc_selected_slug', $term_slug );
					$results['nicename'] = update_post_meta( $post_id, '_pwwp_pc_selected', $term_nicename );
					// set this value early as a default incase it fails.
					$results['term_meta'] = false;
					$new_term_meta = get_term_meta( $term_id, '_pwwp_pc_selected_id', true );
					if ( is_array( $new_term_meta ) ) {
						// since this is an array check if this post id is already in it.
						$key = array_search( $post_id, $new_term_meta );
						if ( false === $key ) {
							// we're not in the array, add us and update.
							$new_term_meta[] = $post_id;
							$results['term_meta'] = update_term_meta( $term_id, '_pwwp_pc_selected_id', $new_term_meta );
						}
					} else {
						// isn't an array, it should be (or be empty).
						$update_term_meta = array( $post_id );
						delete_term_meta( $term_id, '_pwwp_pc_selected_id' );
						$results['term_meta'] = update_term_meta( $term_id, '_pwwp_pc_selected_id', $update_term_meta );
					}
				}
			} // End if().

			return $results;

		}

	}

} // End if().

// instantiate the class.
$pwwp_pc_metabox = new PWWP_Primary_Category_Metabox_Modifications;
