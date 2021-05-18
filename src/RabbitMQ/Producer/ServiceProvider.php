<?php

namespace Enoliu\EasyDev\RabbitMQ\Producer;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['producer'] = function ($app) {
            return new Service($app);
        };
    }
}
