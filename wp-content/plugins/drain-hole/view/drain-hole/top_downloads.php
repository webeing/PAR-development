<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?><ul>
<?php foreach ($files AS $file) : $hole = DH_Hole::get ($file->hole_id); ?>
	<li><?php echo $file->url ($hole, $file->name ()); ?> (<?php echo number_format ($file->hits) ?>)</li>
<?php endforeach; ?>
</ul>