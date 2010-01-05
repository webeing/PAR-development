<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

<title>
<?php if ( is_home() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php bloginfo('description'); ?><?php } ?>
<?php if ( is_search() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php _e('Search Results', 'woothemes'); ?><?php } ?>
<?php if ( is_author() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php _e('Author Archives', 'woothemes'); ?><?php } ?>
<?php if ( is_single() ) { ?><?php wp_title(''); ?>&nbsp;|&nbsp;<?php bloginfo('name'); ?><?php } ?>
<?php if ( is_page() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php wp_title(''); ?><?php } ?>
<?php if ( is_category() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php _e('Archive', 'woothemes'); ?>&nbsp;|&nbsp;<?php single_cat_title(); ?><?php } ?>
<?php if ( is_month() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php _e('Archive', 'woothemes'); ?>&nbsp;|&nbsp;<?php the_time('F'); ?><?php } ?>
<?php if (function_exists('is_tag')) { if ( is_tag() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php _e('Tag Archive', 'woothemes'); ?>&nbsp;|&nbsp;<?php  single_tag_title("", true); } } ?>
</title>
    
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
   
<!--[if IE 6]>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/pngfix.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/menu.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/css/ie6.css" />
<![endif]-->	

<!--[if IE 7]>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/css/ie7.css" />
<![endif]-->
   
<?php if ( is_single() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>

<!--[if lte IE 7]>
<script type="text/javascript">
jQuery(function() {
	var zIndexNumber = 1000;
	jQuery('div').each(function() {
		jQuery(this).css('zIndex', zIndexNumber);
		zIndexNumber -= 10;
	});
});
</script>
<![endif]-->

<script type="text/javascript">
jQuery(window).load(function(){
	jQuery("#loopedSlider").loopedSlider({
	<?php
		$autoStart = 0;
		$slidespeed = 600;
		if ( get_option("woo_slider_auto") == "true" ) 
		   $autoStart = get_option("woo_slider_interval") * 1000;
		else 
		   $autoStart = 0;
		if ( get_option("woo_slider_speed") <> "" ) 
			$slidespeed = get_option("woo_slider_speed") * 1000;
	?>
		autoStart: <?php echo $autoStart; ?>, 
		slidespeed: <?php echo $slidespeed; ?>, 
		autoHeight: true
	});
});
</script>

</head>

<?php woo_globals(); // Set global variables ?>

<body>

<div id="container">
       
	<div id="top">
    
		<?php if ( get_option('woo_nav_top') == "true" ) include( TEMPLATEPATH . '/includes/page-nav.php' ); ?>

        <div id="header" class="col-full">
       
            <div id="logo">
               
                <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('description'); ?>"><img class="title" src="<?php if ( get_option('woo_logo') <> "" ) { echo get_option('woo_logo'); } else { bloginfo('template_directory'); ?>/images/logo.png<?php } ?>" alt="<?php bloginfo('name'); ?>" /></a>
                
                <?php if(is_single() || is_page()) : ?>
                    <span class="site-title"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></span>
                <?php else: ?>
                    <h1 class="site-title"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
                <?php endif; ?>
                
                    <span class="site-description"><?php bloginfo('description'); ?></span>
                
            </div><!-- /#logo -->
               
			<?php if (get_option('woo_ad_top') == "true") { ?>
            <div id="topad">
                <?php include (TEMPLATEPATH . "/ads/top_ad.php"); ?>
            </div><!-- /#topad -->
            <?php } elseif (get_option('woo_twitter')) { ?>
            <div id="twitter-top">
            	<div class="logo">
                    <a href="http://www.twitter.com/<?php echo get_option('woo_twitter'); ?>" title="<?php _e('Follow on Twitter', 'woothemes'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/ico-twitter.png" alt="<?php _e('Twitter', 'woothemes'); ?>" /></a>                
                </div>
                <div class="tweet">
	                <ul id="twitter_update_list"><li></li></ul>
                </div>
            </div><!-- /#topad -->
			<?php } ?>
                       
        </div><!-- /#header -->
        
        <div id="navigation">
        
			<?php if ( get_option('woo_nav_top') <> "true" ) include( TEMPLATEPATH . '/includes/page-nav.php' ); ?>
                        
            <div id="cat-nav">
                <div class="col-full">
                    <ul id="secnav" class="fl">
						<?php foreach ( (get_categories('hide_empty=0&exclude='.get_option('woo_nav_exclude') ) ) as $category ) { if ( $category->category_parent == '0' ) { ?>      
                        <li>
                            <a href="<?php echo get_category_link($category->cat_ID); ?>"><?php echo $category->cat_name; ?><?php if ( $GLOBALS[desc] ) { ?><br/><span><?php echo $category->category_description; ?></span><?php } ?></a>
                            
                            <?php if (get_category_children($category->cat_ID) ) { ?>
                            <ul><?php wp_list_categories('title_li&child_of=' . $category->cat_ID ); ?></ul>
                            <?php } ?>
                        </li>        
                        <?php } } ?>
                    </ul><!-- /#nav2 -->
                    
                    <?php if ( get_option('woo_search_disable') <> "true" ) : ?>
                    <div id="search" class="fr">
                        <form method="get" id="searchform" action="<?php bloginfo('url'); ?>">
                            <input type="text" class="field" name="s" id="s"  value="<?php _e('Enter keywords...', 'woothemes') ?>" onfocus="if (this.value == '<?php _e('Enter keywords...', 'woothemes') ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Enter keywords...', 'woothemes') ?>';}" />
                            <input class="submit btn" type="image" src="<?php bloginfo('template_directory'); ?>/images/ico-search.png" value="Go" />
                        </form>
                    </div><!-- /#search -->
                    <?php endif; ?>
                </div><!-- /.col-full -->
            </div><!-- /#cat-nav -->
            
        </div><!-- /#navigation -->
    
    </div><!--/#top-->