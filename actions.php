<?php

define('WORDPRESS_OSS_BASE_FOLDER', plugin_basename(dirname(__FILE__)));

// Register Option Fields
function wordpress_oss_activatition()
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

function wordpress_oss_deactivation()
{
  $options = get_option('wordpress_oss_options');
  $options['cdn_url_path'] = get_option('upload_url_path');
  update_option('wordpress_oss_options', $options);
  update_option('upload_url_path', '');
}

// Add Setting Menu to Admin Menu
function add_settings_page()
{
  require_once 'views/settings.php';
  add_options_page('WordPress OSS Settings', 'WordPress OSS', 'manage_options', 'wordpress-oss-plugin', 'generate_settings_page');
}
