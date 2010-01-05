<?php get_header(); ?>
       
    <div id="content" class="col-full">
		<div id="main" class="col-left">
	            
            <?php if (have_posts()) : $count = 0; ?>
            <?php while (have_posts()) : the_post(); $count++; ?>
                                                                        
                <div class="box">
                    <div class="post">
    
                        <h1 class="title"><?php the_title(); ?></h1>
                        
                        <div class="entry">
                            <?php the_content(); ?>
                        </div>
                                                
                    </div><!-- /.post -->
                                        
                </div><!-- /.box -->
                
                <?php if ('open' == $post->comment_status) : ?>
	                <?php comments_template(); ?>
				<?php endif; ?>
                                                    
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