<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?>
<div class="wrap">
	<?php $this->render_admin ('annoy'); ?>
	<?php screen_icon(); ?>
	<h2>
	<?php if (isset ($file)) :?>
		<?php _e ('Drain Hole | Statistics for', 'drain-hole'); ?> <a href="<?php echo $file->url_ref ($hole); ?>" title="<?php echo htmlspecialchars ($hole->directory.'/'.$file->file) ?>" onclick="return edit_file(<?php echo $file->id ?>)"><?php echo $file->file ?></a>
	<?php else : ?>
		<?php _e ('Drain Hole | Downloads', 'drain-hole'); ?>
	<?php endif; ?>

	</h2>
	
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
	
	<?php if (count ($stats) > 0) : ?>
	<table class="widefat post fixed">
		<thead>
			<tr>
				<th width="16" class="check-column">
					<input type="checkbox" name="select_all" value="" onclick="select_all (); return true"/>
				</th>
				<th><?php echo $pager->sortable ('created_at', __ ('Downloaded At', 'drain-hole')) ?></th>
				<?php if (!isset ($file)) : ?>
				<th><?php echo $pager->sortable ('file', __ ('File', 'drain-hole')) ?></th>
				<?php endif; ?>
				<th><?php echo $pager->sortable ('ip', __ ('IP', 'drain-hole')) ?></th>
				<th><?php echo $pager->sortable ('users', __ ('User', 'drain-hole')) ?></th>
				<th><?php echo $pager->sortable ('referrer', __ ('Referrer', 'drain-hole')); ?></th>
			</tr>
		</thead>
		
		<tbody>
		<?php foreach ($stats AS $pos => $stat) : ?>
		<tr id="stat_<?php echo $stat->id ?>"<?php if ($pos % 2 == 1) echo ' class="alt"' ?>>
			<td width="16" class="item center">
				<input type="checkbox" class="check" name="checkall[]" value="<?php echo $stat->id ?>"/>
			</td>
			<td><?php echo date ('H:i', $stat->created_at - (get_option ('gmt_offset') * 60 * 60))?> - <?php echo date (str_replace ('F', 'M', get_option ('date_format')), $stat->created_at); ?></td>
			<?php if (!isset ($file)) : ?>
			<td><?php echo htmlspecialchars ($stat->file); ?></td>
			<?php endif; ?>
			<td><a href="http://urbangiraffe.com/map/?ip=<?php echo $stat->ip ?>"><?php echo $stat->ip; ?></a></td>
			<td><?php echo $stat->user (); ?></td>
			<td><?php echo $stat->referrer_as_link () ?></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	
	<div id="loading" style="display: none">
		<img src="<?php echo $this->url () ?>/images/loading.gif" alt="loading" width="32" height="32"/>
	</div>
	<?php else : ?>
	<p><?php _e ('No files have been downloaded!', 'drain-hole'); ?></p>
	<?php endif; ?>
</div>

<div class="wrap">
	<h2><?php _e ('Clear all downloads', 'drain-hole'); ?></h2>
	<br/>
	<form action="<?php echo $this->url ($_SERVER['REQUEST_URI']) ?>" method="post" accept-charset="utf-8">
		<?php wp_nonce_field ('drainhole-clear_downloads'); ?>
		<input class="button-primary" type="submit" name="clear_downloads" value="<?php _e ('Clear Downloads', 'drain-hole'); ?>"/>
	</form>
</div>

<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function()
	{ 
		jQuery('#doaction2').click (function ()
		{
			if (jQuery('#action2_select').attr ('value') == 'delete')
				delete_items ('download','<?php echo wp_create_nonce ('drainhole-delete_items'); ?>');
			return false;
		});
	});
</script>