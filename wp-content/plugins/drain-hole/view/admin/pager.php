<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?>
<div id="pager" class="pager">
		<input type="hidden" name="page" value="drain-hole.php"/>
		<?php if (isset ($_GET['files'])) : ?>
		<input type="hidden" name="files" value="<?php echo $_GET['files'] ?>"/>
		<?php elseif (isset ($_GET['stats'])) : ?>
		<input type="hidden" name="stats" value="<?php echo $_GET['stats'] ?>"/>
		<?php elseif (isset ($_GET['version'])) : ?>
		<input type="hidden" name="version" value="<?php echo $_GET['version'] ?>"/>
		<?php endif; ?>
		<input type="hidden" name="sub" value="<?php echo isset( $_GET['sub'] ) ? $_GET['sub'] : ''  ?>"/>
		<input type="hidden" name="curpage" value="<?php echo $pager->current_page () ?>"/>

		<p class="search-box">
			<label for="post-search-input" class="hidden"><?php _e ('Search') ?>:</label>
			<input class="search-input" type="text" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars ($_GET['search']) : '' ?>"/>
			<input class="button-secondary" type="submit" name="go" value="<?php _e ('Search', 'drain-hole') ?>"/>
		</p>
</div>
