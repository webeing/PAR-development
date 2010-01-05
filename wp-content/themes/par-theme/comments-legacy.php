<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

			<p class="nocomments">This post is password protected. Enter the password to view comments.</p>

			<?php
			return;
		}
	}
?>

<!-- You can start editing here. -->

<li class="post-blank clearfix">
	<h1 class="comments-title"><?php comments_number('0 Comments', '1 Comment', '% Comments' );?></h1>
	<h2 class="comments-title">Take a look at some of the responses we've had to this article.</h2>
</li>
<li class="post clearfix">
	<?php if ($comments) : ?>	
		<ol class="commentlist">
			<?php foreach ($comments as $comment) : ?>
				<li id="comment-<?php comment_ID() ?>" class="clearfix">
					<div class="comment-meta">
                
                        <?php  if ( get_option('woo_gravatar') ) { 
                        // Determine which gravatar to use for the user
                        $email =  $comment->comment_author_email;
                        $grav_url = "http://www.gravatar.com/avatar.php?gravatar_id=".md5($email). "&default=".urlencode($GLOBALS['defaultgravatar'])."&size=48"; 
						?>
                        <span class="gravatar"><img src="<?php echo $grav_url; ?>" width="48" height="48" alt="" /></span>
						<?php } ?>

						<div class="large darkblue comment-author"><?php comment_author_link() ?></div>
                        <!--[if lt IE 7]><div class="clearfix"></div><![endif]-->
 						<div class="small arial">Posted on <?php comment_date('F jS') ?></div>
					</div>
					<div class="comment-content">	
						<?php if ($comment->comment_approved == '0') : ?>
							<strong>Your comment is awaiting moderation.</strong>
						<?php endif; ?>	
						
						<?php comment_text() ?>
					</div>
				</li>
			<?php endforeach;  ?>
		</ol>
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
	<h1 class="comments-title">Post a Comment</h1>
	<h2 class="comments-title">Let us know what you thought.</h2>
</li>
<li class="post-last-blank clearfix">
	<?php if ('open' == $post->comment_status) : ?>
	
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
	<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>
	<?php else : ?>
		
	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" class="post-content clearfix">
		<div class="comment-form-left">
			<?php if ( $user_ID ) : ?>
				<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Log out &raquo;</a></p>
			<?php else : ?>
				<p><span class="small block">Name:</span>
					<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="30" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> class="textfield" />
				</p>
				
				<p><span class="small block">Email<?php if ($req) echo " (required)"; ?>:</span>
					<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="30" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> class="textfield" />
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
			<?php do_action('comment_form', $post->ID); ?>
		</div>	
	</form>
	<?php endif; // If registration required and not logged in ?>
</li>
<?php endif; // if you delete this the sky will fall on your head ?>
