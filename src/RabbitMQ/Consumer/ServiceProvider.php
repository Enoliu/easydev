<?php

namespace Enoliu\EasyDev\RabbitMQ\Consumer;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     */
    public function register(Container $app)
    {
        $app['consumer'] = function ($app) {
            return new Service($app);
        };
    }
}
