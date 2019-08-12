<?php
/*
  Plugin Name: WordPress CDN
  Plugin URI: https://github.com/juzhiyuan/wordpress-cdn
  Description: Upload files to Content Storage platform
  Version: 0.0.1
  Author: juzhiyuan
  Author URI: https://www.shaoyaoju.org
 */

define('PLUGIN_BASE_FOLDER', plugin_basename(dirname(__FILE__)));

$options = get_option('wordpress_cdn_options');
if ($options) {
  if (isset($options['platform'])) {
    $platform = $options['platform'];
    if ($platform == "aliyun_oss") {
      require_once "aliyun_oss.php";
    } elseif ($platform == "tencent_cos") {
      require_once "tencent_cos.php";
    }
  }
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
  $options = get_option('wordpress_cdn_options');

  if (!$options) {
    $options = array(
      'platform' => 'aliyun_oss',
      'cdn_url_path' => '',
      'accessKeyId' => '',
      'accessKeySecret' => '',
      'endpoint' => '',
      'bucket' => '',
    );

    add_option('wordpress_cdn_options', $options, '', 'yes');
  } else if (isset($options["cdn_url_path"]) && $options["cdn_url_path"] != "") {
    update_option('upload_url_path', $options['cdn_url_path']);
  }
}

function plugin_deactivation()
{
  $options = get_option('wordpress_cdn_options');

  $options['cdn_url_path'] = get_option('upload_url_path');
  update_option('wordpress_cdn_options', $options);
  update_option('upload_url_path', '');
}

function add_settings_page()
{
  require_once 'views/settings.php';
  add_options_page('WordPress CDN Settings', 'WordPress CDN', 'manage_options', 'wordpress-cdn-plugin', 'generate_settings_page');
}

function upload_attachment($upload)
{
  $mime_types = get_allowed_mime_types();
  $image_mime_types = array(
    $mime_types['jpg|jpeg|jpe'],
    $mime_types['gif'],
    $mime_types['png'],
    $mime_types['bmp'],
    $mime_types['tiff|tif'],
    $mime_types['ico'],
  );

  if (!in_array($upload['type'], $image_mime_types)) {
    $object = str_replace(wp_upload_dir()['basedir'] . '/', '', $upload['file']);
    $filePath = $upload['file'];
    file_upload($object, $filePath);
  }

  return $upload;
}

function generate_attachment_metadata($metadata)
{
  if (isset($metadata['file'])) {
    $attachment_key = $metadata['file'];
    $attachment_local_path = wp_upload_dir()['basedir'] . '/' . $attachment_key;
    file_upload($attachment_key, $attachment_local_path);
  }

  if (isset($metadata['sizes']) && count($metadata['sizes']) > 0) {
    foreach ($metadata['sizes'] as $val) {
      $attachment_thumb_key = dirname($metadata['file']) . '/' . $val['file'];
      $attachment_thumb_local_path = wp_upload_dir()['basedir'] . '/' . $attachment_thumb_key;

      file_upload($attachment_thumb_key, $attachment_thumb_local_path);
    }
  }

  return $metadata;
}

function generate_unique_filename($filename)
{
  $object = wp_get_upload_dir()['subdir'] . "/$filename";
  $object = ltrim($object, '/');

  if (check_file_exist_on_remote($object)) {
    $filename = gen_uuid() . '-' . $filename;
  }
  return $filename;
}