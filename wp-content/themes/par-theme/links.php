<?php
/*
Template Name: Links
*/
?>

<?php get_header(); ?>
		<div id="featured">
			<div class="container">
				<div class="featured-small clearfix">
					<h2 class="featured">Links</h2>
				</div>
			</div>
		</div>
		<div id="content">

		<div class="container clearfix">
			<div id="left-col">
				<ul class="post-list clearfix">
					<li class="post-last clearfix">
						<div class="meta">
							<h3>Site Links</h3>
						</div>
						<div class="post-content">
								<ul>
									<?php wp_list_bookmarks('title_li='); ?>
								</ul>				
						</div>
					</li>
				</ul>
			</div>
			<div id="right-col">
				<?php get_sidebar(); ?>
			</div>
		</div>
		</div>

<?php get_footer(); ?>