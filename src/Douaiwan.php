<?php

namespace Enoliu\EasyDev;

use think\helper\Str;

/**
 * Class Douaiwan
 *
 * @method static RabbitMQ\Application rabbitMQ(array $config = [])
 * @method static Redis\Application redis()
 * @method static Order\Application order(array $config = [])
 * @method static Oss\Application oss(array $config = [])
 * @method static Sms\Application sms(array $config = [])
 * @method static Http\Application http(array $config = [])
 * @method static Pay\Application pay(array $config = [])
 */
class Douaiwan
{
    public static function make(string $name, array $config = [])
    {
        $namespace = Str::studly($name);
        $application = "Enoliu\\EasyDev\\{$namespace}\\Application";

        return new $application($config);
    }

    public static function __callStatic($name, $arguments = [])
    {
        return static::make($name, ...$arguments);
    }
}
