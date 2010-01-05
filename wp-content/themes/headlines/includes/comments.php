<?php

$comment_posts = get_option('woo_tabs_comments');
if (empty($comment_posts) || $comment_posts < 1) $comment_posts = 5;

global $wpdb;
 
$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID,
comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved,
comment_type,comment_author_url,
SUBSTRING(comment_content,1,50) AS com_excerpt
FROM $wpdb->comments
LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
$wpdb->posts.ID)
WHERE comment_approved = '1' AND comment_type = '' AND
post_password = ''
ORDER BY comment_date_gmt DESC LIMIT ".$comment_posts;

$comments = $wpdb->get_results($sql);
$output = $pre_HTML;

foreach ($comments as $comment) {


?>
<li>
    <?php echo get_avatar( $comment, '48' ); ?>

	<a href="<?php echo get_permalink($comment->ID); ?>#comment-<?php echo $comment->comment_ID; ?>" title="<?php _e('on ', 'woothemes'); ?> <?php echo $comment->post_title; ?>">
		<?php echo strip_tags($comment->comment_author); ?>: <?php echo strip_tags($comment->com_excerpt); ?>...
    </a>
    <div style="clear:both"></div>
</li>
<?php 
}
?>