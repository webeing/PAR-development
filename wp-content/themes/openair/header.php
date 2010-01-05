<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

    <title>
    <?php if ( is_home() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php bloginfo('description'); ?><?php } ?>
    <?php if ( is_search() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Search Results<?php } ?>
    <?php if ( is_author() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Author Archives<?php } ?>
    <?php if ( is_single() ) { ?><?php wp_title(''); ?>&nbsp;|&nbsp;<?php bloginfo('name'); ?><?php } ?>
    <?php if ( is_page() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php wp_title(''); ?><?php } ?>
    <?php if ( is_category() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Archive&nbsp;|&nbsp;<?php single_cat_title(); ?><?php } ?>
    <?php if ( is_month() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Archive&nbsp;|&nbsp;<?php the_time('F'); ?><?php } ?>
    <?php if (function_exists('is_tag')) { if ( is_tag() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Tag Archive&nbsp;|&nbsp;<?php  single_tag_title("", true); } } ?>
    </title>

	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	
    <?php // Get archive link page id
		$is_video = get_option('woo_vidpage'); // Name of the archives page
		$is_video_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$is_video'");							
		if ( is_page($is_video) or is_page($is_video_id) ) { 
	?>

	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/mootools.js"></script>	
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/swfobject.js"></script>	
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/videobox.css" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/videobox.js"></script>				

	<?php } ?>

    <script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/suckerfish.js"></script>   
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>    
    <script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/carousel.js"></script>

    <!--[if lt IE 7]>
    <script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE7.js" type="text/javascript"></script>
    <![endif]-->

	<?php 
		// Includes for WooThemes functions
		include(TEMPLATEPATH . '/includes/categories.php');
		include(TEMPLATEPATH . '/includes/stylesheet.php');
	?>	
		
	<?php wp_head(); ?>

</head>

<body>

	<?php $template_path = get_bloginfo('template_directory'); $GLOBALS['defaultgravatar'] = $template_path . '/images/gravatar.jpg'; ?>

	<div id="pages-top">
		<div class="container clearfix">
			<ul id="pages-list" class="clearfix">
				<li class="blank"><a href="<?php bloginfo('url'); ?>" title="Return Home">Home</a></li>
				<?php wp_list_pages('sort_column=menu_order&title_li=&depth=3'); ?>
			</ul>
			<div id="subscribe">
				<a class="rss" href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>" title="URL for Posts RSS 2.0 Feed">Grab the RSS feed</a>
			</div>
		</div>
	</div>
    
	<div id="header">
		<div class="container clearfix">
			<div id="logo-back">
				<h1><?php bloginfo('name'); ?></h1>
				<a href="<?php bloginfo('url'); ?>"><img src="<?php if ( get_option('woo_logo') <> "" ) { echo get_option('woo_logo'); } else { ?><?php bloginfo('template_directory'); ?>/styles/<?php echo $style_path; ?>/logo-trans.png<?php } ?>" alt="<?php bloginfo('name'); ?>" /></a>
				<div id="search">
					<?php include(TEMPLATEPATH . '/searchform.php'); ?>
				</div>
			</div>
		</div>
	</div>
    
	<div id="categories">
		<div class="container">
			<ul id="category-list" class="clearfix">
				<li<?php if(is_home()): ?> class="current-cat"<?php endif; ?>><a href="<?php bloginfo('url'); ?>">Home</a></li>
				<?php wp_list_categories('title_li='); ?>
			</ul>
		</div>
	</div>
	