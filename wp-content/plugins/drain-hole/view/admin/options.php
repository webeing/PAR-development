<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?>
<div class="wrap">
	<?php $this->render_admin ('annoy'); ?>
	<?php screen_icon(); ?>
	<h2><?php _e ('Drain Hole | Options', 'drain-hole'); ?></h2>
	
	<?php $this->submenu (true); ?>
	
	<form style="clear: both" action="<?php echo $this->url ($_SERVER['REQUEST_URI']) ?>" method="post" accept-charset="utf-8">
	<?php wp_nonce_field ('drainhole-save_options'); ?>
	<table width="100%" class="form-table">

		<tr>
			<th width="220" align="right"><?php _e ('Statistic retention', 'drain-hole'); ?>:<br/>
				<span class="sub"><?php _e ('How many days to keep access statistics for', 'drain-hole'); ?></span></th>
			<td><input type="text" size="5" name="days" value="<?php echo $options['days'] ?>"/> 
				<span class="sub"><?php _e ('Enter 0 for no limit.  File hits are not cleared', 'drain-hole'); ?></span></td>
		</tr>

		<tr>
			<th width="220" align="right"><?php _e ('SVN path', 'drain-hole'); ?>:</th>
			<td>
				<input class="regular-text" size="50" type="text" name="svn" value="<?php echo $options['svn'] ?>"/>
			</td>
		</tr>
		<tr>
			<th width="220" align="right"><?php _e ('Issue tracker URL', 'drain-hole'); ?>:</th>
			<td>
				<input class="regular-text" size="50" type="text" name="tracker" value="<?php echo $options['tracker'] ?>"/>
			</td>
		</tr>
		<tr>
			<th width="220" align="right"><?php _e ('Default version no.', 'drain-hole'); ?>:</th>
			<td>
				<input class="regular-text" size="50" type="text" name="default_version" value="<?php echo $options['default_version'] ?>"/>
			</td>
		</tr>
		<tr>
			<th width="220" align="right"><?php _e ('Default file name', 'drain-hole'); ?>:</th>
			<td>
				<input class="regular-text" size="50" type="text" name="default_name" value="<?php echo $options['default_name'] ?>"/>
				<span class="sub"><?php _e ('<code>$FILENAME$</code> and <code>$EXTENSION$</code> will be replaced by the file\'s real name and extension', 'drain-hole'); ?></span>
			</td>
		</tr>
		<tr>
			<th width="220" align="right"><label for="google"><?php _e ('Google Analytics tracking', 'drain-hole'); ?></label>:<br/>
				<span class="sub"><?php _e ('Add code to track downloads', 'drain-hole'); ?></span></th>
			<td><input type="checkbox" name="google"<?php if ($options['google'] == true) echo ' checked="checked"' ?> id="google"/></td>
		</tr>
		<tr>
			<th width="220" align="right" valign="top"><?php _e ('Allow file deletion', 'drain-hole'); ?>:</th>
			<td>
				<input type="checkbox" name="delete_file"<?php $this->checked ($options, 'delete_file') ?>/>
				<span class="sub"><?php _e ('Enabling this will allow Drain Hole to delete physical files', 'drain-hole'); ?></span>
			</td>
		</tr>
		<tr>
			<th width="220" align="right" valign="top"><?php _e ('Create .htaccess in holes', 'drain-hole'); ?>:</th>
			<td>
				<input type="checkbox" name="htaccess"<?php $this->checked ($options, 'htaccess') ?>/>
				<span class="sub"><?php _e ('Enabling this will allow Drain Hole to create .htaccess in holes for further protection', 'drain-hole'); ?></span>
			</td>
		</tr>
		<tr>
			<th width="220" align="right" valign="top"><?php _e ('Support', 'drain-hole'); ?>:</th>
			<td>
				<input type="checkbox" name="support"<?php $this->checked ($options, 'support') ?>/>
				<span class="sub"><?php _e ('I hereby testify that I have supported this plugin.  If I check this option and haven\'t supported this plugin then a squad of winged monkeys will be sent to drop things on my head.', 'drain-hole'); ?></span>
			</td>
		</tr>
		<tr>
			<th width="220" align="right"></th>
			<td><input class="button-primary" type="submit" name="options" value="<?php _e ('Save options', 'drain-hole'); ?>"/></td>
		</tr>
	</table>

		</form>
</div>

<div class="wrap">
	<h2><?php _e ('Delete Drain Hole', 'drain-hole'); ?></h2>
	
	<p><?php _e ('This operation removes all data associated with Drain Hole and disables the plugin.  It does not delete any files', 'drain-hole'); ?></p>
	
	<form action="<?php echo $this->url ($_SERVER['REQUEST_URI']) ?>" method="post" accept-charset="utf-8">
		<?php wp_nonce_field ('drainhole-delete_plugin'); ?>

		<input class="button-primary" type="submit" value="Delete" name="delete"/>
	</form>
</div>