<?php

	$style = $_REQUEST[style];
	if ($style != '') {
		$style_path = $style;
	} else {

		global $style_path;
		
		$stylesheet = get_option('woo_alt_stylesheet');
		
		if ( $stylesheet == 'default.css' ) { $style_path = 'default'; }
		elseif ( $stylesheet == 'grayscale.css' ) { $style_path = 'grayscale'; }
		elseif ( $stylesheet == 'green.css' ) { $style_path = 'green'; }
		elseif ( $stylesheet == 'red.css' ) { $style_path = 'red'; }
		elseif ( $stylesheet == 'fresh.css' ) { $style_path = 'fresh'; }
		else { $style_path = 'default'; }
	
	}
?>

