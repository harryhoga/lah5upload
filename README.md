# H5 直传阿里云 oss 扩展

### 1.使用 composer 安装 hoga/lah5upload 扩展

```
composer require hoga/lah5upload -vvvv
```

### 2.导出资源文件

`windows`:`php artisan vendor:publish --provider=Encore\lah5upload\h5uploadServiceProvider`

`mac|linux`:`php artisan vendor:publish --provider=Encore\\lah5upload\\h5uploadServiceProvider`

### 3.在`app/Admin/bootstrap.php`添加代码

```
Encore\Admin\Form::extend('lah5upload', \Encore\lah5upload\lah5uploadFiled::class);
```

### 5.在 form 方法里面使用

`$form->lah5upload('url','视频');`

### 设置允许上传扩展的文件

```
可选扩展:video视频类型文件 file所有类型的文件 mp3音频文件 image图片文件
$form->lah5upload('url','视频')->setExpansion('video');
```

### 关于.env 配置文件

```
请打开网站https://help.aliyun.com/document_detail/100624.html?spm=a2c4g.11186623.2.10.316879b0jDJxFq#concept-xzh-nzk-2gb根据提升一步一步添加配置
```

分片上传 最后一个请求报错 One or more of the specified parts could not be found or the specified entity tag might not have matched the part's entity tag 错误

```
// 响应信息
<?xml version="1.0" encoding="UTF-8"?>
<Error>
  <Code>InvalidPart</Code>
  <Message>One or more of the specified parts could not be found or the specified entity tag might not have matched the part's entity tag.</Message>
  <RequestId>5C455831BB4097C0D8F96794</RequestId>
  <HostId>wolaile.oss-cn-hangzhou.aliyuncs.com</HostId>
  <ETag>undefined</ETag>
  <PartNumber>1</PartNumber>
  <UploadId>4B1BF2F5DE064694870DD46E657F0CA6</UploadId>
</Error>
```

复制代码产生原因
经检查发现 ETag 为 undefined
解决方法
在阿里云 oss 控制台 基础设置 > 跨域规则设置 > 编辑规则 “暴露 Headers” 中增加 ETag 即可解决问题

作者：waanhappy
链接：https://juejin.im/post/5c4412186fb9a049d2365bbc
来源：掘金
著作权归作者所有。商业转载请联系作者获得授权，非商业转载请注明出处。

### tips

```
如果有什么问题可以联系email:643145444@qq.com,作者会在时间充足的情况下更新扩展
```

#有更好的点子

### 1.复制文件

`app/Admin/Extensions/laravel-admin-ext/lah5upload`

### 2.修改项目 composer.json 文件的 repositories 加入

```
 "lah5upload": {
   "type": "path",
   "url": "app/Admin/Extensions/laravel-admin-ext/lah5upload"
 }
```

### 3.安装本地

```
composer require harryhoga/lah5upload -vvvv
```
