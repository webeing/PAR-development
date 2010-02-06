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
add_action( 'template_redirect', 'bp_group_documents_add_js', 1 );

//call the css
function bp_group_documents_add_css() {
	wp_enqueue_style('bp-group-documents',WP_PLUGIN_URL . '/buddypress-group-documents/css/style.css');

	switch( BP_GROUP_DOCUMENTS_THEME_VERSION ){
		case '1.1':
			wp_enqueue_style('bp-group-documents-1.1', WP_PLUGIN_URL . '/buddypress-group-documents/css/11.css');
		break;
		case '1.2':
		//	wp_enqueue_style('bp-group-documents-1.2', WP_PLUGIN_URL . '/buddypress-group-documents/css/12.css');
		break;
	}
}
add_action( 'template_redirect', 'bp_group_documents_add_css', 1 );
		
?>
