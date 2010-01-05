<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<li class="post-blank clearfix">
	<h1 class="comments-title"><?php comments_number('0 Comments', '1 Comment', '% Comments' );?></h1>
	<h2 class="comments-title">Take a look at some of the responses we've had to this article.</h2>
</li>
<li class="post clearfix">
	<?php if ( have_comments() ) : ?>
    
    <div id="comments_wrap">   
        <ol class="commentlist">
        <?php wp_list_comments('avatar_size=48&callback=custom_comment'); ?>
        </ol>
        <div class="navigation">
            <div class="fl"><?php previous_comments_link() ?></div>
            <div class="fr"><?php next_comments_link() ?></div>
            <div class="fix"></div>
        </div>
    </div> <!-- end #comments_wrap -->
     
	<?php else : // this is displayed if there are no comments so far ?>
		<?php if ('open' == $post->comment_status) : ?>
			<!-- If comments are open, but there are no comments. -->
		<?php else : // comments are closed ?>
			<!-- If comments are closed. -->
			<p class="nocomments">Comments are closed.</p>
		<?php endif; ?>
	<?php endif; ?>
</li>

<li class="post-blank clearfix">
	<h1 class="comments-title"><?php comment_form_title( 'Leave a Reply', 'Leave a Reply to %s' ); ?></h1>
	<h2 class="comments-title">Let us know what you thought. <?php cancel_comment_reply_link(); ?></h2>

</li>
<li class="post-last-blank clearfix">
<div id="respond">
	<?php if ('open' == $post->comment_status) : ?>
	
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
	<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>
	<?php else : ?>

	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" class="post-content clearfix">
		<div class="comment-form-left">

			<?php if ( $user_ID ) : ?>
				<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(); ?>" title="Log out of this account">Log out &raquo;</a></p>
			<?php else : ?>
				<p><span class="small block">Name<?php if ($req) echo " (required)"; ?>:</span>
					<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="30" tabindex="1" class="textfield" />
				</p>
				
				<p><span class="small block">Email<?php if ($req) echo " (required)"; ?>:</span>
					<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="30" tabindex="2"  class="textfield" />
				</p>
				
				<p><span class="small block">Website:</span>
					<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="30" tabindex="3" class="textfield" />
				</p>
			<?php endif; ?>
				
		</div>
		<div class="comment-form-right">
			<p>Message:
				<textarea name="comment" id="comment" tabindex="4"  rows="10" cols="45" class="textfield"></textarea>
			</p>
            <p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit your comment" class="button" /></p>
			<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
			<?php comment_id_fields(); ?>
            <?php do_action('comment_form', $post->ID); ?>
		</div>	
	</form>
	<?php endif; // If registration required and not logged in ?>
</div> <!-- end #respond -->
</li>
<?php endif; // if you delete this the sky will fall on your head ?>