<?php
 /**
  * The actions-and-filters.php file
  *
  * Adds some actions for the plugin so that other plugins can hook in or a
  * theme could use them in their templates.
  *
  * @package  Primary Category Plugin
  */

/**
 * Filters args used in the primary category custom widget.
 *
 * @param  array $args array of args used when building category lists.
 * @return array updates args array with a meta_key value.
 */
function pwwp_pc_filter_widget_args( $args ) {
	// merge the existing args with ours containing a meta_key value.
	$args = array_merge( $args, array(
		'meta_key'	=> '_pwwp_pc_selected_id',
	) );
	// return modified args.
	return $args;
}
// filters the args in our custom widget so that it only grabs cats that
// contain posts it is tagged as primary category for.
add_filter( 'pwwp_widget_primary_categories_dropdown_args', 'pwwp_pc_filter_widget_args' );
add_filter( 'pwwp_widget_primary_categories_args', 'pwwp_pc_filter_widget_args' );

/**
 * Filters the main query if a pwwp_pc query var is set to true.
 *
 * @param  object $query WP_Query object.
 * @return void
 */
function pwwp_pc_filter_wp_query_object_on_categories( $query ) {

	// check if we have a 'pwwp_pc' query var set to true.
	$pwwp_pc_show = $query->get( 'pwwp_pc' );
	if ( $pwwp_pc_show ) {

		// only act on the main page query.
		if ( $query->is_main_query() ) {
			// get original meta query if there is one.
			$meta_query = $query->get( 'meta_query' );
			// add our meta query.
			$meta_query[] = array(
				'key' => '_pwwp_pc_selected_id',
			);
			// set the new meta query.
			$query->set( 'meta_query', $meta_query );
		}
	}

}
add_action( 'pre_get_posts', 'pwwp_pc_filter_wp_query_object_on_categories' );


/**
 * Add a pwwp_pc query var to the the query object.
 *
 * @param  array $vars Array of currently avialable query_vars.
 * @return array       Maybe updated array of query_vars.
 */
function custom_query_vars_filter( $vars ) {
	$vars[] = 'pwwp_pc';
	return $vars;
}
add_filter( 'query_vars', 'custom_query_vars_filter' );

/**
 * Filter the category list links to for our primary_category widget.
 *
 * @param  string $output String of html containing list items and maybe links.
 * @param  array  $args   Array of args passed to the parent function.
 * @return string         String of html with maybe updated links.
 */
function pwwp_pc_filter_category_list_links( $output, $args ) {

	// if $args has a 'meta_key' with the value we want.
	if ( array_key_exists( 'meta_key', $args ) && '_pwwp_pc_selected_id' === $args['meta_key'] ) {
		// pattern to find all links.
		$pattern = '/http:\/\/[^"]*?[^"]+/';
		// append our query var to the links.
		$results = preg_replace( $pattern, '$0?pwwp_pc=true', $output );
		// if we have update html then cast it to $output.
		if ( $results ) {
			$output = $results;
		}
		// return the maybe updated html.
		return $output;
	}
}
add_filter( 'wp_list_categories', 'pwwp_pc_filter_category_list_links', 10, 2 );
