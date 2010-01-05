<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?><?php if (count ($versions) > 0) : ?>
	<ul>
		<?php foreach ($versions AS $version) : ?>
			<li>
				<?php if ($file->has_version ($version->id, $hole)) : ?>
					<?php echo $file->url ($hole, $version->version, false, $version) ?>
				<?php else : ?>
					<?php echo $version->version; ?>
				<?php endif; ?>
				
				<?php if ($version->reason) echo ' - '.$version->reason; ?>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>