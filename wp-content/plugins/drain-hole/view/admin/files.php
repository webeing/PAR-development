<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?>
<div class="wrap">
	<?php $this->render_admin ('annoy'); ?>
	<?php screen_icon(); ?>
	<h2><?php _e ('Drain Hole | Files for', 'drain-hole'); ?> <?php echo $hole->url; ?> <a href="<?php echo $this->url () ?>/csv.php?id=<?php echo $hole->id ?>&amp;type=files" title="Download as CSV"><img src="<?php echo $this->url () ?>/images/csv.png" width="16" height="16" alt="CSV"/></a></h2>
	
	<?php $this->submenu (true); ?>
	
	<p style="clear: both"><?php _e ('Files are stored in', 'drain-hole'); ?> <code><?php echo $hole->directory; ?></code></p>
	
	<form method="get" action="<?php echo $this->url ($pager->url) ?>">
		<?php $this->render_admin ('pager', array ('pager' => $pager)); ?>
	
		<div id="pager" class="tablenav">
			<div class="alignleft actions">
				<select name="action2" id="action2_select">
					<option value="-1" selected="selected"><?php _e('Bulk Actions'); ?></option>
					<option value="delete"><?php _e('Delete'); ?></option>
				</select>
				
				<input type="submit" value="<?php _e('Apply'); ?>" name="doaction2" id="doaction2" class="button-secondary action" />
				
				<?php $pager->per_page ('drain-hole'); ?>

				<input type="submit" value="<?php _e('Filter'); ?>" class="button-secondary" />

				<br class="clear" />
			</div>
		
			<div class="tablenav-pages">
				<?php echo $pager->page_links (); ?>
			</div>
		</div>
	</form>
	
	<?php if (count ($files) > 0) : ?>
	<table class="widefat post fixed">
		<thead>
		<tr>
			<th width="16" class="check-column">
				<input type="checkbox" name="select_all" value="" onclick="select_all (); return true"/>
			</th>
			<th class="center"><?php echo $pager->sortable ('id', __ ('ID', 'drain-hole')) ?></th>
			<th class="file-title"><?php echo $pager->sortable ('file', __ ('File', 'drain-hole')) ?></th>
			<th align="left"><?php echo $pager->sortable ('version', __ ('Version', 'drain-hole')) ?></th>
			<th class="center"><?php echo $pager->sortable ('hits', __ ('Hits', 'drain-hole')) ?></th>
			<th align="left"><?php echo $pager->sortable ('updated_at', __ ('Updated', 'drain-hole')) ?></th>
			<th class="center"><img src="<?php echo $this->url () ?>/images/add.png" width="16" height="16" alt="Add"/></th>
			<th class="center"><img src="<?php echo $this->url () ?>/images/chart.png" width="16" height="16" alt="Chart"/></th>
		</tr>
		</thead>
		
		<?php foreach ($files AS $pos => $file) : ?>
			<tr id="file_<?php echo $file->id ?>" class="<?php if ($pos % 2 == 1) echo 'alt' ?><?php if (!$file->exists ($hole)) echo ' missing' ?>">
				<?php $this->render_admin ('files_item', array ('file' => $file, 'hole' => $hole)); ?>
			</tr>
		<?php endforeach; ?>
	</table>
	
	<div id="loading" style="display: none">
		<img src="<?php echo $this->url () ?>/images/loading.gif" alt="loading" width="32" height="32"/>
	</div>
	
	<?php endif;?>
	<div id="dialog"></div>
	<?php $this->render_admin ('loading')?>
</div>

<div class="wrap">
	<h2><?php _e ('Add A File', 'drain-hole'); ?></h2>
	
	<p><?php _e ('New files can be added by uploading here (if the directory has appropriate write-permissions), or by uploading with an FTP client and \'scanning\' the directory for changes.', 'drain-hole'); ?></p>

	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
		<?php wp_nonce_field ('drainhole-add_file'); ?>
		<table class="form-table">
			<tr>
				<th><?php _e ('New filename', 'drain-hole'); ?>:</th>
				<td><input class="regular-text" size="40" type="text" name="filename" value=""/> <span class="sub"><?php _e ('Optional, uploaded filename will be used if not given', 'drain-hole'); ?></span></td>
			</tr>
			
			<?php if ($hole->can_write ()) : ?>
			<tr>
				<th><?php _e ('Upload a file', 'drain-hole'); ?>:</th>
				<td>
					<input class="regular-text" size="40" type="file" name="file"/> <span class="sub">
				</td>
			</tr>
			<?php endif; ?>
		
			<tr>
				<td></td>
				<td>
					<input class="button-primary" type="submit" name="upload" value="<?php _e ('Create &amp; Upload', 'drain-hole'); ?>"/>
					<input class="button-secondary" type="submit" name="rescan" value="<?php _e ('Re-scan', 'drain-hole'); ?>" id="rescan"/>
				</td>
			</tr>
		</table>
	</form>
</div>

<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function()
	{ 
		jQuery('#doaction2').click (function ()
		{
			if (jQuery('#action2_select').attr ('value') == 'delete')
				delete_items ('file','<?php echo wp_create_nonce ('drainhole-delete_items'); ?>');
			return false;
		});
	});
</script>