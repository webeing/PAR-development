<?php

// Register widgetized areas

function the_widgets_init() {
    if ( !function_exists('register_sidebars') )
        return;

   		register_sidebar(array('name' => 'right','before_widget' => '<li id="%1$s" class="widget %2$s">','after_widget' => '</li>','before_title' => '<h5 class="widgettitle">','after_title' => '</h5>',));
       	register_sidebar(array('name' => 'footer','before_widget' => '<li id="%1$s" class="widget %2$s">','after_widget' => '</li>','before_title' => '<h5 class="widgettitle">','after_title' => '</h5>',));
       	register_sidebar(array('name' => 'bottom','before_widget' => '<li id="%1$s" class="widget %2$s">','after_widget' => '</li>','before_title' => '<h5 class="widgettitle">','after_title' => '</h5>',));
       	
}

add_action( 'init', 'the_widgets_init' );


    
?>