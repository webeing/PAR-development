<?php get_header(); ?>
       
    <div id="content" class="col-full">
		<div id="main" class="col-left">
            
            <?php if (have_posts()) : $count = 0; ?>
            
                <span class="archive_header"><?php _e('Search results', 'woothemes') ?> for <?php printf(__('\'%s\''), $s) ?></span>
                
            <?php while (have_posts()) : the_post(); $count++; ?>
                                                                        
                <div class="box">
                    <div class="post">
    
                        <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                        
                        <p class="post-meta">
                            <img src="<?php bloginfo('template_directory'); ?>/images/ico-time.png" alt="" /><?php the_time($GLOBALS['woodate']); ?>
                            <span class="comments"><img src="<?php bloginfo('template_directory'); ?>/images/ico-comment.png" alt="" /><?php comments_popup_link(__('0 Comments', 'woothemes'), __('1 Comment', 'woothemes'), __('% Comments', 'woothemes')); ?></span>
                        </p>
                        
                        <div class="entry">
                            <?php the_excerpt(); ?>
                        </div><!-- /.entry -->
    
                    </div><!-- /.post -->
                    <div class="post-bottom">
                        <div class="fl"><span class="cat"><?php the_category(', ') ?></span></div>
                        <div class="fr"><?php the_tags('<span class="tags">', ', ', '</span>'); ?></div> 
                        <div class="fix"></div>                       
                    </div>
                </div>        
                                                    
               <?php endwhile; else: ?>
                <div class="box">
					<div class="post">
                		<p><?php _e('Sorry, no posts matched your criteria.', 'woothemes') ?></p>
             	   </div><!-- /.post -->
                </div>        
         	   <?php endif; ?>  
        
                <div class="more_entries">
                    <?php if (function_exists('wp_pagenavi')) wp_pagenavi(); else { ?>
                    <div class="fl"><?php previous_posts_link(__('Newer Entries', 'woothemes')) ?></div>
                    <div class="fr"><?php next_posts_link(__('Older Entries', 'woothemes')) ?></div>
                    <br class="fix" />
                    <?php } ?> 
                </div>		
                
        </div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>
