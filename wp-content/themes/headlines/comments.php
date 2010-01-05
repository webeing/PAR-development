<?php
	
	// Do not delete these lines
	
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'woothemes') ?></p>
	
	<?php return; } ?>

<!-- You can start editing here. -->

<div id="comments">

	<?php if ( have_comments() ) : ?>

		<h3><?php comments_number(__('No Responses', 'woothemes'), __('One Response', 'woothemes'), __('% Responses', 'woothemes') );?> <?php _e('to', 'woothemes') ?> &#8220;<?php the_title(); ?>&#8221;</h3>

		<ol class="commentlist">
	
			<?php wp_list_comments('avatar_size=70&callback=custom_comment&type=comment'); ?>
		
		</ol>    

		<div class="navigation">
			<div class="fl"><?php previous_comments_link() ?></div>
			<div class="fr"><?php next_comments_link() ?></div>
			<div class="fix"></div>
		</div><!-- /.navigation -->
		    
		<?php if ( $comments_by_type['pings'] ) : ?>
    		
    		<h3 id="pings"><?php _e('Trackbacks/Pingbacks', 'woothemes') ?></h3>
    
    		<ol class="commentlist">
			
			    <?php wp_list_comments('type=pings'); ?>
		
		    </ol>
    	
    	<?php endif; ?>
    	
	<?php else : // this is displayed if there are no comments so far ?>

		<?php if ('open' == $post->comment_status) : ?>
			<!-- If comments are open, but there are no comments. -->
			<h3 class="nocomments"><?php _e('No comments yet... Be the first to leave a reply!', 'woothemes') ?></h3>

		<?php else : // comments are closed ?>
			<!-- If comments are closed. -->
			<h3 class="nocomments"><?php _e('Comments are closed.', 'woothemes') ?></h3>

		<?php endif; ?>

	<?php endif; ?>

</div> <!-- /#comments_wrap -->

<?php if ('open' == $post->comment_status) : ?>

<div id="respond">

	<h3><?php comment_form_title( __('Leave a Reply', 'woothemes'), __('Leave a Reply to %s', 'woothemes') ); ?></h3>
	
	<div class="cancel-comment-reply">
		<small><?php cancel_comment_reply_link(); ?></small>
	</div><!-- /.cancel-comment-reply -->

	<?php if ( get_option('comment_registration') && !$user_ID ) : //If registration required & not logged in. ?>

		<p><?php _e('You must be', 'woothemes') ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e('logged in', 'woothemes') ?></a> <?php _e('to post a comment.', 'woothemes') ?></p>

	<?php else : //No registration required ?>
	    
		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" onsubmit="if (url.value == '<?php _e('Website (optional)', 'woothemes'); ?>') {url.value = '';}">

	    <div class="left">

		<?php if ( $user_ID ) : //If user is logged in ?>

			<p><?php _e('Logged in as', 'woothemes') ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(); ?>" title="<?php _e('Log out of this account', 'woothemes') ?>"><?php _e('Logout', 'woothemes') ?> &raquo;</a></p>

		<?php else : //If user is not logged in ?>

			<p>
                <input type="text" name="author" class="txt" id="commentauthor" tabindex="1" value="Name <?php if ($req) echo "(required)"; ?>" onfocus="if (this.value == '<?php _e('Name (required)', 'woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Name (required)', 'woothemes'); ?>';}" />
			</p>

			<p>
                <input type="text" name="email" class="txt" id="email" tabindex="2" value="Email <?php if ($req) echo "(required)"; ?>" onfocus="if (this.value == '<?php _e('Email (required)', 'woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Email (required)', 'woothemes'); ?>';}" />
			</p>

			<p>
                <input type="text" name="url" class="txt" id="url" tabindex="3" value="<?php _e('Website (optional)', 'woothemes'); ?>" onfocus="if (this.value == '<?php _e('Website (optional)', 'woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Website (optional)', 'woothemes'); ?>';}" />
			</p>

		<?php endif; // End if logged in ?>
        
        </div>

		<div class="right">
        
		<!--<p><strong>XHTML:</strong> <?php _e('You can use these tags', 'woothemes'); ?>: <?php echo allowed_tags(); ?></p>-->

		<p><textarea name="comment" id="comment" rows="10" cols="50" tabindex="4"></textarea></p>

		<input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment', 'woothemes') ?>" />
		<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
		
		<?php comment_id_fields(); ?>
		<?php do_action('comment_form', $post->ID); ?>
		
        </div>
        
		</form><!-- /#commentform -->

	<?php endif; // If registration required ?>

	<div class="fix"></div>

</div><!-- /#respond -->

<?php endif; // if you delete this the sky will fall on your head ?>
