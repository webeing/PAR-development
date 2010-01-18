<?php if(is_home()) : ?>

<?php 

	$blogsManager = new SiteWidePostsManager(1,2);

?>

<div id="featured">
	<div class="container">
		<div class="featured-norm clearfix">
			 <?php $feature = new WP_Query('category_name='.get_option('woo_featured_category').'&showposts=1'); while ($feature->have_posts()) : $feature->the_post(); $do_not_duplicate = $post->ID; $preview = get_post_meta($post->ID, 'preview', true); ?>

				<div class="featured-content">
					<?php var_dump($blogsManager->getAllPostsByCategoryName('Featured')); ?>
					<h2 class="featured"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt(''); ?>
				</div>
				<div class="featured-preview">

					<?php woo_get_image('image',550,220,'thumb alignleft'); ?>

				</div>
	
    			<?php $GLOBALS['exclude'] = $post->ID; ?>             														
                
			<?php endwhile; ?>
		</div>
	</div>
</div>
<!-- / featured -->

<?php endif; ?>    
