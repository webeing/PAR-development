<?php
/*
Template Name: OpenAir Video
*/
?>

<?php get_header(); ?>

		<div id="featured">
			<div class="container">
				<div class="featured-small clearfix">
					<h2 class="featured">Video Gallery</h2>
				</div>
			</div>
		</div>
		<div id="content">
    
            <div class="container clearfix">
                <div id="left-col">
    
                    <ul class="post-list clearfix">
    
        <?php query_posts('tag=video&showposts=' . get_option('woo_video_posts') ); ?>
        
        <?php $count = 0; ?>
        
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); $preview = get_post_meta($post->ID, 'preview', true); ?>				
    
                                <li class="other-post<?php $count++; if( $count == 3) { $count = 0; ?>-last<?php } ?> clearfix">
                                    <span class="darkblue block"><?php echo get_post_meta($post->ID, "video", $single = true); ?></span>
                                    
                            <a href="<?php echo get_post_meta($post->ID, "url", $single = true); ?>" rel="vidbox" title="<?php echo get_post_meta($post->ID, "video", $single = true); ?>"><img src="<?php echo bloginfo('template_url'); ?>/thumb.php?src=<?php if (get_post_meta($post->ID, "vidimage", $single = true)) { echo get_post_meta($post->ID, "vidimage", $single = true); } else { echo get_post_meta($post->ID, "image", $single = true); } ?>&amp;h=<?php if ( get_option('woo_cat_image_height') <> "" ) { echo get_option('woo_cat_image_height'); } else { ?>76<?php } ?>&amp;w=<?php if ( get_option('woo_cat_image_width') <> "" ) { echo get_option('woo_cat_image_width'); } else { ?>207<?php } ?>&amp;zc=1&amp;q=90" alt="<?php the_title(); ?>" class="block other-posts-preview" /></a>          	   	
    
                                    <div class="arial grayblue bold block">Originally posted in: <span style="font-weight: normal !important;"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></span></div>
                                </li>		
    
            <?php endwhile; ?>
        <?php else: ?>
                <p>Sorry, no posts matched your criteria.</p>
        <?php endif; ?>							
                    
                    </ul>
    
                </div>
                <div id="right-col">
                    <?php get_sidebar(); ?>
                </div>
            </div>
    
		</div>
<?php get_footer(); ?>
