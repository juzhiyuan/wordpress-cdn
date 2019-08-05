<?php

$plugin_options_group = 'wordpress_oss_options';
$accessKeyIdField = 'wordpress_oss__access_key_id';

// Register Option Fields
function wordpress_oss_init()
{
  global $plugin_options_group;
  global $accessKeyIdField;

  register_setting($plugin_options_group, $accessKeyIdField);
}

// Add Setting Menu to Admin Menu
function add_settings_page()
{
  require_once 'views/settings.php';
  add_options_page('WordPress OSS Settings', 'WordPress OSS', 'manage_options', 'wordpress-oss-plugin', 'generate_settings_page');
}
?>