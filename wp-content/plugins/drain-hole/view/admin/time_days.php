<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?><?php _e ('Day', 'drain-hole'); ?>: 
<select name="day" id="days">
	<?php for ($x = 1; $x <= 31; $x++) : ?>
		<option value="<?php echo $x; ?>" <?php if ($x == $current) echo ' selected="selected"'; ?>><?php echo $x ?></option>
	<?php endfor; ?>
</select> 