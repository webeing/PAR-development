<?php get_header() ?>

<div class="content-header">
	
</div>

<div id="content">
	<?php do_action( 'template_notices' ) // (error/success feedback) ?>
	
	<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>
	
	<div class="left-menu">
		<?php locate_template( array( 'groups/single/menu.php' ), true ) ?>
	</div>

	<div class="main-column">
		<div class="inner-tube">
			
			<div id="group-name">
				<h1><a href="<?php bp_group_permalink() ?>"><?php bp_group_name() ?></a></h1>
				<p class="status"><?php bp_group_type() ?></p>
			</div>

			<div class="bp-widget">
				<?php if ( bp_has_topic_posts() ) : ?>
				<form action="<?php bp_forum_topic_action() ?>" method="post" id="forum-topic-form" class="standard-form">
			
					<h4><a href="<?php bp_forum_permalink() ?>">&larr; <?php _e( 'Forum', 'buddypress' ); ?></a> <?php if ( is_user_logged_in() ) : ?><span><a href="#post-topic-reply" title="<?php _e( 'Post New', 'buddypress' ) ?>"><?php _e( 'Post Reply &rarr;', 'buddypress' ) ?></a></span><?php endif; ?></h4>
					
					<div class="pagination">

						<div id="post-count" class="pag-count">
							<?php bp_the_topic_pagination_count() ?>
						</div>
				
						<div class="pagination-links" id="topic-pag">
							<?php bp_the_topic_pagination() ?>
						</div>
					
					</div>
					
					<ul id="topic-post-list" class="item-list">
						<li id="topic-meta">
							<span class="small"><a href="<?php bp_forum_permalink() ?>">&larr; <?php _e( 'Group Forum', 'buddypress' ) ?></a> | <a href="<?php bp_forum_directory_permalink() ?>"><?php _e( 'Forum Topic Directory', 'buddypress') ?></a></span>
							<h3><?php bp_the_topic_title() ?> (<?php bp_the_topic_total_post_count() ?>)</h3>
							
							<?php if ( bp_group_is_admin() || bp_group_is_mod() || bp_get_the_topic_is_mine() ) : ?>
								<div class="admin-links"><?php bp_the_topic_admin_links() ?></div>
							<?php endif; ?>
						</li>
						
					<?php while ( bp_topic_posts() ) : bp_the_topic_post(); ?>
						
						<li id="post-<?php bp_the_topic_post_id() ?>">
							<div class="poster-meta">
								<?php bp_the_topic_post_poster_avatar() ?>
								<?php echo sprintf( __( '%s said %s ago:', 'buddypress' ), bp_the_topic_post_poster_name( false ), bp_the_topic_post_time_since( false ) ) ?>
							</div>
					
							<div class="post-content">
								<?php bp_the_topic_post_content() ?>
							</div>
							
							<?php if ( bp_group_is_admin() || bp_group_is_mod() || bp_get_the_topic_post_is_mine() ) : ?>
								<div class="admin-links"><?php bp_the_topic_post_admin_links() ?></div>
							<?php endif; ?>
						</li>
						
					<?php endwhile; ?>
					
					</ul>
					
					<?php if ( ( is_user_logged_in() && 'public' == bp_get_group_status() ) || bp_group_is_member() ) : ?>
											
						<?php if ( bp_get_the_topic_is_topic_open() ) : ?>
							
							<div id="post-topic-reply">	
								<a name="post-reply"></a>

								<?php if ( !bp_group_is_member() ) : ?>
									<p><?php _e( 'You will auto join this group when you reply to this topic.', 'buddypress' ) ?></p>
								<?php endif; ?>
							
								<?php do_action( 'groups_forum_new_reply_before' ) ?>
						
								<p><strong><?php _e( 'Add a reply:', 'buddypress' ) ?></strong></p>
																
								<textarea name="reply_text" id="reply_text"></textarea>
					
								<p class="submit"><input type="submit" name="submit_reply" id="submit" value="<?php _e( 'Post Reply', 'buddypress' ) ?>" /></p>

								<?php do_action( 'groups_forum_new_reply_after' ) ?>

								<?php wp_nonce_field( 'bp_forums_new_reply' ) ?>
							</div>
							
						<?php else : ?>
							
							<div id="message" class="info">
								<p><?php _e( 'This topic is closed, replies are no longer accepted.', 'buddypress' ) ?></p>
							</div>
							
						<?php endif; ?>
					
					<?php endif; ?>
					
				</form>	
				<?php else: ?>

					<div id="message" class="info">
						<p><?php _e( 'There are no posts for this topic.', 'buddypress' ) ?></p>
					</div>

				<?php endif;?>
			</div>

		</div>
	</div>
	
	<?php endwhile; endif; ?>
</div>

<?php get_footer() ?>