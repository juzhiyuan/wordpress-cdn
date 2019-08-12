<?php

require "utils.php";

require_once 'sdk/tencent-cos/vendor/autoload.php';

// use OSS\OssClient;
// use OSS\Core\OssException;

// function file_upload($object, $filePath)
// {
//   // Init OSS Client Instance
//   $option = get_option('wordpress_oss_options');
//   $ossClient = new OssClient($option['accessKeyId'], $option['accessKeySecret'], $option['endpoint']);

//   // Action
//   try {
//     $ossClient->uploadFile($option['bucket'], $object, $filePath);

//     delete_local_file($filePath);
//   } catch (OssException $e) {
//     return FALSE;
//   }
// }

// function check_file_exist_on_remote($object) {
//   $option = get_option('wordpress_oss_options');
//   $ossClient = new OssClient($option['accessKeyId'], $option['accessKeySecret'], $option['endpoint']);
//   return $ossClient->doesObjectExist($option['bucket'], $object);
// }

// // Different platform needs different deleteObjects format
// function delete_remote_attachment($post_id)
// {
//   $deleteObjects = array();
//   $meta = wp_get_attachment_metadata($post_id);

//   if (isset($meta['file'])) {
//     $attachment_key = $meta['file'];
//     array_push($deleteObjects, $attachment_key);
//   } else {
//     $file = get_attached_file($post_id);
//     $attached_key = str_replace(wp_get_upload_dir()['basedir'] . '/', '', $file);
//     $deleteObjects[] = $attached_key;
//   }

//   if (isset($meta['sizes']) && count($meta['sizes']) > 0) {
//     foreach ($meta['sizes'] as $val) {
//       $attachment_thumb_key = dirname($meta['file']) . '/' . $val['file'];
//       array_push($deleteObjects, $attachment_thumb_key);
//     }
//   }

//   if (!empty($deleteObjects)) {
//     $option = get_option('wordpress_oss_options');
//     $ossClient = new OssClient($option['accessKeyId'], $option['accessKeySecret'], $option['endpoint']);
//     $ossClient->deleteObjects($option['bucket'], $deleteObjects);
//   }
// }