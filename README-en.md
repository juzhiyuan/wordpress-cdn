# [wordpress-cdn](https://www.shaoyaoju.org)
Upload attachments to Content Storage platform like [Aliyun OSS](https://www.aliyun.com/product/oss). For more detail, please visit this [blog article](https://blog.shaoyaoju.org/2019/08/07/wordpress-oss/).

## Support Platform
- [Aliyun OSS](https://www.aliyun.com/product/oss)
- [Tencent COS](https://cloud.tencent.com/product/cos)

## Installation
1. Clone the repo under `wordpress/wp-content/plugins/`;
2. Visit WordPress dashboard and activate this plugin；

## Usage
1. Activate this plugin;
2. Click Settings menu on the left of dashboard, and click the `wordpress-oss` button；
3. Fill out the configuration：

## Note
1. All uploaded files will be removed from your host server after the upload success；
2. All files will be saved under the root of bucket by default；
3. We need sync those data which are stored on the server disk to bucket;

## Reference
- [wpqiniu](https://wordpress.org/plugins/wpqiniu/)