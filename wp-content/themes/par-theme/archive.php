<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<div id="featured">
			<div class="container">
				<div class="featured-small clearfix">
					<?php $post = $posts[0];  ?>
					<?php if (is_category()) { ?>
						<h2 class="featured">Archive for "<?php single_cat_title(); ?>"</h2>
					<?php } elseif( is_tag() ) { ?>
						<h2 class="featured">Posts Tagged with "<?php single_tag_title(); ?>"</h2>
					<?php } elseif (is_day()) { ?>
						<h2 class="featured">Archive for "<?php the_time('F jS, Y'); ?>"</h2>
					<?php } elseif (is_month()) { ?>
						<h2 class="featured">Archive for "<?php the_time('F, Y'); ?>"</h2>
					<?php } elseif (is_year()) { ?>
						<h2 class="featured">Archive for "<?php the_time('Y'); ?>"</h2>
					<?php } elseif (is_author()) { ?>
						<h2 class="featured">"Author" Archive</h2>
					<?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
						<h2 class="featured">"Blog" Archives</h2>
					<?php } ?>
				</div>
			</div>
		</div>
		<div id="content">

            <div class="container clearfix">
                <div id="left-col">
                    <ul class="post-list-last clearfix">
                        <?php while (have_posts()) : the_post(); $preview = get_post_meta($post->ID, 'preview', true); ?>
                        <li class="post clearfix">
                            <div class="meta">
                                <h3><?php the_category(', ') ?></h3>
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

                        <li class="archives clearfix">
                        
                            <h2 class="gray left"><?php next_posts_link('Older Posts <span class="extrasmall georgia lightgray block">Yeah! There are more posts, check them out.</span>') ?></h2>
                            <h2 class="gray right textright"><?php previous_posts_link('Newer Posts <span class="extrasmall georgia lightgray block">Yeah! There are more posts, check them out.</span>') ?></h2>
    
                        </li>
                        
                    </ul>
                </div>
                <div id="right-col">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        <?php else: ?>
                <p>Sorry, no posts matched your criteria.</p>
        <?php endif; ?>
		</div>
<?php get_footer(); ?>