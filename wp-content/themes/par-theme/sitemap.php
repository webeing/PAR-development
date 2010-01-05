<?php
/*
Template Name: Sitemap
*/
?>
<?php get_header(); ?>
		<div id="featured">
			<div class="container">
				<div class="featured-small clearfix">
					<h2 class="featured">Sitemap</h2>
				</div>
			</div>
		</div>
		<div id="content">
            <div class="container clearfix">
                <div id="left-col">
                    <ul class="post-list clearfix">
                        <li class="post-last clearfix">
                            <div class="meta">
                                <h3>Site overview</h3>
                            </div>
                            <div class="post-content">
                                <h2>Pages</h2>
            
                                <ul>
                                    <?php wp_list_pages('depth=1&sort_column=menu_order&title_li=' ); ?>		
                                </ul>		
    
                                <br /><br />
        
                                <h2>Categories</h2>
            
                                <ul>
                                    <?php wp_list_categories('title_li=&hierarchical=0&show_count=1') ?>	
                                </ul>								
                                
                                <?php
                        
                                $cats = get_categories();
                                foreach ($cats as $cat) {
                        
                                query_posts('cat='.$cat->cat_ID);
                    
                                ?>
                            
                                <br /><br />
                                <h2><?php echo $cat->cat_name; ?></h2>
                    
                                <ul>	
                                        <?php while (have_posts()) : the_post(); ?>
                                        <li style="font-weight:normal !important;"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> - Comments (<?php echo $post->comment_count ?>)</li>
                                        <?php endwhile;  ?>
                                </ul>
                        
                                <?php } ?>	
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
