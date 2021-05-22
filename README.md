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
#### OSS即代表上传，可上传至阿里云和本地，<App直传暂未实现>
```php
use Enoliu\EasyDev\Douaiwan;

// 上传本地相关方法
string Douaiwan::oss()->local->upload(think\File $file, string $path, string $name = null); // $name不带后缀名
array Douaiwan::oss()->local->batchUpload(array $files, string $path, string $prefix_name = null);  // $prefix_name批量文件统一命名前缀，文件名称自动跟上序号，如：'prefix_name_1.png'
bool Douaiwan::oss()->local->copy($path, $$new_path);
bool Douaiwan::oss()->local->move($path, $new_path);
bool Douaiwan::oss()->local->delete($path);

// aliyun相关方法
string Douaiwan::oss()->aliyun->upload(think\File $file, string $path, string $name = null); // $name不带后缀名
array Douaiwan::oss()->aliyun->batchUpload(array $files, string $path, string $prefix_name = null);  // $prefix_name批量文件统一命名前缀，文件名称自动跟上序号，如：'prefix_name_1.png'
bool Douaiwan::oss()->aliyun->copy($path, $$new_path);
bool Douaiwan::oss()->aliyun->move($path, $new_path);
bool Douaiwan::oss()->aliyun->delete($path);
// web直传配置, $config = [
    'dir'      => '',   // 上传目录
    'expire'   => 30,   // 有效时间：秒
    'callback' => '',   // 回调地址
    'max_size' => 10485760    // 文件最大字节数，10M
];  
array Douaiwan::oss()->aliyun->webUpload($config); 
array Douaiwan::oss()->aliyun->miniProgramUpload($config); // 微信小程序直传配置，config同webUpload
array Douaiwan::oss()->aliyun->appUpload($config); // app直传配置
```

## 发送短信SMS  
#### 暂只支持阿里云短信  
```php
use Enoliu\EasyDev\Douaiwan;

array Douaiwan::sms()->send('17688xx1590', 'SMS_16xxxx219', ['code' => 123456]);
```

## 消息队列RabbitMQ  
  
### 生产者
```php
use Enoliu\EasyDev\Douaiwan;

// 基础用法，setCallback设置回调方法，可被is_callable发现，publish支持可变参数传入，参数将回调给callback，即publish参数需与callback对应
bool Douaiwan::rabbitMQ()->producer->setCallback('callback|[class,function]')->publish('param1','param2',...);
// 延迟队列：delay(int $delay = 0), 单位秒
bool Douaiwan::rabbitMQ()->producer->setCallback('callback|[class,function]')->delay(10)->publish('param1','param2',...);
// 自定义queue，exchange，routingkey
bool Douaiwan::rabbitMQ()->producer->setQueueName('queue_name')->setExchange('exchange_name')->setRoutingKey('routing_key')->setCallback('callback|[class,function]')->delay(10)->publish('param1','param2',...);

// 简单方法
publisher($data, $callback, int $delay = 0);
```
  
### 消费者
```php
use Enoliu\EasyDev\Douaiwan;

// qosLimit(int $limit = 1) 消费质量控制
Douaiwan::rabbitMQ()->consumer->qosLimit(1)->consume();

// 匹配自定义queue，exchange，routingkey，与生产消息时配置信息相对应
Douaiwan::rabbitMQ()->consumer->setQueueName('queue_name')->setExchange('exchange_name')->setRoutingKey('routing_key')->qosLimit(1)->consume();

// 简单方法
consume($qos = 1);
```
