<?php
 /**
  * actions-and-filters.php
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
