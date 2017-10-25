
/**
 * Function used to bootstrap and initate the primary category system.
 */
function pwwp_pc_bootstrap() {
	pwwp_pc_check_first_loaded();
	var taxArray;
	taxArray = pwwp_pc_get_taxonomies_on_page();
	if( taxArray.prototype.toString.call( someVar ) === '[object Array]' && taxArray.length > 0 ) {
		taxArray.forEach( function( element ){
    		console.log( element );
			pwwp_pc_init_box_for_taxonomy( element );
		});
	}
}

function pwwp_pc_initiate(){

}

/**
 * Check for all the functions that are present on the page and return them
 * in an array.
 * @return array array containing IDs matching the taxonomies on page.
 */
function pwwp_pc_get_taxonomies_on_page() {
	jQuery();
}

function pwwp_pc_init_box_for_taxonomy( taxonomyContainerID = false ) {
	if ( taxonomyContainerID ) {
		jQuery( taxonomyContainerID );
	}
}

function pwwp_pc_bind_on_checkbox_change() {
	jQuery("#categorychecklist").contents().find(":checkbox").bind('change', function(){
        is_checked = this.checked;
		if( true === is_checked ){
			jQuery( this ).parent().parent().addClass( 'pwwp-pc-checked' );
			pwwp_pc_toggle_button_in_label( this );

		} else {
			jQuery( this ).parent().parent().removeClass( 'pwwp-pc-checked' );
			pwwp_pc_toggle_button_in_label( this, false );
		}
	});
}

function pwwp_pc_toggle_button_in_label( element = false, toAdd = true ) {
	if( false !== element ){
		if( true === toAdd ) {
			jQuery( element ).parent().after( '<label><input type="button" class="pwwp-pc-primary button button-primary" value="Make Primary"><span class="screen-reader-text">Make Primary</span></label>' );
			// ensure a click event is bound to these buttons.
			pwwp_pc_bind_on_button_make_primary();
		} else {
			var container;
			container = jQuery( element ).parent().parent();
			var item = jQuery( jQuery( container ).find( '.pwwp-pc-primary' ) );
			jQuery( jQuery(item).first() ).remove();
		}
	}
}

function pwwp_make_ajax_request( cat = '' ) {
	if ( '' === cat ) {
		// there was no category passed, return.
		return;
	}
		var data = {
			'action': 'pwwp_pc_save_primary_category',
			'ID': pwwp_pc_data.post_ID,
			'category': cat
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			alert('Got this from the server: ' + response);
		});


}
function pwwp_pc_bind_on_button_make_primary() {
	jQuery(".pwwp-pc-primary").on('click', pwwp_pc_button_click_handler );
}

function pwwp_pc_reset_all_buttons() {
	jQuery(".pwwp-pc-primary").each( function( index ) {
		console.log( 'reset' + index );
		jQuery( this ).removeClass( 'pwwp-pc-cat')
		jQuery( this ).parent().parent().removeClass( 'pwwp-pc-checked' );
		jQuery( this ).parent().find( '.screen-reader-text' ).val( 'Make Primary' );
		jQuery( this ).prop( 'disabled', false );
		jQuery( this ).val('Make Primary');
	});
}

function pwwp_pc_button_click_handler( event ) {
	event.preventDefault();
	pwwp_pc_reset_all_buttons();
	jQuery( this ).parent().addClass( 'pwwp-pc-cat')
	if( jQuery( this).parent().hasClass( 'pwwp-pc-cat' ) ) {
		jQuery( this ).parent().find( '.screen-reader-text' ).val( 'This Posts Primary Category' );
		jQuery( this ).prop( 'disabled', true );
		jQuery( this ).val('Selected');
		var cat = jQuery( this ).parent().parent().text();
		// TODO: we can't rely on a string replace because 'Make Primary' might eb translated
		cat = cat.replace('Make Primary', '');
		console.log( 'will make request with this category: ' + cat )
		pwwp_make_ajax_request( cat );
	} else {
		jQuery( this ).parent().find( '.screen-reader-text' ).val( 'Make Primary' );
		jQuery( this ).prop( 'disabled', false );
		jQuery( this ).val('Make Primary');
	}


}

jQuery( document ).ready( function() {
	pwwp_pc_initiate();
	pwwp_pc_bind_on_checkbox_change();
	pwwp_pc_bind_on_button_make_primary();
});
