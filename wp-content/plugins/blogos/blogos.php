<?php 
/*
Plugin Name: WP-MU Blogs Logo Management
Plugin URI: http://www.webeing.net
Description: Platform administrator could now assign each Blog of the network a Custom Logo
Author: Enrico Corinti 
Author URI: http://www.webeing.com/
Version: 0.1
*/
global $wp_version;

$exitMsg = "BLogos require Wordpress 2.8+";
if (version_compare($wp_version,"2.8","<")){
	exit($exitMsg);
}

if(!class_exists('BLogos')):
class SiteWidePostsManager{
	
}
?>