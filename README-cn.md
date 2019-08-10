# wordpress-oss
上传附件至 [阿里云 OSS](https://www.aliyun.com/product/oss)，希望了解更多？可以查看这篇[博文](https://blog.shaoyaoju.org/2019/08/07/wordpress-oss/)。

## 安装
1. 下载仓库内文件并拷贝至 WordPress 中 `wp-content/plugins` 目录下；
2. 进入 WordPress 后台，并访问左侧 `插件` 设置；
3. 激活 `wordpress-oss` 插件；

## 使用
1. 安装完毕后，激活插件；
2. 在 WordPress 后台左侧 `设置` 中，进入 `WordPress OSS` 选项页；
3. 填写配置信息：

|字段|解释|
|-----------|-------------|
|accessKeyId|阿里云 RAM 账户|
|accessKeySecret|阿里云 RAM 密钥|
|endpoint|区域节点地址|
|bucket|OSS Bucket 名称|
|cdn_url_path|CDN 域名|

## 注意
1. 配置字段 `cdn_url_path` 不可以以 `/` 结尾；
2. 若 OSS 区域与 ECS 在同一区域，推荐配置字段 `endpoint` 使用内网地址；
3. 该插件默认在上传文件完毕后，会将服务器中文件移除；
4. 该插件默认上传文件至 OSS Bucket 根目录下；
5. 若 WordPress 曾有文件在服务器中，可使用 [OSS 常用工具](https://help.aliyun.com/document_detail/44075.html) 将文件同步至 OSS Bucket 中；

## 参考
- [wpqiniu](https://wordpress.org/plugins/wpqiniu/)
