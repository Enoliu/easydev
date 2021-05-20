<h1 align="center"> easydev </h1>

<p align="center"> 都爱玩开发工具集合包，基于TP6</p>


## 安装

```shell
composer require enoliu/easydev -vvv
```  
## 发布配置文件
```php
// 在config目录生成douaiwan.php，根据需求对相应模块进行配置即可，不须全部配置完整  
php think vendor:publish
```
## HTTP请求  
#### 对http进行简单封装，常用简单http请求  
```php
use Enoliu\EasyDev\Douaiwan;

array Douaiwan::http()->get(string $url, array $query);    // get请求
array Douaiwan::http()->post(string $url, array $data);    // post请求
array Douaiwan::http()->put(string $url, array $data);     // put请求
array Douaiwan::http()->delete(string $url, array $data);  // delete请求
// 请求设置
array Douaiwan::http()->setHeader('header-key', 'header-value')->get(); // 设置头部信息
array Douaiwan::http()->setHeaders(['header-key' => 'header-value'])->get(); // 设置头部信息
array Douaiwan::http()->setTimeout(5)->get(); // 设置http请求超时时间

```

## redis相关方法  
#### redis原生方法
```php
use Enoliu\EasyDev\Douaiwan;
// Douaiwan::redis()后面可以跟所有redis原生方法，如：
Douaiwan::redis()->set('key', 'value');
Douaiwan::redis()->get('key');
```
## OSS上传相关  
#### OSS即代表上传，可上传至阿里云和本地
```php
use Enoliu\EasyDev\Douaiwan;

// 上传本地相关方法
string Douaiwan::oss()->local->upload(think\File $file, string $path, string $name = null);
array Douaiwan::oss()->local->batchUpload(array $files, string $path);
bool Douaiwan::oss()->local->copy($path, $$new_path);
bool Douaiwan::oss()->local->move($path, $new_path);
bool Douaiwan::oss()->local->delete($path);

// aliyun相关方法
string Douaiwan::oss()->aliyun->upload(think\File $file, string $path, string $name = null);
array Douaiwan::oss()->aliyun->batchUpload(array $files, string $path);
bool Douaiwan::oss()->aliyun->copy($path, $$new_path);
bool Douaiwan::oss()->aliyun->move($path, $new_path);
bool Douaiwan::oss()->aliyun->delete($path);
array Douaiwan::oss()->aliyun->webUpload($config); // web直传配置
array Douaiwan::oss()->aliyun->miniProgramUpload($config); // 微信小程序直传配置
array Douaiwan::oss()->aliyun->appUpload($config); // app直传配置
```
