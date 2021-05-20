##HTTP请求
####对http进行简单封装，常用简单http请求
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
