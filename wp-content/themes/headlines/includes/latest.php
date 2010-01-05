<?php 
	$number = get_option('woo_tabs_latest'); if (empty($number) || $number < 1) $number = 5;
	$the_query = new WP_Query('cat=' . $GLOBALS['featured_cat'] . '&showposts='. $number .'&orderby=post_date&order=desc');	
	while ($the_query->have_posts()) : $the_query->the_post(); $do_not_duplicate = $post->ID;
?>
<li>
	<?php woo_get_image('image',48,48,'thumbnail',90,$post->ID,'src',1,0,'','',true,false,false); ?>
	<a title="<?php _e('Permalink to ', 'woothemes'); ?> <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
	<span class="meta"><?php the_time($GLOBALS['woodate']); ?></span>
	<div class="fix"></div>
</li>
<?php endwhile; ?>		
