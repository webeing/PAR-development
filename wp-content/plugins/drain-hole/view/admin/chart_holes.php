<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?>
<div class="wrap" style="min-width: 820px">
	<div class="options">
		<a href="#" onclick="return print_chart ()"><img src="<?php echo $this->url () ?>/images/printer.png" width="16" height="16" alt="Printer"/></a>
		<a href="<?php echo $chart->source ?>&amp;csv"><img src="<?php echo $this->url () ?>/images/csv.png" width="16" height="16" alt="Csv"/></a>
	</div>
	
	<h2><?php printf (__ ('Hole Charts for %s', 'drain-hole'), $hole->url); ?></h2>

	<?php $this->submenu (true); ?>

	<div style="margin: 0 auto; width: 810px; clear: both">
		<form action="<?php echo $base ?>" method="get" accept-charset="utf-8">
			<p>
				<?php _e ('Chart', 'drain-hole'); ?>:
			<select name="type" id="type">
				<option value="percent"<?php if ($type == 'percent') echo ' selected="selected"' ?>><?php _e ('Downloads as percentage', 'drain-hole'); ?></option>
				<option value="time"<?php if ($type == 'time') echo ' selected="selected"' ?>><?php _e ('Downloads over time (grouped)', 'drain-hole'); ?></option>
			</select>
	
			<?php _e ('Display', 'drain-hole'); ?>: 
			<?php if ($type == 'percent') : ?>
				<select name="display" id="display">
					<option value="pie"<?php if ($display == 'pie') echo ' selected="selected"' ?>><?php _e ('Pie chart', 'drain-hole'); ?></option>
					<option value="bar"<?php if ($display == 'bar') echo ' selected="selected"' ?>><?php _e ('Bar chart', 'drain-hole'); ?></option>
				</select>
			<?php elseif ($type == 'time' || $type == 'perfile') : ?>
				<select name="display" id="display">
					<option value="hourly"<?php if ($display == 'hourly') echo ' selected="selected"' ?>><?php _e ('Hourly', 'drain-hole'); ?></option>
					<option value="daily"<?php if ($display == 'daily') echo ' selected="selected"' ?>><?php _e ('Daily', 'drain-hole'); ?></option>
					<option value="monthly"<?php if ($display == 'monthly') echo ' selected="selected"' ?>><?php _e ('Monthly', 'drain-hole'); ?></option>
				</select>

				<?php $chart->show_time ($display, $hole); ?>
			<?php endif; ?>
	
				<input type="submit" name="show" value="Show" id="show" class="button-secondary"/>
				<input type="hidden" name="source" value="hole"/>
				<input type="hidden" name="page" value="<?php echo $_GET['page'] ?>"/>
				<input type="hidden" name="chart" value="<?php echo $_GET['chart'] ?>"/>
			</p>
		</form>
	
		<?php echo $chart->get (); ?>
	
		<?php echo $chart->previous ($display, $hole); ?>
		<?php echo $chart->next ($display, $hole); ?>
		<div style="clear: both"></div>
	</div>
</div>