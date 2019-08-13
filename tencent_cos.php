<?php

require "utils.php";

require_once 'sdk/tencent-cos/vendor/autoload.php';

use Qcloud\Cos\Client;

function file_upload($object, $filePath)
{
  // Init OSS Client Instance
  $option = get_option('wordpress_cdn_options');
  $cosClient = new Client(array(
    'region' => $option['endpoint'],
    'credentials' => array(
      'secretId' => $option['accessKeyId'],
      'secretKey' => $option['accessKeySecret'],
    ),
  ));

  // Action
  try {
    $cosClient->putObject(array(
      'Bucket' => $option['bucket'],
      'Key' => $object,
      'Body' => fopen($filePath, 'rs'),
    ));

    delete_local_file($filePath);
  } catch (Exception $e) {
    return FALSE;
  }
}

function check_file_exist_on_remote($object)
{
  $option = get_option('wordpress_cdn_options');
  $cosClient = new Client(array(
    'region' => $option['endpoint'],
    'credentials' => array(
      'secretId' => $option['accessKeyId'],
      'secretKey' => $option['accessKeySecret'],
    ),
  ));

  try {
    $cosClient->headObject(array(
      'Bucket' => $option['bucket'],
      'Key' => $object,
    ));
    return TRUE;
  } catch(Exception $e) {
    return FALSE;
  }
}

// Different platform needs different deleteObjects format
function delete_remote_attachment($post_id)
{
  $deleteObjects = array();
  $meta = wp_get_attachment_metadata($post_id);

  if (isset($meta['file'])) {
    $attachment_key = $meta['file'];
    $deleteObjects[] = array('Key' => $attachment_key);
  } else {
    $file = get_attached_file($post_id);
    $attached_key = str_replace(wp_get_upload_dir()['basedir'] . '/', '', $file);
    $deleteObjects[] = array('Key' => $attached_key);
  }

  if (isset($meta['sizes']) && count($meta['sizes']) > 0) {
    foreach ($meta['sizes'] as $val) {
      $attachment_thumb_key = dirname($meta['file']) . '/' . $val['file'];
      $deleteObjects[] = array('Key' => $attachment_thumb_key);
    }
  }

  if (!empty($deleteObjects)) {
    $option = get_option('wordpress_cdn_options');
    $cosClient = new Client(array(
      'region' => $option['endpoint'],
      'credentials' => array(
        'secretId' => $option['accessKeyId'],
        'secretKey' => $option['accessKeySecret'],
      ),
    ));
    
    $cosClient->deleteObjects(array(
      'Bucket' => $option['bucket'],
      'Objects' => $deleteObjects,
    ));
  }
}
