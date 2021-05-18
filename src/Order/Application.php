<?php

namespace Enoliu\EasyDev\Order;

use Enoliu\EasyDev\Kernel\ServiceContainer;

/**
 * Class Application
 *
 * @package Enoliu\EasyDev\Order
 * @method array createOrder(array $order)
 */
class Application extends ServiceContainer
{
    protected $providers = [
        Base\ServiceProvider::class
    ];

    /**
     * @param $name
     * @param $arguments
     *
     * @return false|mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this['base'], $name], $arguments);
    }
}
