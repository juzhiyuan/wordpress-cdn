# wordpress-oss
Upload attachments to [Aliyun OSS](https://www.aliyun.com/product/oss)

## Doc
- [中文简体](./README-cn.md)
## Installation
1. Clone the repo under `wordpress/wp-content/plugins/`;
2. Visit WordPress dashboard and activate this plugin；

## Usage
1. Activate this plugin;
2. Click Settings menu on the left of dashboard, and click the `wordpress-oss` button；
3. Fill out the configuration：

|Field|Description|
|-----------|-------------|
|accessKeyId|[Aliyun RAM](https://ram.console.aliyun.com) Key ID|
|accessKeySecret|Aliyun RAM Secret Key|
|endpoint|Area EndPoint|
|bucket|OSS Bucket Name|
|cdn_url_path|CDN URL|

## Note
1. The field `cdn_url_path` cannot end with `/`;
2. If the OSS area is in the same area as ECS, it's recommended to use the internal network address in the field `endpoint`;
3. All uploaded files will be removed from your host server after the upload success；
4. All files will be saved under the root of OSS by default；
5. We can use [OSS tools](https://help.aliyun.com/document_detail/44075.html) to sync those data which are stored on the server disk to OSS；

## Reference
- [wpqiniu](https://wordpress.org/plugins/wpqiniu/)
