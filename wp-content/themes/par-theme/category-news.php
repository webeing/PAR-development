<?php 
/*
 * Template Name: PAR Overall News
 */
?>
<?php get_header(); ?>

	<div id="featured">
		<div class="container">
			<div class="featured-small clearfix">
				<h2 class="featured">PAR "<?php single_cat_title(); ?>" section</h2>
			</div>
		</div>
	</div>
	<div id="content">
      <div class="container clearfix">     
          <div id="left-col">
	<?php 
	$blog_list = get_blog_list( 0, 'all' );
	foreach ($blog_list AS $blog):	
	   	$blogPath = 'http://' . $blog['domain'].$blog['path'];
	    global $switched;
	    switch_to_blog($blog['blog_id']);
	    
	    query_posts("category_name=news&showpost=3&orderby=date&oreder=DESC");
	?>
		<?php if (have_posts()) : ?>
               
                    <ul class="post-list-last clearfix">
                        <?php while (have_posts()) : the_post(); $preview = get_post_meta($post->ID, 'preview', true); ?>
                        <li class="post clearfix">
                            <div class="meta">
                                <h3><a href="<?php echo $blogPath; ?>" title="Go to <?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a> | <?php the_category(', ') ?></h3>
                                    <p>Posted on <?php the_time('F jS, Y') ?></p>
                                    <p>Written by <?php the_author(); ?></p>
                                    
                                <h4 class="tags-top">Tags</h4>
                                    <?php the_tags( '', ', ', ''); ?>
                            </div>
                            <div class="post-content">
                                <h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                                
                                <?php woo_get_image('image',get_option('woo_cat_thumb_width'),get_option('woo_cat_thumb_height'),'thumb alignleft'); ?>
                                
                                <?php the_excerpt(); ?>							
                            </div>
                        </li>

                        <?php endwhile; ?>

						<!-- 
                        <li class="archives clearfix">
                        
                            <h2 class="gray left"><?php next_posts_link('Older Posts <span class="extrasmall georgia lightgray block">Yeah! There are more posts, check them out.</span>') ?></h2>
                            <h2 class="gray right textright"><?php previous_posts_link('Newer Posts <span class="extrasmall georgia lightgray block">Yeah! There are more posts, check them out.</span>') ?></h2>
    
                        </li>
                         -->
                    </ul>
            <?php else: ?>
         		<p>Sorry, no posts matched your criteria.</p>
       	 	<?php endif; restore_current_blog(); endforeach; ?>
            </div>
            <div id="right-col">
                    <?php get_sidebar(); ?>
            </div>
          </div>
		</div>	
<?php get_footer(); ?>