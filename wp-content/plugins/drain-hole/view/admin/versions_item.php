<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?>
<td width="16" class="item center">
	<?php if ($version->id != $file->version_id) : ?>
		<input type="checkbox" class="check" name="checkall[]" value="<?php echo $version->id ?>"/>
	<?php endif; ?>
</td>
<td align="center"><?php echo $version->id?></td>
<td align="center"><?php echo $version->version?></td>
<td align="center"><?php echo $version->hits?></td>
<td align="center"><?php echo date (get_option ('date_format'), $version->created_at); ?></td>
<td><?php echo htmlspecialchars ($version->reason); ?></td>
<td align="center">
	<?php if ($file->has_version ($version->id, $hole)) : ?>
		<?php _e ('Yes', 'drain-hole'); ?>
	<?php else : ?>
		<?php _e ('No', 'drain-hole'); ?>
	<?php endif; ?>
</td>
<td align="center">
	<a href="#" title="<?php _e ('Edit version'); ?>" onclick="return edit_version(<?php echo $version->id ?>,this)"><?php _e ('Edit', 'drain-hole'); ?></a>
</td>
