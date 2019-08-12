<?php

require "utils.php";

require_once 'sdk/aliyun-oss/autoload.php';

use OSS\OssClient;
use OSS\Core\OssException;

function add_settings_page()
{
  require_once 'views/settings.php';
  add_options_page('WordPress OSS Settings', 'WordPress OSS', 'manage_options', 'wordpress-oss-plugin', 'generate_settings_page');
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

function file_upload($object, $filePath)
{
  // Init OSS Client Instance
  $option = get_option('wordpress_oss_options');
  $ossClient = new OssClient($option['accessKeyId'], $option['accessKeySecret'], $option['endpoint']);

  // Action
  try {
    $ossClient->uploadFile($option['bucket'], $object, $filePath);

    delete_local_file($filePath);
  } catch (OssException $e) {
    return FALSE;
  }
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
  $option = get_option('wordpress_oss_options');
  $ossClient = new OssClient($option['accessKeyId'], $option['accessKeySecret'], $option['endpoint']);

  $object = wp_get_upload_dir()['subdir'] . "/$filename";
  $object = ltrim($object, '/');

  if ($ossClient->doesObjectExist($option['bucket'], $object)) {
    $filename = gen_uuid() . '-' . $filename;
  }
  return $filename;
}

function delete_remote_attachment($post_id)
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
