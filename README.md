# [wordpress-cdn](https://www.shaoyaoju.org)
上传附件至内容存储平台，如 [阿里云 OSS](https://www.aliyun.com/product/oss) 等。希望了解更多？可以查看这篇 [博文](https://blog.shaoyaoju.org/2019/08/07/wordpress-oss/)。

[README in English](./README-en.md)

## 支持的平台
- [阿里云 OSS](https://www.aliyun.com/product/oss)
- [腾讯云 COS](https://cloud.tencent.com/product/cos)

## 安装
1. 下载仓库内文件并拷贝至 WordPress 中 `wp-content/plugins` 目录下；
2. 进入 WordPress 后台，并访问左侧 `插件` 设置；
3. 激活 `wordpress-oss` 插件；

## 使用
1. 安装完毕后，激活插件；
2. 在 WordPress 后台左侧 `设置` 中，进入 `WordPress OSS` 选项页；
3. 填写配置信息并保存；

## 注意
1. 该插件默认在上传文件完毕后，会将服务器中文件移除；
2. 该插件默认上传文件至 Bucket 根目录下；
3. 若 WordPress 曾有文件在服务器中，请先将服务器内文件同步至 Bucket 中；

## 参考
- [wpqiniu](https://wordpress.org/plugins/wpqiniu/)
