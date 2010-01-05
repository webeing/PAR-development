<div class="advert_200x200 alignright">

	<?php if (get_option('woo_ad_content_adsense') <> "") { echo stripslashes(get_option('woo_ad_content_adsense')); ?>
	
	<?php } else { ?>
	
		<a href="<?php echo get_option('woo_ad_content_url'); ?>"><img src="<?php echo get_option('woo_ad_content_image'); ?>" width="200" height="200" alt="advert" /></a>
		
	<?php } ?>	

</div>