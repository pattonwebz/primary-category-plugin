<?php
class pwwp_pc_query_shortcode {
	public function __construct() {
		add_shortcode( 'primary_category_query', array( $this, 'primary_category_query_shortcode' ) );
	}

	public function primary_category_query_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'id' => '',
			'slug' => '',
			'name' => '',
			'post_type'	=> 'post',
			'limit' => '10',
		), $atts, 'primary_category_query' );
		// if we have neither an ID, a slug or a name then we should return nothing...
		if( '' === $atts['id'] && '' === $atts['slug'] && '' === $atts['name'] ){
			// return noting... except an inline html comment
			return '<!-- no id, slug or name passed to shortcode -->';
		} else {
			// here we have either an id, a slug or a name.
			if ( '' !== $atts['id'] ) {
				$tax_query_field = array(
					'meta_key'		=> '_pwwp_pc_selected_id',
					'meta_value'	=> $atts['id'],
				);
			} elseif ( '' !== $atts['slug'] ) {
				$tax_query_field = array(
					'meta_key'		=> '_pwwp_pc_selected_slug',
					'meta_value'	=> $atts['slug'],
				);
			} elseif ( '' !== $atts['name'] ) {
				$tax_query_field = array(
					'meta_key'		=> '_pwwp_pc_selected',
					'meta_value'	=> $atts['name'],
				);
			}

			// WP_Query arguments
			$sticky_posts = get_option( 'sticky_posts' );

			$args = array(
				'post_type'			=> array( $atts['post_type'] ),
				'post_status'		=> array( 'published' ),
				'nopaging'			=> true,
				'posts_per_page'	=> $atts['limit'],
				'post__not_in'		=> $sticky_posts,
			);
			$args = array_merge( $args, $tax_query_field );
			error_log( print_r( $args, true ), 0 );
			// The Query
			$query = new WP_Query( $args );
			error_log( print_r( $query, true ), 0 );

			// start the loop
			if ( $query->have_posts() ) {
				echo '<ul>';
				while ( $query->have_posts() ) {
					$query->the_post();
					echo '<li><a href="' . esc_url( get_the_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></li>';
				}
				echo '</ul>';
				/* Restore original Post Data */
				wp_reset_postdata();
			} else {
				// no posts found
			}
		}

	}
}

$shortcode = new pwwp_pc_query_shortcode;
