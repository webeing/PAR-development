<?php
if (!is_admin()) add_action( 'wp_print_scripts', 'woothemes_add_javascript' );
function woothemes_add_javascript( ) {
	wp_enqueue_script('jquery');    
	wp_enqueue_script( 'superfish', get_bloginfo('template_directory').'/includes/js/superfish.js', array( 'jquery' ) );
	if ( get_option('woo_tabs') == "true" ) 
		wp_enqueue_script( 'woo_tabs', get_bloginfo('template_directory').'/includes/js/woo_tabs.js', array( 'jquery' ) );
	if ( get_option('woo_cufon') == "true" ) {
		wp_enqueue_script( 'cufon', get_bloginfo('template_directory').'/includes/js/cufon-yui.js', array( 'jquery' ) );
		wp_enqueue_script( 'liberation', get_bloginfo('template_directory').'/includes/js/Liberation.font.js', array( 'jquery' ) );
	}
	if ( get_option('woo_featured') == "true" ) 
		wp_enqueue_script( 'loopedSlider', get_bloginfo('template_directory').'/includes/js/loopedSlider.js', array( 'jquery' ) );
}
?>