<?php

namespace Enoliu\EasyDev\Sms;

use Enoliu\EasyDev\Kernel\ServiceContainer;

/**
 * Class Application
 *
 * @property \Enoliu\EasyDev\Sms\Aliyun\Service $aliyun   阿里云Sms
 * @method array send(string $phone, string $template_code, array $template_param = [])  发送短信（阿里云）
 */
class Application extends ServiceContainer
{
    protected $providers = [
        Aliyun\ServiceProvider::class,
    ];

    public function __call($name, $arguments)
    {
        return call_user_func([$this['aliyun'], $name], ...$arguments);
    }
}
