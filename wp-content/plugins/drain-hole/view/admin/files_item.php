<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?>
<th width="16" class="item check-column">
	<input type="checkbox" class="check" name="checkall[]" value="<?php echo $file->id ?>"/>
</th>
<td align="center"><?php echo $file->id ?></td>
<td class="file-title">
	<a title="<?php _e ('Edit File'); ?>" href="<?php echo $file->url_ref ($hole); ?>" onclick="return edit_file(<?php echo $file->id ?>,this)"><?php echo $file->name () ?></a>
</td>
<td>
	<a href="<?php echo $this->base () ?>?page=drain-hole.php&amp;version=<?php echo $file->id ?>"><?php echo $file->version; ?></a>
</td>
<td align="center"><a href="<?php echo $this->base () ?>?page=drain-hole.php&amp;stats=<?php echo $file->id ?>"><?php echo $file->hits ?></a></td>
<td>
	<?php if ($file->exists ($hole)) echo date (get_option('date_format'), $file->updated_at); else echo '<span class="missing">'.__ ('File is missing','drain-hole').'</span>'; ?>
</td>
<td align="center">
	<a href="#newversion" title="<?php _e ('New version', 'drain-hole'); ?>" onclick="return new_version (<?php echo $file->id ?>,this)"><?php _e ('Branch', 'drain-hole') ?></a>
</td>
<td align="center">
	<a href="<?php echo $this->base () ?>?page=drain-hole.php&amp;source=file&amp;chart=<?php echo $file->id ?>"><?php _e ('View Charts', 'drain-hole') ?></a>
</td>
