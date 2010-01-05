<div id="content">    
    <div class="container clearfix">
        <div id="left-col">
            <?php if (have_posts()) : $count = 0; $count2 = 0; ?>
                <ul class="post-list clearfix">
                <?php while (have_posts()) : the_post(); $preview = get_post_meta($post->ID, 'preview', true); ?>
                    <?php $home_posts = get_option('woo_home_posts'); if($count < $home_posts) : ?>
					<?php if ( $GLOBALS['exclude'] <> $post->ID ) { ?> 
                    <?php $exarr .= ",".$post->ID; ?>
                        <li class="post clearfix">
                            <div class="meta">
                                <h3><?php the_category(', ') ?></h3>
                                    <p>Posted on <?php the_time('F jS, Y') ?></p>
                                    <p>Written by <?php the_author_posts_link(); ?></p>
                
                                <h4 class="related-posts">Related Posts</h4>
                                    <ul class="related_posts">
                                        <?php rp_related_posts(''); ?>
                                    </ul>
                            </div>
                            <div class="post-content">
                                <h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

                                <!-- Custom setting image -->
                                <?php woo_get_image('image',get_option('woo_thumb_width'),get_option('woo_thumb_height'),'thumb alignleft'); ?> 	
                                
                                <?php if ( get_option('woo_the_content') == 'true' ) { the_content('Continue Reading...'); } else { the_excerpt(); } ?>
                                
                            </div>
                        </li>
                    <?php } else { $count--; } ?>
                    <?php endif; ?>
                <?php $count++; endwhile; ?>

				<?php $getcats = get_categories('hierarchical=0&hide_empty=0&include=' . get_inc_categories("woo_cat_mid_")); ?>
                
                <?php foreach ( $getcats as $cat ) { ?>
                
					<?php query_posts("cat=".$cat->cat_ID); if (have_posts()) : ?>		
                    
                    <?php while (have_posts()) : the_post(); $count++; if (strstr($exarr,$post->ID) && get_option('woo_exclude_cats') == 'true' ) continue; ?>			
                
                        <li class="other-post<?php if($count2 % 3 == 0) { ?>-last<?php } ?> clearfix">
                            <span class="darkblue block"><?php the_category(', ') ?></span>

                            <!-- Custom setting image -->
                            <?php if (get_post_meta($post->ID, "image", $single = true)) { ?>
                            <a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>"><img src="<?php echo bloginfo('template_url'); ?>/thumb.php?src=<?php echo get_post_meta($post->ID, "image", $single = true); ?>&amp;h=<?php if ( get_option('woo_cat_thumb_height') <> "" ) { echo get_option('woo_cat_thumb_height'); } else { ?>76<?php } ?>&amp;w=<?php if ( get_option('woo_cat_thumb_width') <> "" ) { echo get_option('woo_cat_thumb_width'); } else { ?>207<?php } ?>&amp;zc=1&amp;q=90" alt="<?php the_title(); ?>" class="block other-posts-preview" /></a>          	
                            <?php } ?>       	

                            <div class="arial grayblue bold block"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></div>
                            <div class="arial small darkgray"><p><?php the_content_rss('', TRUE, '', 10); ?></p></div>
                        </li>					

					<?php break; endwhile; ?>
                    <?php endif; ?>
                        
				<?php } ?>							
                               
                    <li class="archives clearfix">
                                            
                        <?php if (get_option('woo_archives')) { ?> <h2 class="gray left"><a href="<?php echo get_option('woo_archives'); ?>">View the Archives<span class="extrasmall georgia lightgray block">Yeah! There are more posts, check them out</span></a></h2> <?php } ?>
                                                    
                        <h2 class="gray right textright"><a href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>" title="URL for Posts RSS 2.0 Feed">Subscribe to Updates<span class="extrasmall georgia lightgray block">Subscribe to the RSS feed to stay updated</span></a></h2>

                    </li>
                </ul>
            <?php endif; ?>
        </div>
        <div id="right-col">
            <?php get_sidebar("bottom"); ?>
        </div>
    </div>
</div>
<!-- /content -->        