<div id="page-nav">
    <div class="col-full">
        <ul id="nav" class="fl">
            <li><a href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/ico-home.png" class="ico-home" alt="<?php _e('Home', 'woothemes'); ?>" /></a></li>
            <?php wp_list_pages('sort_column=menu_order&depth=4&title_li=&exclude='.get_option('woo_nav_exclude')); ?>
        </ul><!-- /#nav1 -->
        
        <ul class="rss fr">
            <li><a href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>"><?php _e('Posts', 'woothemes') ?></a></li>
            <?php if ( get_option('woo_feedburner_id') ) {?>
            <li class="last"><a href="<?php echo $feedburner_id; ?>" target="_blank"><?php _e('Email', 'woothemes') ?></a></li>
            <?php } ?>
        </ul><!-- /.rss -->
    </div><!-- /.col-full -->
</div><!-- /#page-nav -->
