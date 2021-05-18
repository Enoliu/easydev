<?php

namespace Enoliu\EasyDev\Kernel\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        !isset($pimple['config']) && $pimple['config'] = $pimple->getConfig();
    }
}
