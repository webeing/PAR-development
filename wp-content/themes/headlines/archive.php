<?php get_header(); ?>
       
    <div id="content" class="col-full">
		<div id="main" class="col-left">
            
		<?php if (have_posts()) : $count = 0; ?>
        
            <?php if (is_category()) { ?>
            <span class="archive_header"><span class="fl cat"><?php _e('Archive', 'woothemes'); ?> | <?php echo single_cat_title(); ?></span> <span class="fr catrss"><?php $cat_obj = $wp_query->get_queried_object(); $cat_id = $cat_obj->cat_ID; echo '<a href="'; get_category_rss_link(true, $cat, ''); echo '">RSS feed for this section</a>'; ?></span></span>        
        
            <?php } elseif (is_day()) { ?>
            <span class="archive_header"><?php _e('Archive', 'woothemes'); ?> | <?php the_time($GLOBALS['woodate']); ?></span>

            <?php } elseif (is_month()) { ?>
            <span class="archive_header"><?php _e('Archive', 'woothemes'); ?> | <?php the_time('F, Y'); ?></span>

            <?php } elseif (is_year()) { ?>
            <span class="archive_header"><?php _e('Archive', 'woothemes'); ?> | <?php the_time('Y'); ?></span>

			<?php } elseif (is_author()) { ?>
            <span class="archive_header"><?php _e('Archive by Author', 'woothemes'); ?></span>

            <?php } elseif (is_tag()) { ?>
            <span class="archive_header"><?php _e('Tag Archives:', 'woothemes'); ?> <?php echo single_tag_title('', true); ?></span>
            
            <?php } ?>
            
            <div class="fix"></div>
        
        <?php while (have_posts()) : the_post(); $count++; ?>
                                                                    
            <div class="box">
                    <div class="post">
                        
						<?php 
						if (!woo_get_embed('embed','590','420')) 
							woo_get_image('image',200,200,'thumbnail alignleft'); 
						else
							echo woo_get_embed('embed','590','420');
						?> 
                        <h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                        <p class="post-meta">
							<img src="<?php bloginfo('template_directory'); ?>/images/ico-time.png" alt="" /><?php the_time($GLOBALS['woodate']); ?>
                            <span class="comments"><img src="<?php bloginfo('template_directory'); ?>/images/ico-comment.png" alt="" /><?php comments_popup_link(__('0 Comments', 'woothemes'), __('1 Comment', 'woothemes'), __('% Comments', 'woothemes')); ?></span>
                        </p>
                        <div class="entry">
                            
                            <?php if ( get_option('woo_archive_content') == "true" ) { ?>
							<?php the_content(__('Read more...', 'woothemes')); ?>
                            <?php } else { ?>
							<?php the_excerpt(); ?><span class="read-more"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="btn"><?php _e('Read more', 'woothemes'); ?></a></span>
                            <?php } ?>
                            
                        </div>
                        <div class="fix"></div>
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