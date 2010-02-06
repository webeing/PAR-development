<?php

/**
 * bp_group_documents_add_js()
 *
 * This function will enqueue the components javascript file
 */
function bp_group_documents_add_js() {
	global $bp;

	if ( $bp->current_component == $bp->group_documents->slug )
		wp_enqueue_script( 'bp-group-documents-js', plugins_url( '/buddypress-group-documents/js/general.js' ),array('jquery') );
}
bp_group_documents_add_js();

//call the css
wp_enqueue_style('bp-group-documents-css',WP_PLUGIN_URL . '/buddypress-group-documents/css/style.css');

?>
