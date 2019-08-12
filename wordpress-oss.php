<?php
/*
  Plugin Name: WordPress OSS
  Plugin URI: https://www.shaoyaoju.org
  Description: Upload files to Aliyun OSS
  Version: 0.0.1
  Author: juzhiyuan
  Author URI: https://www.shaoyaoju.org
 */

$plugin_options_group = 'wordpress_oss_options';

$type = "aliyun_oss";
if ($type == "aliyun_oss") {
  require_once 'aliyun_oss.php';
}

register_activation_hook(__FILE__, "wordpress_oss_activatition");
register_deactivation_hook(__FILE__, "wordpress_oss_deactivation");

// Add plugin setting menu to Admin Menu
add_action('admin_menu', 'add_settings_page');

if (substr_count($_SERVER['REQUEST_URI'], '/update.php') <= 0) {
  add_filter('wp_handle_upload', 'wordpress_oss_upload_attachment');
  add_filter('wp_generate_attachment_metadata', 'wordpress_oss_generate_attachment_metadata');
}

// Fired when attachment updated
add_filter('wp_update_attachment_metadata', 'wordpress_oss_generate_attachment_metadata');

// Delete remote attachment
add_action('delete_attachment', 'wordpress_oss_delete_remote_attachment');

add_filter('wp_unique_filename', 'generate_unique_filename');