<?php

namespace Enoliu\EasyDev\Http;

use Enoliu\EasyDev\Kernel\ServiceContainer;

/**
 * Class Application
 *
 * @method array get(string $url, array $query = [])    // GET请求
 * @method array post(string $url, array $data = [])    // POST请求
 * @method array put(string $url, array $data = [])     // PUT请求
 * @method array delete(string $url, array $data = [])  // DELETE请求
 * @method array setHeader(string $key, mixed $value)   // 设置header
 * @method array setHeaders(array $headers = [])        // 设置多个header
 * @method array setTimeout(int $timeout = 10)          // 设置请求超时时间
 */
class Application extends ServiceContainer
{
    protected $providers = [
        Base\ServiceProvider::class,
    ];

    public function __call($name, $arguments)
    {
        return call_user_func([$this['base'], $name], ...$arguments);
    }
}
