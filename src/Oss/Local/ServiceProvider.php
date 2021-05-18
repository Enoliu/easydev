<?php

namespace Enoliu\EasyDev\Oss\Local;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['local'] = function ($app) {
            return new Service($app);
        };
    }
}
