<?php

namespace Enoliu\EasyDev\Pay\Alipay;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     */
    public function register(Container $app)
    {
        $app['alipay'] = function ($app) {
            return new Service($app);
        };
    }
}
