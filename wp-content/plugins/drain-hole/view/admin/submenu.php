<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?>
<ul class="subsubsub">
  <li><a <?php if ($sub == '') echo 'class="current"'; ?>href="<?php echo $url ?>"><?php _e ('Files &amp; Holes', 'drain-hole') ?></a> |</li>
  <li><a <?php if ($sub == 'downloads') echo 'class="current"'; ?>href="<?php echo $url ?>&amp;sub=downloads"><?php _e ('Downloads', 'drain-hole') ?></a> |</li>
  <li><a <?php if ($sub == 'options') echo 'class="current"'; ?>href="<?php echo $url ?>&amp;sub=options"><?php _e ('Options', 'drain-hole') ?></a> |</li>
  <li><a <?php if ($sub == 'support') echo 'class="current"'; ?>href="<?php echo $url ?>&amp;sub=support"><?php _e ('Support', 'drain-hole') ?></a></li>
</ul>