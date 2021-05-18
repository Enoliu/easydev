<?php

namespace Enoliu\EasyDev\Oss;

use Enoliu\EasyDev\Kernel\ServiceContainer;

/**
 * Class Application
 *
 * @property \Enoliu\EasyDev\Oss\Aliyun\Service $aliyun   阿里云OSS
 * @property \Enoliu\EasyDev\Oss\Local\Service  $local    本地储存
 */
class Application extends ServiceContainer
{
    protected $providers = [
        Aliyun\ServiceProvider::class,
        Local\ServiceProvider::class,
    ];
}
