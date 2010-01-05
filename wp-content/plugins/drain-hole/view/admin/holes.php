<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?>
<div class="wrap">
	<?php $this->render_admin ('annoy'); ?>
	<?php screen_icon(); ?>
	
	<h2><?php _e ('Drain Hole | Holes', 'drain-hole') ?></h2>
	
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
	
	<?php if (count ($holes) > 0) : ?>
	<table class="widefat post fixed">
		<thead>
			<tr>
				<th class="manage-column check-column" scope="col">
					<input type="checkbox" name="select_all" value="" onclick="select_all (); return true"/>
				</th>
				<th class="center"><?php echo $pager->sortable ('id', 'ID') ?></th>
				<th class="hole-title"><?php echo $pager->sortable ('name', 'Name') ?></th>
				<th class="center"><?php echo $pager->sortable ('hits', 'Hits') ?></th>
				<th class="center"><img src="<?php echo $this->url (); ?>/images/edit.png" width="16" height="16" alt="Edit"/></th>
				<th class="center"><img src="<?php echo $this->url (); ?>/images/files.png" width="16" height="16" alt="Edit"/></th>
				<th class="center"><img src="<?php echo $this->url (); ?>/images/chart.png" width="16" height="16" alt="Edit"/></th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach ($holes AS $pos => $hole) : ?>
				<tr id="hole_<?php echo $hole->id ?>"<?php if ($pos % 2 == 1) echo ' class="alt"'?>>
					<?php $this->render_admin ('hole_item', array ('hole' => $hole)); ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
	
	<div id="loading" style="display: none">
		<img src="<?php echo $this->url () ?>/images/loading.gif" alt="loading" width="32" height="32"/>
	</div>
</div>
<br/>
<div class="wrap">
	<h2><?php _e ('New Drain Hole', 'drain-hole'); ?></h2>
	
	<p><?php _e ('A drain hole maps a URL path to a directory on your server.  Files placed within the directory are available under your chosen URL path.', 'drain-hole'); ?></p>

	<form method="post" action="<?php echo $this->url ($_SERVER['REQUEST_URI']) ?>">
		<?php wp_nonce_field ('drainhole-new_hole'); ?>
		<table width="100%" class="form-table">
			<tr>
			  <th valign="top" align="right" width="120"><?php _e ('URL', 'drain-hole') ?>:</th>
			  <td><input class="regular-text" style="width: 95%" type="text" name="urlx" id="urlx" value="<?php echo htmlspecialchars ($base_url); ?>"/></td>
			</tr>
			<tr>
			  <th valign="top" align="right" ><?php _e ('Directory', 'drain-hole') ?>:<br/><span class="sub"><?php _e ('Relative to root', 'drain-hole') ?></span></th>
			  <td><input class="regular-text" style="width: 95%" type="text" id="directoryx" name="directoryx" value="<?php echo htmlspecialchars ($base_directory); ?>"/>

				</td>
			</tr>
			<tr>
				<th></th>
				<td><input class="button-primary" type="submit" name="create" value="<?php _e ('Create Drain Hole', 'drain-hole'); ?>" id="create"/></td>
			</tr>
		</table>
		
		<table class="example">
			<tr>
				<th>URL</th>
				<td><strong id="base_url"><?php echo htmlspecialchars ($base_url) ?></strong>/example.zip</td>
			</tr>
			<tr>
				<th><?php _e ('Directory', 'drain-hole'); ?></th>
				<td><strong id="base_dir"><?php echo htmlspecialchars ($base_directory); ?></strong>example.zip</td>
			</tr>
		</table>

		<br/>
		<div class="errorx" style="display: none" id="error_dir">
			<p><?php _e ('<p>Your chosen <strong>directory</strong> is within a publicly accessible web directory.  Drain Hole <strong>will not be able to control access</strong> to files placed here unless a <code>.htaccess</code> file is placed in the directory.  Drain Hole will attempt to do this for you, but may not have permission to do so.  If this is the case then you will need to create this file yourself (<a href="#" onclick="jQuery(\'#htaccess\').toggle (); return false">click to view</a>)</p>', 'drain-hole'); ?></p>
		</div>
		
		<br/>
		<div class="errorx" style="display: none" id="error_url">
			<p><?php _e ('<p>Your chosen <strong>URL</strong> is outside of your WordPress site and as such Drain Hole <strong>may not be able to control access</strong> to files unless a <code>.htaccess</code> file is placed in the directory.  Drain Hole will attempt to do this for you, but may not have permission to do so.  If this is the case then you will need to create this file yourself (<a href="#" onclick="jQuery(\'#htaccess\').toggle (); return false">click to view</a>)</p>','drain-hole'); ?></p>
		</div>
		
		<div class="updatedx" id="htaccess" style="display: none">
			<p><?php _e ('The following should be created in the directory given above:', 'drain-hole'); ?></p>
			<pre style="margin: 0px">
			<?php $this->render_admin ('htaccess', array ('index' => DH_Plugin::realpath (ABSPATH).'/index.php'))?>
			</pre>
		</div>

		<div id="dialog"></div>
		
		<?php $this->render_admin ('loading')?>
		
		<script type="text/javascript" charset="utf-8">
			var wp_dh_base_url  = '<?php echo htmlspecialchars ($base_url) ?>';
			var wp_dh_home_url  = '<?php echo htmlspecialchars ($home); ?>';
			var wp_dh_base_dir  = '<?php echo htmlspecialchars ($base_directory); ?>';
			var wp_dh_base_home = '<?php echo htmlspecialchars ($_SERVER['DOCUMENT_ROOT']) ?>';
			
			jQuery(document).ready(function()
			{ 
				jQuery('#urlx').keyup (urlKey);
				jQuery('#directoryx').keyup (dirKey);

				update_url_warning (wp_dh_base_url);
				update_dir_warning (wp_dh_base_dir);
				
				jQuery('#doaction2').click (function ()
				{
					if (jQuery('#action2_select').attr ('value') == 'delete')
						delete_items ('hole','<?php echo wp_create_nonce ('drainhole-delete_items'); ?>');
					return false;
				});
		 	});
		</script>
	</form>
</div>
