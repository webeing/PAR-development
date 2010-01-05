<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?>
<div style="float: right; width: 200px; margin: 10px; text-align: center">
	<p>$icon$</p>

	<table class="download">
		<tr>
			<th><?php _e ('Download', 'drain-hole'); ?>:</th>
			<td>$url$</td>
		</tr>
		<tr>
			<th><?php _e ('Version', 'drain-hole'); ?>:</th>
			<td>$version$</td>
		</tr>
		<tr>
			<th><?php _e ('Updated', 'drain-hole'); ?>:</th>
			<td>$updated$</td>
		</tr>
		<tr>
			<th><?php _e ('Size', 'drain-hole'); ?>:</th>
			<td>$size$</td>
		</tr>
	</table>
	
	<?php	$options = get_option ('drainhole_options');
			if (!$options || !isset ($options['kitten']) || $options['kitten'] == false)
				_e ('<br/><small>Powered by <a href="http://urbangiraffe.com/plugins/drain-hole/">Drain Hole</a></small>', 'drain-hole');
			?>
</div>
