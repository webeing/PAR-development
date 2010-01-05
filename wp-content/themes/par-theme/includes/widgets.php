<?php

function BlockAds()
{

$img_url = array();
$dest_url = array();
$numbers = range(1,2); 
$counter = 0;

shuffle($numbers);

foreach ($numbers as $number) {
	
	$counter++;
	
	$img_url[$counter] = get_option('woo_ad_image_'.$number);
	$dest_url[$counter] = get_option('woo_ad_url_'.$number);
	
	}
	
?>

	<li>
		<div class="box">
			<a href="<?php echo "$dest_url[1]"; ?>"><img src="<?php echo "$img_url[1]"; ?>" alt="Ad" /></a>
			<a href="<?php echo "$dest_url[2]"; ?>"><img src="<?php echo "$img_url[2]"; ?>" alt="Ad" /></a>            
		</div>
	</li>

<?php

}
		
function FlickrBox()
{

?>

		<li><h5>Flickr Photos</h5>
			<ul class="flickr_photos">
								
            <li class="clearfix"><script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo get_option('woo_flickr_entries'); ?>&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo get_option('woo_flickr_id'); ?>"></script>
            </li>								
			</ul>
		</li>
	
<?php 	

}

function SidePopular()
{

?>

	<li><h5>Popular Content</h5>
		<ul>
			 <?php $featured = get_option('woo_featured_category'); $feature = new WP_Query('category_name='.$featured.'&amp;showposts=8'); while ($feature->have_posts()) : $feature->the_post(); $do_not_duplicate = $post->ID; $preview = get_post_meta($post->ID, 'preview', true); ?>
				<li class="popular">
                
                    <!-- Custom setting image -->
                    <?php if (get_post_meta($post->ID, "image", $single = true)) { ?>
                    <a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="lightbox"><img src="<?php echo bloginfo('template_url'); ?>/thumb.php?src=<?php echo get_post_meta($post->ID, "image", $single = true); ?>&amp;h=138&amp;w=216&amp;zc=1&amp;q=90" alt="<?php the_title(); ?>" class="post-preview left" /></a>   
                    <?php } ?>       	
					<p><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a><br /><span class="small arial">Posted on <?php the_time('F jS, Y') ?></span></p>
                </li>
			<?php endwhile; ?>
		</ul>
	</li>
	
<?php 	

}
function Papercut_Tag_Cloud()
{

?>

		<li class="widget">
        	<h2>Tag Cloud</h2>
			<ul><li class="tag_cloud clearfix"><?php if (function_exists('wp_tag_cloud')) { wp_tag_cloud('smallest=10&largest=18'); } ?></li></ul>
		</li>
	
<?php 	

}

register_sidebar_widget('180x150 Ad Blocks', 'BlockAds');
register_sidebar_widget('Sidebar Popular Content', 'SidePopular');
register_sidebar_widget('Flickr Photos', 'FlickrBox');
register_sidebar_widget('Papercut Tag Cloud', 'Papercut_Tag_Cloud');

?>