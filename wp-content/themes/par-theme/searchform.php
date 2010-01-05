<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/" class="search-form">
	<input type="text" value="Enter search keyword" name="s" id="s" onclick="this.value='';" class="search-box" />
	<input type="image" src="<?php bloginfo('template_directory'); ?>/styles/<?php echo $style_path; ?>/search.gif" value="Search" class="search-button"  />
</form>
