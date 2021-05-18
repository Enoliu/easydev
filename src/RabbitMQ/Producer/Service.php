<?php


namespace Enoliu\EasyDev\RabbitMQ\Producer;


use Enoliu\EasyDev\RabbitMQ\Base\BaseService;
use Exception;
use PhpAmqpLib\Message\AMQPMessage;

class Service extends BaseService
{
    /**
     * @param mixed $message  消息数据
     * @param mixed $callback  回调执行方法 [className, functionName]
     *
     * @return bool
     * @throws Exception
     */
    public function publish($message, $callback = []): bool
    {
        // 声明交换机
        $this->declareExchange();
        // 声明队列
        $this->declareQueue();
        // 发送消息
        $this->channel->basic_publish(
            $this->normalizeMessage(compact('message', 'callback')),
            $this->getExchange((bool)$this->delay),
            $this->getRoutingKey((bool)$this->delay)
        );
        // 关闭连接
        $this->close();

        return true;
    }

    /**
     * 标准化队列消息
     *
     * @param array $data
     *
     * @return AMQPMessage
     */
    protected function normalizeMessage(array $data = []): AMQPMessage
    {
        return new AMQPMessage(
            json_encode($data),
            [
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            ]
        );
    }
}
