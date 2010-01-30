<?php ?>
<div class="wrap" style="max-width:950px !important;">
	<h2>Site Wide Posts Category Filter</h2>
	<div id="poststuff" style="margin-top:10px;">
		<div id="mainblock" style="width:710px">
			<div class="dbx-content">
				<form action="<?php echo $action_url ?>" method="post">
					<input type="hidden" name="submitted" value="1" />
					<?php wp_nonce_field('swpcf_nonce'); ?>
					<h3>Usage</h3>
					<p>Configure the featured section </p>
					<br />
					<h3>Options</h3>
					<p>You can choose the the number of posts that you want to show in the
					featured section.</p>
					<label> Total number of posts to show
					<input type="text" name="totalposts" id="totalposts" value="<?php echo $totalposts ?>" />
					<span>es. 2</span></label>
					
					<p>You can choose the number of posts by category to show					
					</p>
					<label> Number of posts by category
					<input type="text" name="postsbycategory" id="postsbycategory" value="<?php echo $postsbycategory ?>" />
					<span>es. 2</span></label>
					
					<p>You can choose the category that you want to show in the
					featured section.<br/>
					For multiple categories use comma-separated list of category <strong>names</strong><em>(not slug!!!)</em>					
					</p>
					<label> The categories to show
					<input type="text" name="categories" id="categories" value="<?php echo $categories ?>" />
					<span>es. Featured,News</span></label>
					
					<p>You can exclude an entore blog of the network from queries<br/>
					Is necessary indicate the number of the blog (ID) that is show in the <a href="<?php bloginfo('url'); ?>/wp-admin/wpmu-blogs.php" title="">Administration Dashboard</a>  
										
					</p>
					<label> The blog ID to exclude by loop
					<input type="text" name="blogtoexclude" id="blogtoexclude" value="<?php echo $blogtoexclude ?>" />
					<span>es. "1" for exclude by loop the Main Blog</span></label>
					
					
					<br />
					<div class="submit"><input type="submit" name="Submit"
					value="Update" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
