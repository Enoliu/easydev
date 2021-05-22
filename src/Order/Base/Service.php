<?php


namespace Enoliu\EasyDev\Order\Base;

use think\Model;

/**
 * Class Service
 *
 * @package Douaiwan\Order\Base
 */
class Service
{
    /**
     * @var
     */
    private $app;

    /**
     * @var Model
     */
    protected $model;

    public function __construct($app)
    {
        $this->app = $app;
        $this->model = new $this->app->config['order']['model']();
    }

    public function create()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function query()
    {

    }

    public function close()
    {

    }
}
