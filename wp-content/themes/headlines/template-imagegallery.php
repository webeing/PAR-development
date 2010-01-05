<?php
/*
Template Name: Image Gallery
*/
?>
<?php get_header(); ?>
       
    <div id="content" class="col-full">
		<div id="main" class="col-left">
					
            <div class="box">
                <div class="post">
                
                    <div class="entry">
                    
						<h2><?php the_title(); ?></h2>			
    
                        <?php query_posts('showposts=60'); ?>
                        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>				
                            <?php $wp_query->is_home = false; ?>
    
							<?php woo_get_image('image',110,110,'thumbnail alignleft'); ?>
                        
                        <?php endwhile; endif; ?>	
                    
                    </div><!-- /.entry -->
                                    
                </div><!-- /.post -->    
                <div class="fix"></div>                
			</div>	                
        </div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>
