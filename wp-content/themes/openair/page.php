<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); $preview = get_post_meta($post->ID, 'preview', true); ?>
		<div id="featured">
			<div class="container">
				<div class="featured-small clearfix">
					<h2 class="featured"><?php the_title(); ?></h2>
				</div>
			</div>
		</div>
        <div id="content">

            <div class="container clearfix">
                <div id="left-col">
                    <ul class="post-list clearfix">
                        <li class="post-last clearfix">
                            <div class="meta">
                                    <p>Posted on <?php the_time('F jS, Y') ?></p>
                                    <p>Written by <?php the_author(); ?></p>
                            </div>
                            <div class="post-content">
                                <?php the_content('Continue Reading...'); ?>
                            </div>
                        </li>
                    </ul>
                </div>
                <div id="right-col">
                    <?php get_sidebar(); ?>
                </div>
            </div>
            <?php endwhile; ?>
            <?php else: ?>
                <p>Sorry, no posts matched your criteria.</p>
            <?php endif; ?>
        </div>
<?php get_footer(); ?>
