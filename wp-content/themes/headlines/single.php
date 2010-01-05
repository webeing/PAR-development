<?php get_header(); ?>
       
    <div id="content" class="col-full">
		<div id="main" class="col-left">
		           
            <?php if (have_posts()) : $count = 0; ?>
            <?php while (have_posts()) : the_post(); $count++; ?>
            
                <div class="box">
                    <div class="post">
    
						<?php 
						// Show image or ad content
						if ( get_option('woo_image_single') == "true" && !woo_get_embed('embed','590','420') && get_option('woo_ad_content') <> "true" ) 
							woo_get_image('image',get_option('woo_single_width'),get_option('woo_single_height'),'thumbnail alignright'); 
                        elseif ( !woo_get_embed('embed','590','420') && get_option('woo_ad_content') == "true" ) 
							include ( TEMPLATEPATH . '/ads/content_ad.php' );											
						?>
                           
                        <h1 class="title"><?php the_title(); ?></h1>
                        
                        <p class="post-meta">
							<img src="<?php bloginfo('template_directory'); ?>/images/ico-time.png" alt="" /><?php the_time($GLOBALS['woodate']); ?>
                            <span class="comments"><img src="<?php bloginfo('template_directory'); ?>/images/ico-comment.png" alt="" /><?php comments_popup_link(__('0 Comments', 'woothemes'), __('1 Comment', 'woothemes'), __('% Comments', 'woothemes')); ?></span>
                        </p>

						<?php echo woo_get_embed('embed','590','420'); ?>
						<?php if ( woo_get_embed('embed','590','420') && get_option('woo_ad_content') == "true" ) include ( TEMPLATEPATH . '/ads/content_ad.php' ); ?>					
                        <div class="entry">
							<?php the_content(); ?>
                        </div>
                        
                        <?php if ( get_option('woo_social') == "true" ) { ?>
                        <div id="share">
							<div class="banner"><?php _e('Share', 'woothemes'); ?></div>
                            <?php woo_social(); ?>
                        </div>
                        <?php } ?>
                                                
                    </div><!-- /.post -->
                    
                    <div class="post-bottom">
                        <div class="fl"><span class="cat"><?php the_category(', ') ?></span></div>
                        <div class="fr"><?php the_tags('<span class="tags">', ', ', '</span>'); ?></div> 
                        <div class="fix"></div>                       
                    </div>
                    
                </div><!-- /.box -->

                <div class="more_entries">
					<div class="fl"><?php previous_post_link('%link') ?></div>
					<div class="fr"><?php next_post_link('%link') ?></div>
                    <div class="fix"></div>                       
				</div>               
                
				<?php comments_template(); ?>
                                                    
			<?php endwhile; else: ?>
                <div class="box">
                    <div class="post">
                        <p><?php _e('Sorry, no posts matched your criteria.', 'woothemes') ?></p>
                    </div><!-- /.post -->            
				</div>                     
           	<?php endif; ?>  
        
		</div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>