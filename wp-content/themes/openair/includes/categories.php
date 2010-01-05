<?php

function get_inc_categories($label) {
	
	$include = '';
	$counter = 0;
	$cats = get_categories('hide_empty=0');
	
	foreach ($cats as $cat) {
		
		$counter++;
		
		if ( get_option( $label.$cat->cat_ID ) == 'true' ) {
			if ( $counter <> 1 ) { $include .= ','; }
			$include .= $cat->cat_ID;
			}
	
	}
	
	return $include;

}

?>