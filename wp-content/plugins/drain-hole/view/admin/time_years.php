<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?><?php _e ('Year', 'drain-hole'); ?>: 
<select name="year" id="years">
	<?php for ($x = $start; $x <= $end; $x++) : ?>
		<option value="<?php echo $x; ?>" <?php if ($x == $current) echo ' selected="selected"'; ?>><?php echo $x ?></option>
	<?php endfor; ?>
</select> 