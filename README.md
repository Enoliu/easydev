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
## 支付  
### 微信支付  
```php
use Enoliu\EasyDev\Douaiwan;

// 支付下单
array $result = Douaiwan::pay()->wechat->pay([
    'body' => '腾讯充值中心-QQ会员充值',  // 商品说明
    'out_trade_no' => '20150806125346', // 商户自定义订单号
    'total_fee' => 88,  // 订单金额：分
    'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
    'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
    'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
    'openid' => 'oUpF8uMuAJO_M2pxb1Q9zNjWeS6o', // 用户openid
]);
// $result:
{
    "return_code": "SUCCESS",
    "return_msg": "OK",
    "appid": "wx2421b1c4390ec4sb",
    "mch_id": "10000100",
    "nonce_str": "IITRi8Iabbblz1J",
    "openid": "oUpF8uMuAJO_M2pxb1Q9zNjWeSs6o",
    "sign": "7921E432F65EB8ED0CE9755F0E86D72F2",
    "result_code": "SUCCESS",
    "prepay_id": "wx201411102639507cbf6ffd8b0779950874",
    "trade_type": "JSAPI"
}

// 查询订单
array Douaiwan::pay()->wechat->query('out_trade_no');
// 关闭订单
array Douaiwan::pay()->wechat->close('out_trade_no');
// 提现到零钱
array Douaiwan::pay()->wechat->withdraw([
    'partner_trade_no' => '1233455', // 商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
    'openid' => 'oxTWIuGaIt6gTKsQRLau2M0yL16E', // 用户openid
    'check_name' => 'FORCE_CHECK', // NO_CHECK：不校验真实姓名, FORCE_CHECK：强校验真实姓名
    're_user_name' => '王小帅', // 如果 check_name 设置为FORCE_CHECK，则必填用户真实姓名
    'amount' => 10000, // 企业付款金额，单位为分
    'desc' => '理赔', // 企业付款操作说明信息。必填
]);
// 退款,参数分别为：商户订单号、商户退款单号、订单金额（元）、退款金额（元）、其他参数
array Douaiwan::pay()->wechat->refund(string $out_trade_no, string $refund_trade_no, mixed $total_fee, mixed $refund_fee, array $optional = []);
// 生成前端支付配置信息
array Douaiwan::pay()->wechat->appConfig('prepay_id');  // app支付
array Douaiwan::pay()->wechat->bridgeConfig('prepay_id'); // WeixinJSBridge/小程序支付配置信息
array Douaiwan::pay()->wechat->sdkConfig('prepay_id'); // JSSDK
```
### 支付宝支付
```php
use Enoliu\EasyDev\Douaiwan;

// APP跳转支付宝客户端支付下单
array Douaiwan::pay()->alipay->pay([
    'subject' => '购买商品名称',
    'out_trade_no' => '商户自定义订单号',
    'total_amount' => 8.8, // 订单总金额，单位元，注意微信支付和支付宝支付金额单位不一样
    'notify_url' => 'http://www.douaiwan.com/alipay/notify' // 订单支付回调地址
]);
// 查询订单
array Douaiwan::pay()->alipay->query('out_trade_no');
// 关闭订单
array Douaiwan::pay()->alipay->close('out_trade_no');
// 退款,参数分别为：商户订单号、商户退款单号、订单金额（元）、退款金额（元）、其他参数
array Douaiwan::pay()->alipay->refund(string $out_trade_no, string $refund_trade_no, mixed $total_fee, mixed $refund_fee, array $optional = []);

```
