<?php
if(is_home()) : ?>
<div class="container"> 
	<div id="last-child-container" class="left">	
		
		<?php SWPMOutput(1, 3,'News', 1); ?>
			
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