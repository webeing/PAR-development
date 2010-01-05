<?php
if(is_home()) : ?>
<div class="container"> 
	<div id="last-child-container" class="left">	
		<div class="box left">
			<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">Last Article Segment 1</a></h3>
			<div class="last-child-thumb">
				<img src="<?php bloginfo('template_url');?>/images/flickr.gif" alt="<?php the_title();?>" />
			</div>
				<div class="entry">
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed nec risus gravida lacus adipiscing rhoncus. Proin tincidunt, ipsum vel volutpat fringilla, metus massa ornare est, in sagittis risus mi at lectus. Maecenas vel nibh urna, a venenatis odio. Pellentesque sit amet neque sem. Donec tincidunt dictum dolor ac placerat.					
				</div>	
		</div> <!-- /.children -->
		
		<div class="box left">
			<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">Last Article Segment 2</a></h3>
			<div class="last-child-thumb">
				<img src="<?php bloginfo('template_url');?>/images/flickr.gif" alt="<?php the_title();?>" />
			</div>
				<div class="entry">
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed nec risus gravida lacus adipiscing rhoncus. Proin tincidunt, ipsum vel volutpat fringilla, metus massa ornare est, in sagittis risus mi at lectus. Maecenas vel nibh urna, a venenatis odio. Pellentesque sit amet neque sem. Donec tincidunt dictum dolor ac placerat.					
				</div>		
		</div> <!-- /.children -->
		
		<div class="box left">
			<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">Last Article Segment 3</a></h3>
			<div class="last-child-thumb">
				<img src="<?php bloginfo('template_url');?>/images/flickr.gif" alt="<?php the_title();?>" />
			</div>
				<div class="entry">
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed nec risus gravida lacus adipiscing rhoncus. Proin tincidunt, ipsum vel volutpat fringilla, metus massa ornare est, in sagittis risus mi at lectus. Maecenas vel nibh urna, a venenatis odio. Pellentesque sit amet neque sem. Donec tincidunt dictum dolor ac placerat.					
				</div>		
		</div> <!-- /.children -->		
	</div> <!-- /#last-child-container -->
	
	<div id="social-account" class="box left">
		<h4>Newsletter</h3>	
		<form class="newsletter" action="" id="newsletter" method="get">
			<input type="text" class="nl-box" onclick="this.value='';" id="nl" name="nl" value="Enter your e-mail"/>
			<input type="image" class="nl-btn" value="" src="<?php bloginfo('template_url');?>/images/news-letter.gif"/>
		</form>
		<h4>Follow us on</h4>
		<ul class="account">
			<li><a href="#" title="social account"><img src="<?php bloginfo('template_url');?>/images/feed.gif" alt="social account" /></a></li>
			<li><a href="#" title="social account"><img src="<?php bloginfo('template_url');?>/images/feed.gif" alt="social account" /></a></li>
			<li><a href="#" title="social account"><img src="<?php bloginfo('template_url');?>/images/feed.gif" alt="social account" /></a></li>
			<li><a href="#" title="social account"><img src="<?php bloginfo('template_url');?>/images/feed.gif" alt="social account" /></a></li>
		</ul>
	</div>
</div>
<br class="clear"/>

<?php endif; ?>  