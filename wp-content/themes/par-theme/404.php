<?php get_header(); ?>
		<div id="featured">
			<div class="container">
				<div class="featured-small clearfix">
					<h2 class="featured"><span class="orange">404</span> - Page Not Found</h2>
				</div>
			</div>
		</div>
		<div id="content">

		<div class="container clearfix">
			<div id="left-col">
				<ul class="post-list clearfix">
					<li class="post-last clearfix">
						<div class="meta">
							<h3>Other Links</h3>
								<ul class="related_posts">
									<li><a href="<?php bloginfo('url'); ?>" title="Home">Home</a></li>
									<?php wp_list_pages('title_li='); ?>
									<?php wp_list_categories('title_li='); ?>
								</ul>
						</div>
						<div class="post-content">
							<h2>Oops! It looks like we've made a mistake, something has gone terribly wrong.</h2>
							<p>The page you are looking for has either been moved, or you typed the URL incorrectly.</p>
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
