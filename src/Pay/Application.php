<?php

namespace Enoliu\EasyDev\Pay;

use Enoliu\EasyDev\Kernel\ServiceContainer;

/**
 * Class Application
 *
 * @property \Enoliu\EasyDev\Pay\Alipay\Service $alipay 支付宝支付
 * @property \Enoliu\EasyDev\Pay\Wechat\Service $wechat 微信支付
 */
class Application extends ServiceContainer
{
    protected $providers = [
        Alipay\ServiceProvider::class,
        Wechat\ServiceProvider::class,
    ];
}
