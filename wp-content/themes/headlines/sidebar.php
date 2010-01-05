<div id="sidebar" class="col-<?php if ( get_option('woo_left_sidebar') == "true" ) echo 'left'; else echo 'right'; ?>">

	<!-- Widgetized Sidebar -->	
	<?php dynamic_sidebar('sidebar-top'); ?>		
    
	<!-- TABS STARTS --> 
	<?php if (get_option('woo_tabs') == "true") { ?>
	<div id="tabs">
		
		<ul class="wooTabs tabs">
			<li><a href="#pop"><?php _e('Popular', 'woothemes'); ?></a></li>
			<li><a href="#feat"><?php _e('Latest', 'woothemes'); ?></a></li>
            <li><a href="#comm"><?php _e('Comments', 'woothemes'); ?></a></li>
			<li><a href="#tagcloud"><?php _e('Tags', 'woothemes'); ?></a></li>
		</ul>	
		
		<div class="fix"></div>
		
		<div class="inside">
		 <div id="pop">
			<ul>
			<?php include(TEMPLATEPATH . '/includes/popular.php' ); ?>                    
			</ul>
           </div>
           
         <div id="feat"> 
	        <ul>
			<?php include(TEMPLATEPATH . '/includes/latest.php' ); ?>                    
			</ul>
          </div>
          <div id="comm">  
			<ul>
			<?php include(TEMPLATEPATH . '/includes/comments.php' ); ?>                    
			</ul>
	      </div>
			<div id="tagcloud">
			    <?php wp_tag_cloud('smallest=12&largest=20'); ?>
			</div>
		
	</div><!-- INSIDE END -->
	
	</div><!-- TABS END -->
	
	<div class="fix" style="height:25px !important;"></div>
	
	<?php } ?>  
	<!-- TABS END -->    
    
	<!-- Widgetized Sidebar -->	
	<?php dynamic_sidebar('sidebar'); ?>		
             
	
</div><!-- /#sidebar -->