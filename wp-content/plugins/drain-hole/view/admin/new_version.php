<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?>
<form method="post" action="<?php echo $this->url (); ?>/ajax.php?id=<?php echo $file->id ?>&amp;cmd=save_new_version&amp;_ajax_nonce=<?php echo wp_create_nonce ('drainhole-version_new')?>" id="version_form_<?php echo $file->id ?>">
	<table width="100%">
		<tr>
			<th align="right"><?php _e ('File', 'drain-hole'); ?>:</th>
			<td><code><?php echo $file->name () ?></code></td>
		</tr>
		<tr>
			<th align="right"><?php _e ('Current version', 'drain-hole'); ?>:</th>
			<td><input style="width: 95%" type="text" name="old_version" value="<?php echo $file->version ?>" readonly="readonly"/></td>
		</tr>
		<tr>
			<th align="right"><?php _e ('New version', 'drain-hole'); ?>:</th>
			<td><input tabindex="1" id="newversion" style="width: 95%" type="text" name="new_version" value="<?php echo $file->next_version () ?>"/></td>
		</tr>
		<tr>
			<th valign="top" align="right"><?php _e ('Version history', 'drain-hole'); ?>:</th>
			<td>
				<textarea tabindex="2" style="width: 95%" name="reason" rows="3"></textarea>
			</td>
		</tr>
		<?php if ($file->svn) :?>
			<tr>
				<th align="right"><?php _e ('SVN Update', 'drain-hole'); ?>:</th>
				<td>
					<input tabindex="3" type="checkbox" name="svn"/>
					<span class="sub"><?php _e ('Update the download from SVN (using version info if applicable)', 'drain-hole'); ?></span>
				</td>
			</tr>
			<tr>
				<th align="right"><?php _e ('Don\'t branch', 'drain-hole'); ?>:</th>
				<td>
					<input tabindex="3" type="checkbox" name="donotbranch"/>
					<span class="sub"><?php _e ('Just update current version', 'drain-hole'); ?></span>
				</td>
			</tr>
		<?php endif; ?>
		<tr>
			<th align="right"><?php _e ('Keep previous', 'drain-hole'); ?>:</th>
			<td>
				<input tabindex="3" type="checkbox" name="branch"/>
				<span class="sub"><?php _e ('Selecting this will retain the previous version', 'drain-hole'); ?></span>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input class="button-primary" tabindex="4" type="submit" name="save" value="<?php _e ('Save', 'drain-hole'); ?>"/>
				<input class="button-secondary" tabindex="5" type="submit" name="cancel" value="<?php _e ('Cancel', 'drain-hole'); ?>" onclick="jQuery('#dialog').dialog ('close'); return false"/>
			</td>
		</tr>
	</table>
</form>

<script type="text/javascript" charset="utf-8">
	 jQuery('#version_form_<?php echo $file->id ?>').ajaxForm ( { beforeSubmit: function ()
			{
				jQuery('#dialog').html (jQuery('#loadingit').html ());
			},
			success: function ()
			{
				jQuery('#dialog').dialog ('close');
				jQuery('#file_<?php echo $file->id ?>').load ('<?php echo $this->url (); ?>/ajax.php?id=<?php echo $file->id ?>&cmd=show_file');
			}});
</script>