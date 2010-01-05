<?php get_header(); ?>

<!-- Magazine / Blog layout -->
<?php 
if (get_option('woo_show_blog') == 'true') {
	
	// About the blog section
    include('includes/blog.php'); 
	
	// Blog section
    include('layouts/blog.php'); 	
	
} else	{

	// Featured section
    include('includes/featured.php'); 	
	
	// Magazine section
    include('layouts/default.php'); 	

}		
?>
	    
<?php get_footer(); ?>
