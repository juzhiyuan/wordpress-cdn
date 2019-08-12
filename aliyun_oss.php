<?php

// Import OSS SDK
require_once 'sdk/aliyun-oss/autoload.php';

define('WORDPRESS_OSS_BASE_FOLDER', plugin_basename(dirname(__FILE__)));

use OSS\OssClient;
use OSS\Core\OssException;

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

function add_settings_page()
{
  require_once 'views/settings.php';
  add_options_page('WordPress OSS Settings', 'WordPress OSS', 'manage_options', 'wordpress-oss-plugin', 'generate_settings_page');
}

function wordpress_oss_upload_attachment($upload)
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
    wordpress_oss_file_upload($object, $filePath);
  }

  return $upload;
}

function wordpress_oss_file_upload($object, $filePath)
{
  // Init OSS Client Instance
  $option = get_option('wordpress_oss_options');
  $ossClient = new OssClient($option['accessKeyId'], $option['accessKeySecret'], $option['endpoint']);

  // Action
  try {
    $ossClient->uploadFile($option['bucket'], $object, $filePath);

    wordpress_oss_delete_local_file($filePath);
  } catch (OssException $e) {
    return FALSE;
  }
}

function wordpress_oss_delete_local_file($object)
{
  try {
    // File Not Exist
    if (!@file_exists($object)) {
      return TRUE;
    }

    if (!@unlink($object)) {
      return FALSE;
    }

    return TRUE;
  } catch (OssException $e) {
    return FALSE;
  }
}

function wordpress_oss_generate_attachment_metadata($metadata)
{
  if (isset($metadata['file'])) {
    $attachment_key = $metadata['file'];
    $attachment_local_path = wp_upload_dir()['basedir'] . '/' . $attachment_key;
    wordpress_oss_file_upload($attachment_key, $attachment_local_path);
  }

  if (isset($metadata['sizes']) && count($metadata['sizes']) > 0) {
    foreach ($metadata['sizes'] as $val) {
      $attachment_thumb_key = dirname($metadata['file']) . '/' . $val['file'];
      $attachment_thumb_local_path = wp_upload_dir()['basedir'] . '/' . $attachment_thumb_key;

      wordpress_oss_file_upload($attachment_thumb_key, $attachment_thumb_local_path);
    }
  }

  return $metadata;
}

function generate_unique_filename($filename)
{
  $option = get_option('wordpress_oss_options');
  $ossClient = new OssClient($option['accessKeyId'], $option['accessKeySecret'], $option['endpoint']);

  $object = wp_get_upload_dir()['subdir'] . "/$filename";
  $object = ltrim($object, '/');

  if ($ossClient->doesObjectExist($option['bucket'], $object)) {
    $filename = gen_uuid() . '-' . $filename;
  }
  return $filename;
}

function wordpress_oss_delete_remote_attachment($post_id)
{
  $deleteObjects = array();
  $meta = wp_get_attachment_metadata($post_id);

  if (isset($meta['file'])) {
    $attachment_key = $meta['file'];
    array_push($deleteObjects, $attachment_key);
  } else {
    $file = get_attached_file($post_id);
    $attached_key = str_replace(wp_get_upload_dir()['basedir'] . '/', '', $file);
    $deleteObjects[] = $attached_key;
  }

  if (isset($meta['sizes']) && count($meta['sizes']) > 0) {
    foreach ($meta['sizes'] as $val) {
      $attachment_thumb_key = dirname($meta['file']) . '/' . $val['file'];
      array_push($deleteObjects, $attachment_thumb_key);
    }
  }

  if (!empty($deleteObjects)) {
    $option = get_option('wordpress_oss_options');
    $ossClient = new OssClient($option['accessKeyId'], $option['accessKeySecret'], $option['endpoint']);
    $ossClient->deleteObjects($option['bucket'], $deleteObjects);
  }
}

function gen_uuid()
{
  return sprintf(
    '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    // 32 bits for "time_low"
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),

    // 16 bits for "time_mid"
    mt_rand(0, 0xffff),

    // 16 bits for "time_hi_and_version",
    // four most significant bits holds version number 4
    mt_rand(0, 0x0fff) | 0x4000,

    // 16 bits, 8 bits for "clk_seq_hi_res",
    // 8 bits for "clk_seq_low",
    // two most significant bits holds zero and one for variant DCE1.1
    mt_rand(0, 0x3fff) | 0x8000,

    // 48 bits for "node"
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff)
  );
}
