<?php

// =============================== Author widget ======================================
function authorWidget()
{
	$settings = get_option("widget_authorwidget");
	$title = $settings['title'];
	if ($title == "") 
		$title = __('Author Info', 'woothemes');
	if ( is_single() ) :
?>

<div id="author" class="widget">
	<h3 class="widget_title"><img src="<?php bloginfo('template_directory'); ?>/images/ico-author.png" alt="" /><?php echo $title; ?></h3>
	<div class="wrap">
		<div class="fl"><?php echo get_avatar( get_the_author_id(), '48' ); ?></div>
        <span class="author-info"><?php _e('This post was written by', 'woothemes'); ?> <?php the_author_posts_link(); ?> <?php _e('who has written', 'woothemes'); ?> <?php the_author_posts(); ?> <?php _e('posts on', 'woothemes'); ?> <a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a>.</span>
		<br class="fix"></br>
		<p class="author-desc"><?php the_author_description(); ?></p>
	</div>
</div>

<?php
	endif;
}

function authorWidgetAdmin() {

	$settings = get_option("widget_authorwidget");

	// check if anything's been sent
	if (isset($_POST['update_author'])) {
		$settings['title'] = strip_tags(stripslashes($_POST['author_title']));

		update_option("widget_authorwidget",$settings);
	}

	echo '<p>
			<label for="author_title">Title:
			<input id="author_title" name="author_title" type="text" class="widefat" value="'.$settings['title'].'" /></label></p>';
	echo '<p>
			 <small>NOTE: This widget will only show on single post page.</small>
		  </p>';
	echo '<input type="hidden" id="update_author" name="update_author" value="1" />';

}

register_sidebar_widget('Woo - Author', 'authorWidget');
register_widget_control('Woo - Author', 'authorWidgetAdmin', 200, 200);


// =============================== Flickr widget ======================================
function flickrWidget()
{
	$settings = get_option("widget_flickrwidget");

	$id = $settings['id'];
	$number = $settings['number'];

?>

<div id="flickr" class="widget">
	<h3 class="widget_title"><?php _e('Photos on <span>Flick<span>r</span></span>', 'woothemes') ?></h3>
	<div class="wrap">
		<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $number; ?>&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $id; ?>"></script>        
		<div class="fix"></div>
	</div>
</div>

<?php
}

function flickrWidgetAdmin() {

	$settings = get_option("widget_flickrwidget");

	// check if anything's been sent
	if (isset($_POST['update_flickr'])) {
		$settings['id'] = strip_tags(stripslashes($_POST['flickr_id']));
		$settings['number'] = strip_tags(stripslashes($_POST['flickr_number']));

		update_option("widget_flickrwidget",$settings);
	}

	echo '<p>
			<label for="flickr_id">Flickr ID (<a href="http://www.idgettr.com">idGettr</a>):
			<input id="flickr_id" name="flickr_id" type="text" class="widefat" value="'.$settings['id'].'" /></label></p>';
	echo '<p>
			<label for="flickr_number">Number of photos:
			<input id="flickr_number" name="flickr_number" type="text" class="widefat" value="'.$settings['number'].'" /></label></p>';
	echo '<input type="hidden" id="update_flickr" name="update_flickr" value="1" />';

}

register_sidebar_widget('Woo - Flickr', 'flickrWidget');
register_widget_control('Woo - Flickr', 'flickrWidgetAdmin', 400, 200);


// =============================== Ad 200x200 widget ======================================
function ad300Widget()
{
include(TEMPLATEPATH . '/ads/widget_300_ad.php');
}
register_sidebar_widget('Woo - Ad 300x250', 'ad300Widget');

// =============================== Search widget ======================================
function searchWidget()
{
include(TEMPLATEPATH . '/search-form.php');
}
register_sidebar_widget('Woo - Search', 'SearchWidget');

