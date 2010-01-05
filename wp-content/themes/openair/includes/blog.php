
<?php if(is_home()) : ?>
<div id="featured">
	<div class="container">
		<div class="featured-blog clearfix">
			 <div class="featured-blog-content">
				<h2 class="featured">About the Blog</h2>
				<p><?php echo stripslashes(get_option('woo_bio')); ?> <?php if (get_option('woo_about')) { ?> <a href="<?php echo get_option('woo_about'); ?>" title="Read more about me">Read more...</a> <?php } ?></p>
			</div>
			<div class="featured-links">
				<?php if ( get_option('woo_twitter') ) { ?>
				<a href="http://www.twitter.com/<?php echo get_option('woo_twitter'); ?>" class="twitter">
					<span class="medium block">Twitter</span>
					<span class="white extrasmall verdana">Stay updated on Twitter</span>
				</a>
				<?php } ?>
				<?php if ( get_option('woo_email') ) { ?>                
				<a href="mailto:<?php echo get_option('woo_email'); ?>" class="email">
					<span class="medium block">Email Me</span>
					<span class="white extrasmall verdana">Send me a Message</span>
				</a>
				<?php } ?>                
				<a href="<?php bloginfo('rss2_url'); ?>" class="rss-big">
					<span class="medium block">RSS Feed</span>
					<span class="white extrasmall verdana">Subscribe to Updates</span>
				</a>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>