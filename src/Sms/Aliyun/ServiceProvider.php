<?php

namespace Enoliu\EasyDev\Sms\Aliyun;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['aliyun'] = function ($app) {
            return new Service($app);
        };
    }
}
