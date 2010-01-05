<?php 

// Show sidebar on left
function woo_sidebar_left() {
	if ( get_option('woo_left_sidebar') == "true" ) 
		echo '<style type="text/css">#main.col-left { float:right; } #main.col-right { float:left; }</style>' . "\n";
} 
add_action('wp_head','woo_sidebar_left');

// Use Cufon font replacement
function woo_cufon() {
	if ( get_option('woo_cufon') == "true" ) {
		echo '<script type="text/javascript">Cufon.replace("h1, h2, h3, h4, h5, h6, .cufon");</script>' . "\n";
		echo '<style type="text/css">.widget h3 { line-height:22px; }</style>' . "\n";
    }
} 
add_action('wp_head','woo_cufon');

// Show Category descriptions in menu
function woo_menu_description() {
	if ( get_option('woo_menu_desc') == "true" ) { 
		$GLOBALS[desc] = "true"; 
		echo '<style type="text/css">#secnav a { line-height:16px; padding:13px 15px; }</style>' . "\n";
	}
}
add_action('wp_head','woo_menu_description');


// SET GLOBAL WOO VARIABLES
function woo_globals() {
	
	// Featured dimensions
	$GLOBALS['align_feat'] = 'alignleft'; if (get_option('woo_align_feat')) $GLOBALS['align_feat'] = get_option('woo_align_feat'); 			
	$GLOBALS['thumb_width_feat'] = '200'; if (get_option('woo_thumb_width_feat')) $GLOBALS['thumb_width_feat'] = get_option('woo_thumb_width_feat'); 		
	$GLOBALS['thumb_height_feat'] = '200'; if (get_option('woo_thumb_height_feat')) $GLOBALS['thumb_height_feat'] = get_option('woo_thumb_height_feat'); 

	// Thumbnail dimensions
	$GLOBALS['align'] = 'alignleft'; if (get_option('woo_align')) $GLOBALS['align'] = get_option('woo_align'); 			
	$GLOBALS['thumb_width'] = '200'; if (get_option('woo_thumb_width')) $GLOBALS['thumb_width'] = get_option('woo_thumb_width'); 		
	$GLOBALS['thumb_height'] = '200'; if (get_option('woo_thumb_height')) $GLOBALS['thumb_height'] = get_option('woo_thumb_height'); 

	// Featured Tags
	$GLOBALS['feat_tags_array'] = array();

	// Duplicate posts 
	$GLOBALS['shownposts'] = array();

	// Video Category
	global $wpdb;
	$video_cat = get_option('woo_video_category'); 
	$GLOBALS[video_id] = $wpdb->get_var("SELECT term_id FROM $wpdb->terms WHERE name = '$video_cat'");
}

// Remove image dimentions from woo_get_image images
update_option('woo_force_all',false);
update_option('woo_force_single',false);


// SHOW SOCIAL BOOKMARKS
function woo_social() {
?>
<a href="http://twitter.com/home/?status=<?php the_title(); ?> : <?php echo get_tiny_url(get_permalink($post->ID)); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/ico-social-twitter.png" alt="Twitter" /></a>
<a href="http://digg.com/submit?phase=2&amp;url=<?php the_permalink() ?>"><img src="<?php bloginfo('template_directory'); ?>/images/ico-social-digg.png" alt="Digg" /></a>                            
<a href="http://del.icio.us/post?url=<?php the_permalink() ?>&amp;title=<?php the_title(); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/ico-social-delicious.png" alt="Delicious" /></a>                            
<a href="http://www.stumbleupon.com/submit?url=<?php the_permalink() ?>&amp;title=<?php echo urlencode(the_title('','', false)) ?>"><img src="<?php bloginfo('template_directory'); ?>/images/ico-social-stumbleupon.png" alt="Stumbleupon" /></a>                            
<a href="http://technorati.com/cosmos/search.html?url=<?php the_permalink() ?>"><img src="<?php bloginfo('template_directory'); ?>/images/ico-social-technorati.png" alt="Technorati" /></a>                            
<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php the_title(); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/ico-social-facebook.png" alt="Facebook" /></a>                            
<?php 
}

// Shorten URL for Twitter
function get_tiny_url($url) {
 
 if (function_exists('curl_init')) {
   $url = 'http://tinyurl.com/api-create.php?url=' . $url;
 
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_HEADER, 0);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_URL, $url);
   $tinyurl = curl_exec($ch);
   curl_close($ch);
 
   return $tinyurl;
 }
 
 else {
   # cURL disabled on server; Can't shorten URL
   # Return long URL instead.
   return $url;
 }
 
}

// Shorten Excerpt text for use in theme
function woo_excerpt($text, $chars = 120) {
	$text = $text." ";
	$text = substr($text,0,$chars);
	$text = substr($text,0,strrpos($text,' '));
	$text = $text."...";
	return $text;
}



?>