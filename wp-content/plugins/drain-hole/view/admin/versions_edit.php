<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?>
<form method="post" action="<?php echo $this->url (); ?>/ajax.php?id=<?php echo $version->id ?>&amp;cmd=save_version&amp;_ajax_nonce=<?php echo wp_create_nonce ('drainhole-version_save')?>" id="version_form_<?php echo $version->id ?>">
	<table width="100%">
		<tr>
			<th align="right" width="80"><?php _e ('Version', 'drain-hole'); ?>:</th>
			<td><input style="width: 35%" type="text" name="version" value="<?php echo htmlspecialchars ($version->version) ?>"/></td>
		</tr>
		<tr>
			<th align="right" width="80"><?php _e ('Hits', 'drain-hole'); ?>:</th>
			<td><input style="width: 35%" type="text" name="hits" value="<?php echo $version->hits ?>"/></td>
		</tr>
		<tr>
			<th align="right"><?php _e ('Date', 'drain-hole'); ?>:</th>
			<td>
				<input size="2" type="text" name="day" value="<?php echo date ('j', $version->created_at) ?>"/> /
				<input size="2" type="text" name="month" value="<?php echo date ('n', $version->created_at) ?>"/> /
				<input size="4" type="text" name="year" value="<?php echo date ('Y', $version->created_at) ?>"/> (D/M/Y)
			</td>
		</tr>
		<tr>
			<th valign="top" align="right" width="80"><?php _e ('Reason', 'drain-hole'); ?>:</th>
			<td><textarea name="reason" style="width: 95%"><?php echo htmlspecialchars ($version->reason); ?></textarea></td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input class="button-primary" type="submit" name="save" value="<?php _e ('Save', 'drain-hole'); ?>"/>
				<input class="button-secondary" type="submit" name="cancel" value="<?php _e ('Cancel', 'drain-hole'); ?>" onclick="jQuery('#dialog').dialog ('close'); return false"/></td>
		</tr>
	</table>
</form>

<script type="text/javascript" charset="utf-8">
	 jQuery('#version_form_<?php echo $version->id ?>').ajaxForm ( { beforeSubmit: function ()
			{
				jQuery('#dialog').html (jQuery('#loadingit').html ());
			},
			success: function ()
			{
				jQuery('#dialog').dialog ('close');
				jQuery('#version_<?php echo $version->id ?>').load ('<?php echo $this->url (); ?>/ajax.php?id=<?php echo $version->id ?>&cmd=show_version');
			}});
</script>