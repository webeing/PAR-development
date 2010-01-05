<?php


/**
 * In all your template tags that output data, you should have an apply_filters() call, you can
 * then use those filters to automatically add the wp_filter_kses() call.
 * The third parameter "1" adds the highest priority to the filter call.
 */
 
 add_filter( 'bp_group_documents_before_display', 'wp_filter_kses', 1 );

/**
 * Used in the save() method in 'bp-group-documents-classes.php'
 */

 add_filter( 'bp_group_documents_before_save', 'wp_filter_kses', 1 );
 

?>
