<?php

namespace Enoliu\EasyDev\Http\Base;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['base'] = function ($app){
            return new Service($app);
        };
    }
}
