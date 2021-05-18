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

    public function createOrder(array $data)
    {
        $order = $this->model->save();
        publish($order->order_no, [self::class, 'closeOrder'], $this->app->config['order']['timeout'] ?? 30 * 60);
    }

    /**
     * 关闭订单
     *
     * @param string $order_no
     *
     * @return bool
     */
    public function closeOrder(string $order_no): bool
    {
        $order = $this->queryOrder(['order_no' => $order_no]);
        if (! $order) return true;
    }

    public function queryOrder(array $condition)
    {
        return $this->model->where($condition)->find();
    }
}
