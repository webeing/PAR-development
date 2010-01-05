<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?>
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule ^(.*)$ <?php echo $index; ?>
</IfModule>
