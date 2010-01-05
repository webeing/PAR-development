<?php
/*
Template Name: Archives
*/
?>

<?php get_header(); ?>
		<div id="featured">
			<div class="container">
				<div class="featured-small clearfix">
					<h2 class="featured">Archives</h2>
				</div>
			</div>
		</div>
		<div id="content">

            <div class="container clearfix">
                <div id="left-col">
                    <ul class="post-list clearfix">
                        <li class="post-last clearfix">
                            <div class="meta">
                                <h3>Post Archives</h3>
                            </div>
                            <div class="post-content">
                                <h2>The Last 30 Posts</h2>
                    
                                <ul>
                                    <?php query_posts('showposts=30'); ?>
                                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                                    
                                        <li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> - <?php the_time('j F Y') ?> - <?php echo $post->comment_count ?> comments</li>
                                    
                                    <?php endwhile; endif; ?>	
                                </ul>				
                                <br /><br />
                                <h2>Archives by Month:</h2>
                                <ul>
                                    <?php wp_get_archives('type=monthly'); ?>
                                </ul>
                                <br /><br />
                                <h2>Archives by Subject:</h2>
                                <ul>
                                     <?php wp_list_categories(); ?>
                                </ul>						
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