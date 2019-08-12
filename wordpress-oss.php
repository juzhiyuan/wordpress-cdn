<?php
/*
  Plugin Name: WordPress CDN
  Plugin URI: https://github.com/juzhiyuan/wordpress-cdn
  Description: Upload files to Content Storage platform
  Version: 0.0.1
  Author: juzhiyuan
  Author URI: https://www.shaoyaoju.org
 */

$plugin_options_group = 'wordpress_oss_options';

$type = "aliyun_oss";
if ($type == "aliyun_oss") {
  require_once 'aliyun_oss.php';
}

register_activation_hook(__FILE__, "plugin_activatition");
register_deactivation_hook(__FILE__, "plugin_deactivation");

// Add plugin setting menu to Admin Menu
add_action('admin_menu', 'add_settings_page');

if (substr_count($_SERVER['REQUEST_URI'], '/update.php') <= 0) {
  add_filter('wp_handle_upload', 'upload_attachment');
  add_filter('wp_generate_attachment_metadata', 'generate_attachment_metadata');
}

// Fired when attachment updated
add_filter('wp_update_attachment_metadata', 'generate_attachment_metadata');

// Delete remote attachment
add_action('delete_attachment', 'delete_remote_attachment');

add_filter('wp_unique_filename', 'generate_unique_filename');

// Public Functions
function plugin_activatition()
{
  $options = get_option('wordpress_oss_options');

  if (!$options) {
    $options = array(
      'accessKeyId' => '',
      'accessKeySecret' => '',
      'endpoint' => '',
      'bucket' => '',
      'cdn_url_path' => '',
    );

    add_option('wordpress_oss_options', $options, '', 'yes');
  } else if (isset($options["cdn_url_path"]) && $options["cdn_url_path"] != "") {
    update_option('upload_url_path', $options['cdn_url_path']);
  }
}

function plugin_deactivation()
{
  $options = get_option('wordpress_oss_options');
  $options['cdn_url_path'] = get_option('upload_url_path');
  update_option('wordpress_oss_options', $options);
  update_option('upload_url_path', '');
}