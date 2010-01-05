<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?><div class="wrap">
	<?php $this->render_admin ('annoy'); ?>
	<?php screen_icon(); ?>
	
	<h2><?php printf (__ ('Version history for %s', 'drain-hole'), '<a href="edit.php?page=drain-hole.php&files='.$file->hole_id.'">'.$file->name ().'</a>'); ?></h2>

	<?php $this->submenu (true); ?>
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
	
	<?php if (count ($versions) > 0) : ?>
		<table class="widefat post fixed">
			<thead>
			<tr>
				<th width="16" class="check-column">
					<input type="checkbox" name="select_all" value="" onclick="select_all (); return true"/>
				</th>
				<th class="center"><?php echo $pager->sortable ('id', __ ('ID', 'drain-hole')) ?></th>
				<th class="center"><?php echo $pager->sortable ('version', __ ('Version', 'drain-hole')) ?></th>
				<th class="center"><?php echo $pager->sortable ('hits', __ ('Hits', 'drain-hole')) ?></th>
				<th class="center"><?php echo $pager->sortable ('created_at', __ ('Created', 'drain-hole')) ?></th>
				<th align="left"><?php echo $pager->sortable ('reason', __ ('Reason for version', 'drain-hole')) ?></th>
				<th class="center"><?php _e ('File', 'drain-hole'); ?></th>
				<th class="center"><img src="<?php echo $this->url () ?>/images/edit.png" width="16" height="16" alt="Edit"/></th>
			</tr>
			</thead>

			<tbody>
			<?php foreach ($versions as $pos => $version): ?>
				<tr id="version_<?php echo $version->id ?>"<?php if ($pos % 2 == 1) echo ' class="alt"' ?>>
					<?php $this->render_admin ('versions_item', array ('version' => $version, 'file' => $file, 'hole' => $hole)); ?>
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>
	<?php else : ?>
		<p><?php _e ('There are no versions to display!', 'drain-hole'); ?></p>
	<?php endif; ?>
	
	<div id="dialog"></div>
	
	<?php $this->render_admin ('loading')?>
</div>

<div class="wrap">
	<h2><?php _e ('Add to history', 'drain-hole'); ?></h2>
	<p><?php _e ('You can add to the history of a file.  This allows you to include some history of a file before Drain Hole was used', 'drain-hole'); ?></p>
	
	<form action="<?php echo $this->url ($_SERVER['REQUEST_URI']) ?>" method="post" accept-charset="utf-8">
		<?php wp_nonce_field ('drainhole-add_version'); ?>
		
		<table class="form-table" width="100%">
			<tr>
				<th align="right" width="80"><?php _e ('Version', 'drain-hole'); ?>:</th>
				<td><input class="regular-text" style="width: 35%" type="text" name="version" value=""/></td>
			</tr>
			<tr>
				<th valign="top" align="right" width="80"><?php _e ('Reason', 'drain-hole'); ?>:</th>
				<td><textarea name="reason" style="width: 95%"></textarea></td>
			</tr>
			<tr>
				<th align="right"><?php _e ('Date', 'drain-hole'); ?>:</th>
				<td>
					<input size="2" type="text" name="day" value="<?php echo date ('j') ?>"/> /
					<input size="2" type="text" name="month" value="<?php echo date ('n') ?>"/> /
					<input size="4" type="text" name="year" value="<?php echo date ('Y') ?>"/> <?php _e ('(D/M/Y)', 'drain-hole'); ?>
				</td>
			</tr>
			<tr>
				<th></th>
				<td><input class="button-primary" type="submit" name="save" value="<?php _e ('Save', 'drain-hole'); ?>"/></td>
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
				delete_items ('version','<?php echo wp_create_nonce ('drainhole-delete_items'); ?>');
			return false;
		});
	});
</script>