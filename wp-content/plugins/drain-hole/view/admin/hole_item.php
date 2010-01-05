<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?>
<th class="item check-column" scope="row">
	<input type="checkbox" class="check" name="checkall[]" value="<?php echo $hole->id ?>"/>
</th>
<td align="center" valign="top"><?php echo $hole->id ?></td>
<td class="hole-title" id="hole_item_<?php echo $hole->id ?>">
	<a title="<?php _e ('View files', 'drain-hole'); ?>" href="<?php echo $this->base () ?>?page=drain-hole.php&amp;files=<?php echo $hole->id ?>"><?php echo $hole->url ?></a>
	
	<?php if ($hole->files > 0) : ?>
	<span class="sub">(<?php printf (@__ngettext ('%d file', '%d files', $hole->files, 'drain-hole'), number_format ($hole->files)); ?>)</span>
	<?php endif; ?>
</td>
<td align="center"><a href="<?php echo $this->base () ?>?page=drain-hole.php&amp;sub=downloads&amp;hole=<?php echo $hole->id ?>"><?php echo number_format ($hole->hits); ?></a></td>

<td align="center">
	<a title="<?php _e ('Edit Drain Hole', 'drain-hole'); ?>" href="#" onclick="edit_hole(this,<?php echo $hole->id ?>);return false;"><?php _e ('Edit Hole', 'drain-hole'); ?></a>
</td>

<td align="center">
	<a title="<?php _e ('View files', 'drain-hole'); ?>" href="<?php echo $this->base () ?>?page=drain-hole.php&amp;files=<?php echo $hole->id ?>"><?php _e ('View Files', 'drain-hole'); ?></a>
</td>

<td align="center">
	<a href="<?php echo $this->base () ?>?page=drain-hole.php&amp;source=hole&amp;chart=<?php echo $hole->id ?>"><?php _e ('View Chart', 'drain-hole'); ?></a>
</td>
