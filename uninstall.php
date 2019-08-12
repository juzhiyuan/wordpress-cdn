<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
  // Exit when uninstall.php is not called from WordPress
  exit();
}

delete_option('wordpress_cdn_options');
update_option('upload_url_path', '');
