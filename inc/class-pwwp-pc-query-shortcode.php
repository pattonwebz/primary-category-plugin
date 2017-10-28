<?php
 /**
  * The class-pwwp-pc-query-shorcode.php file
  *
  * Adds a shortcode that can be used to grab the list of posts from a Category
  * where the post has it tagged as the 'Primary Category'.
  *
  * @package  Primary Category Plugin
  */

if ( ! class_exists( 'PWWP_PC_Query_Shortcode' ) ) {

	/**
	 * PWWP_PC_Query_Shortcode
	 */
	class PWWP_PC_Query_Shortcode {

		/**
		 * Constructor function for the class where we register a shortcode.
		 */
		public function __construct() {
			add_shortcode( 'primary_category_query', array( $this, 'primary_category_query_shortcode' ) );
		}

		/**
		 * Function for the shortcode to perform a query for specific posts and
		 * output a list of post titles.
		 *
		 * @param  array $atts attributes set in the shorcode.
		 */
		public function primary_category_query_shortcode( $atts ) {
			$atts = shortcode_atts( array(
				'id' => '',
				'slug' => '',
				'name' => '',
				'post_type' => 'post',
				'limit' => '10',
			), $atts, 'primary_category_query' );
			// if we have neither an ID, a slug or a name then we should return nothing...
			if ( '' === $atts['id'] && '' === $atts['slug'] && '' === $atts['name'] ) {
				// return noting... except an inline html comment.
				return '<!-- no id, slug or name passed to shortcode -->';
			} else {
				// start empty var as a default return.
				$html = '';
				// here we have either an id, a slug or a name.
				if ( '' !== $atts['id'] ) {
					$tax_query_field = array(
						'meta_key'   => '_pwwp_pc_selected_id',
						'meta_value' => $atts['id'],
					);
				} elseif ( '' !== $atts['slug'] ) {
					$tax_query_field = array(
						'meta_key'   => '_pwwp_pc_selected_slug',
						'meta_value' => $atts['slug'],
					);
				} elseif ( '' !== $atts['name'] ) {
					$tax_query_field = array(
						'meta_key'   => '_pwwp_pc_selected',
						'meta_value' => $atts['name'],
					);
				}

				// WP_Query arguments.
				$sticky_posts = get_option( 'sticky_posts' );

				$args = array(
					'post_type'      => array( $atts['post_type'] ),
					'post_status'    => array( 'published' ),
					'nopaging'       => true,
					'posts_per_page' => $atts['limit'],
					'post__not_in'   => $sticky_posts,
				);

				// merge the $args array with the generated meta_key and meta_value args.
				$args = array_merge( $args, $tax_query_field );

				// the Query.
				$query = new WP_Query( $args );

				// start the loop.
				if ( $query->have_posts() ) {
					ob_start();
					echo '<ul class="pwwp-pc-query-wrapper">';
					while ( $query->have_posts() ) {
						$query->the_post();
						echo '<li><a href="' . esc_url( get_the_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></li>';
					}
					echo '</ul>';
					$html = ob_get_clean();

					// restore original Post Data.
					wp_reset_postdata();
				} else {
					return '<p class="pwwp-pc-none">' . esc_html__( 'No Posts to display in primary category.', 'pwwp_pc' ) . '</p>';
				}
			}// End if().

			// return the html.
			return $html;
		}
	}

} // End if().

// instantiate the class.
$shortcode = new PWWP_PC_Query_Shortcode;
