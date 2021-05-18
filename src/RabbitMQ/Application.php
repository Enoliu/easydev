<?php

namespace Enoliu\EasyDev\RabbitMQ;

use Enoliu\EasyDev\Kernel\ServiceContainer;

/**
 * Class Application
 *
 * @property \Enoliu\EasyDev\RabbitMQ\Producer\Service $producer   生产者
 * @property \Enoliu\EasyDev\RabbitMQ\Consumer\Service $consumer   消费者
 */
class Application extends ServiceContainer
{
    protected $providers = [
        Producer\ServiceProvider::class,
        Consumer\ServiceProvider::class,
    ];
}
