<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?>
<form method="post" action="<?php echo $this->url (); ?>/ajax.php?id=<?php echo $hole->id ?>&amp;cmd=save_hole&amp;_ajax_nonce=<?php echo wp_create_nonce ('drainhole-save_hole')?>" id="hole_form_<?php echo $hole->id ?>">
	<table width="100%">
		<tr>
		  <th valign="top" align="right" width="120"><?php _e ('URL', 'drain-hole') ?>:<br/><span class="sub"><?php _e ('Relative to site root', 'drain-hole') ?></span></th>
		  <td><input style="width: 95%" type="text" name="urlx" value="<?php echo htmlspecialchars ($hole->url); ?>"/></td>
		</tr>
		<tr>
		  <th valign="top" align="right" ><?php _e ('Directory', 'drain-hole') ?>:<br/><span class="sub"><?php _e ('Relative to root', 'drain-hole') ?></span></th>
		  <td><input style="width: 95%" type="text" name="directoryx" value="<?php echo htmlspecialchars ($hole->directory); ?>"/></td>
		</tr>
		<tr>
		  <th valign="top" align="right" ><?php _e ('Access Level', 'drain-hole') ?>:<br/><span class="sub"><?php _e ('File security', 'drain-hole') ?></span></th>
		  <td valign="top" >
		  	<select name="role">
					<option value="-"><?php _e ('Anybody - no login required', 'drain-hole'); ?></option>
					<?php if (class_exists ('WPShopper')) : ?>
					<option value="paid"<?php if ($role == 'paid') echo ' selected="selected"' ?>><?php _e ('Purchased - via WP Shopper', 'drain-hole'); ?></option>
					<?php endif; ?>

					<?php global $wp_roles; foreach ($wp_roles->role_names as $key => $rolename) : ?>
						<option value="<?php echo $key ?>"<?php if ($hole->role == $key) echo ' selected="selected"'; ?>><?php echo $rolename ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>

		<tr>
		  <th valign="top" align="right" ><?php _e ('Access error URL', 'drain-hole') ?>:<br/><span class="sub"><?php _e ('Redirect on access error', 'drain-hole'); ?></span></th>
		  <td valign="top" >
				<input type="text" size="40" name="redirect_urlx" value="<?php echo htmlspecialchars ($hole->role_error_url) ?>"/>
		  </td>
		</tr>
		<tr>
			<th align="right"><label for="hotlink"><?php _e ('Stop hot-links', 'drain-hole'); ?>:</label></th>
			<td>
				<input type="checkbox" name="hotlink" id="hotlink"<?php if ($hole->hotlink) echo ' checked="checked"' ?>/>
			</td>
		</tr>
		<th></th>
			<td>
				<input class="button-primary" type="submit" name="save" value="<?php _e ('Save', 'drain-hole'); ?>"/>
				<input class="button-secondary" type="submit" name="cancel" value="<?php _e ('Cancel', 'drain-hole'); ?>" onclick="jQuery('#dialog').dialog ('close'); return false"/>
			</td>
		</tr>
	</table>
</form>

<script type="text/javascript" charset="utf-8">
	 jQuery('#hole_form_<?php echo $hole->id ?>').ajaxForm ( { beforeSubmit: function ()
			{
				jQuery('#dialog').html (jQuery('#loadingit').html ());
			},
			success: function ()
			{
				jQuery('#dialog').dialog ('close');
				jQuery('#hole_<?php echo $hole->id ?>').load ('<?php echo $this->url (); ?>/ajax.php?id=<?php echo $hole->id ?>&cmd=show_hole');
			}});
</script>