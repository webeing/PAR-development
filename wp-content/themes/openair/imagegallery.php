<?php
/*
Template Name: Image Gallery
*/
?>
<?php get_header(); ?>
		<div id="featured">
			<div class="container">
				<div class="featured-small clearfix">
					<h2 class="featured">Image Gallery</h2>
				</div>
			</div>
		</div>
		<div id="content">

            <div class="container clearfix">
                <div id="left-col">
                    <ul class="post-list clearfix">
                        <li class="post-last clearfix">
                            <div class="meta">
                                <h3>Post Images</h3>
                            </div>
                            <div class="post-content">                    
                                <?php query_posts('showposts=50'); ?>
                                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>				
                                    
								<?php woo_get_image('image',get_option('woo_thumb_width'),get_option('woo_thumb_height'),'thumb alignleft'); ?>
                                
                                <?php endwhile; endif; ?>	
                                
                                <div style="clear:both;"></div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div id="right-col">
                    <?php get_sidebar(); ?>
                </div>
            </div>
		</div>

<?php get_footer(); ?>