// =============================== Video Player widget ======================================
function videoWidget()
{
	$number = 3;
	$title = "Latest Videos";
	$settings = get_option("widget_videowidget");
	if ($settings['number']) $number = $settings['number'];
	if ($settings['title']) $title = $settings['title'];
?>

<div id="video" class="widget">

    <h3><?php echo $title; ?></h3>
    
    <div class="inside">
		<?php query_posts('showposts='.$number.'&cat='.$GLOBALS[video_id]); ?>
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>	
        
            <div id="video-<?php the_ID(); ?>" class="latest">
                <?php echo woo_get_embed('embed','269','225'); ?> 
            </div>	
            
            <?php endwhile; ?>   
        <?php endif; ?>
	</div>
    
	<?php query_posts('showposts='.$number.'&cat='.$GLOBALS[video_id]); ?>   
    <?php if (have_posts()) : ?>
    
    <ul class="wooTabs">
    
    <?php while (have_posts()) : the_post(); $count++; ?>	        
        <li><a href="#video-<?php the_ID(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>            
    <?php endwhile; ?>
            
    </ul>

    <?php endif; ?>
	
</div>
<?php 
}
register_sidebar_widget('Woo - Video Player', 'videoWidget');

function videoWidgetAdmin() {

	$settings = get_option("widget_videowidget");

	// check if anything's been sent
	if (isset($_POST['update_video'])) {
		$settings['number'] = strip_tags(stripslashes($_POST['video_number']));
		$settings['title'] = strip_tags(stripslashes($_POST['video_title']));
		update_option("widget_videowidget",$settings);
	}

	echo '<p>
			<label for="video_number">Number of videos (default = 5):
			<input id="video_number" name="video_number" type="text" class="widefat" value="'.$settings['number'].'" /></label></p>';
	echo '<p>
			<label for="video_title">Title
			<input id="video_title" name="video_title" type="text" class="widefat" value="'.$settings['title'].'" /></label></p>';
	echo '<label>NOTE: Setup the video category in the theme Options Panel';
	echo '<input type="hidden" id="update_video" name="update_video" value="1" /></label>';


}
register_widget_control('Woo - Video Player', 'videoWidgetAdmin', 200, 200);

// =============================== Ad 125x125 widget ======================================
function adsWidget()
{
$settings = get_option("widget_adswidget");
$number = $settings['number'];
if ($number == 0) $number = 1;
$img_url = array();
$dest_url = array();

$numbers = range(1,$number); 
$counter = 0;

if (get_option('woo_ads_rotate') == "true") {
	shuffle($numbers);
}
?>
<div id="advert_125x125" class="widget">
<?php
	foreach ($numbers as $number) {	
		$counter++;
		$img_url[$counter] = get_option('woo_ad_image_'.$number);
		$dest_url[$counter] = get_option('woo_ad_url_'.$number);
	
?>
        <a href="<?php echo "$dest_url[$counter]"; ?>"><img src="<?php echo "$img_url[$counter]"; ?>" alt="Ad" /></a>
<?php } ?>
</div>
<!--/ads -->
<?php

}
register_sidebar_widget('Woo - Ads 125x125', 'adsWidget');

function adsWidgetAdmin() {

	$settings = get_option("widget_adswidget");

	// check if anything's been sent
	if (isset($_POST['update_ads'])) {
		$settings['number'] = strip_tags(stripslashes($_POST['ads_number']));

		update_option("widget_adswidget",$settings);
	}

	echo '<p>
			<label for="ads_number">Number of ads (1-6):
			<input id="ads_number" name="ads_number" type="text" class="widefat" value="'.$settings['number'].'" /></label></p>';
	echo '<input type="hidden" id="update_ads" name="update_ads" value="1" />';

}
register_widget_control('Woo - Ads 125x125', 'adsWidgetAdmin', 200, 200);




/* Deregister Default Widgets */
function woo_deregister_widgets(){
    unregister_widget('WP_Widget_Search');         
}
add_action('widgets_init', 'woo_deregister_widgets');  


?>