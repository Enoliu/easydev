<?php

namespace Enoliu\EasyDev\Kernel;


use Enoliu\EasyDev\Kernel\Providers\ConfigServiceProvider;
use Pimple\Container;
use think\Exception;

class ServiceContainer extends Container
{
    protected $providers = [];
    /**
     * @var array
     */
    private $user_config;

    public function __construct(array $config = [], array $prepends = [])
    {
        $this->user_config = $config;

        parent::__construct($prepends);

        $this->registerProviders($this->getProviders());
    }

    /**
     * 获取所有服务提供者
     *
     * @return array
     */
    private function getProviders(): array
    {
        return array_merge(
            [
                ConfigServiceProvider::class,
            ],
            $this->providers
        );
    }

    /**
     * 注册服务提供者
     *
     * @param array $providers
     */
    private function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getConfig(): array
    {
        $default_config = config('douaiwan', []);
        $config = array_merge($default_config, $this->user_config);

        if (! $config) throw new Exception('not found config file: douaiwan.php');

        return $config;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }
}
