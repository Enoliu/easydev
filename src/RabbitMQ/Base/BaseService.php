<?php


namespace Enoliu\EasyDev\RabbitMQ\Base;


use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Wire\AMQPTable;

class BaseService
{
    /**
     * @var
     */
    protected $app;

    /**
     * @var AMQPStreamConnection
     */
    protected $connection;

    /**
     * @var AMQPChannel
     */
    protected $channel;

    /**
     * @var string
     */
    protected $queue_name = 'douaiwan';

    /**
     * @var string
     */
    protected $exchange = 'douaiwan';

    /**
     * @var string
     */
    protected $routing_key = 'douaiwan';

    /**
     * @var int
     */
    protected $delay = 0;

    public function __construct($app)
    {
        $this->app = $app;
        $this->connection = new AMQPStreamConnection(
            $this->app['config']['rabbitMQ']['host'],
            $this->app['config']['rabbitMQ']['port'],
            $this->app['config']['rabbitMQ']['user'],
            $this->app['config']['rabbitMQ']['password'],
            $this->app['config']['rabbitMQ']['vhost']
        );
        $this->channel = $this->connection->channel();
    }

    /**
     * 设置交换器
     *
     * @param string $exchange
     *
     * @return $this
     */
    public function exchange(string $exchange): self
    {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * 设置队列名称
     *
     * @param string $queue_name
     *
     * @return $this
     */
    public function queueName(string $queue_name): self
    {
        $this->queue_name = $queue_name;

        return $this;
    }

    /**
     * 设置路由键
     *
     * @param string $routing_key
     *
     * @return $this
     */
    public function routingKey(string $routing_key): self
    {
        $this->routing_key = $routing_key;

        return $this;
    }

    /**
     * 设置延迟时间
     *
     * @param int $delay  延迟时间(秒)
     *
     * @return $this
     */
    public function delay(int $delay = 0): self
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * 声明交换机
     *
     * @return mixed|null
     */
    protected function declareExchange()
    {
        $exchange = $this->channel->exchange_declare($this->getExchange(), 'direct', false, true, false);
        if ($this->delay > 0) $this->declareDelayExchange();

        return $exchange;
    }

    /**
     * 声明交换机
     *
     * @return mixed|null
     */
    protected function declareDelayExchange()
    {
        return $this->channel->exchange_declare($this->getExchange(true), 'direct', false, true, false);
    }

    /**
     * 声明队列
     *
     * @return void
     */
    protected function declareQueue(): void
    {
        $this->channel->queue_declare($this->getQueueName(), false, true, false, false);
        $this->channel->queue_bind($this->getQueueName(), $this->getExchange(), $this->getRoutingKey());
        // 如果是延迟消息再声明一个延迟队列
        if ($this->delay > 0) $this->declareDelayQueue();
    }

    /**
     * 声明延迟队列
     */
    protected function declareDelayQueue(): void
    {
        $this->channel->queue_declare(
            $this->getQueueName(true),
            false,
            true,
            false,
            false,
            false,
            new AMQPTable(
                [
                    'x-dead-letter-exchange'    => $this->getExchange(),    // 消息过期后推送至此交换机
                    'x-dead-letter-routing-key' => $this->getRoutingKey(),  // 消息过期后推送至此路由地址,也就是我们消费的正常队列
                    'x-message-ttl'             => $this->delay * 1000, // 延迟时间（毫秒）
                ]
            )
        );
        $this->channel->queue_bind($this->getQueueName(true), $this->getExchange(true), $this->getRoutingKey(true));
    }

    /**
     * 关闭连接
     *
     * @throws \Exception
     */
    protected function close()
    {
        $this->channel->close();
        $this->connection->close();
    }

    protected function getQueueName(bool $delay = false): string
    {
        if ($delay) return $this->queue_name . ':delay:' . $this->delay;

        return $this->queue_name;
    }

    protected function getExchange(bool $delay = false): string
    {
        if (! $this->exchange) $this->exchange = $this->queue_name;

        if ($delay) return $this->exchange . ':delay:' . $this->delay;

        return $this->exchange;
    }

    protected function getRoutingKey(bool $delay = false): string
    {
        if (! $this->routing_key) $this->routing_key = $this->queue_name;

        if ($delay) return $this->routing_key . ':delay:' . $this->delay;

        return $this->routing_key;
    }
}
