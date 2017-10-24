/**
 * Bind to the category checklist and do things on change.
 */
function pwwp_pc_bind_on_checkbox_change() {
	// when checkboxes in the list change...
	jQuery("#categorychecklist").contents().find(":checkbox").on('change', function(){
        is_checked = this.checked;
		// if we were just checked on add a class and button, else remove it.
		if( true === is_checked ){
			jQuery( this ).parent().parent().addClass( 'pwwp-pc-checked' );
			// adds button.
			pwwp_pc_toggle_button_in_label( this );
		} else {
			jQuery( this ).parent().parent().removeClass( 'pwwp-pc-checked' );
			// remove button.
			pwwp_pc_toggle_button_in_label( this, false );
		}
	});
}

function pwwp_pc_toggle_button_in_label( element = false, toAdd = true ) {
	// do we have an element passed?
	if( false !== element ){
		// decide if we are adding or removing items from element.
		if( true === toAdd ) {
			jQuery( element ).parent().after( '<label><input type="button" class="pwwp-pc-primary" value="Make Primary"><span class="screen-reader-text">Make Primary</span></label>' );
			// ensure a click event is properly bound to the newly added button.
			pwwp_pc_bind_on_button_make_primary();
		} else {
			var container;
			container = jQuery( element ).parent().parent();
			var item = jQuery( jQuery( container ).find( '.pwwp-pc-primary' ) );
			jQuery( jQuery(item).first() ).remove();
		}
	}
}

function pwwp_pc_bind_on_button_make_primary( unbind = false ) {
	if( false === 'unbind' ){
		jQuery(".pwwp-pc-primary").on('click', pwwp_pc_button_click_handler );
	} else {
		jQuery(".pwwp-pc-primary").off('click', pwwp_pc_button_click_handler );
	}
}

function pwwp_pc_button_click_handler( event ) {
	// prevent default button event.
	event.preventDefault();
	// toggle a class on button parent to use as a flag.
	jQuery( this ).parent().toggleClass( 'pwwp-pc-cat')
	if( jQuery( this).parent().hasClass( 'pwwp-pc-cat' ) ) {
		// parent is tagged with a flag .class, update values and properties.
		jQuery( this ).parent().find( '.screen-reader-text' ).val( 'This Posts Primary Category' );
		jQuery( this ).prop( 'disabled', true );
		jQuery( this ).val('Selected');
	} else {
		// parent doesn't have our flag .class so reset back to defaults.
		jQuery( this ).parent().find( '.screen-reader-text' ).val( 'Make Primary' );
		jQuery( this ).prop( 'disabled', false );
		jQuery( this ).val('Make Primary');
	}

}

/**
 * When the document is ready bootstrap the plugin features to the page.
 */
jQuery( document ).ready( function() {
	pwwp_pc_bind_on_checkbox_change();
	pwwp_pc_bind_on_button_make_primary();
});
