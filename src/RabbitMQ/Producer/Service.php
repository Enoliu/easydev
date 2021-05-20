<?php


namespace Enoliu\EasyDev\RabbitMQ\Producer;


use Enoliu\EasyDev\RabbitMQ\Base\BaseService;
use Exception;
use PhpAmqpLib\Message\AMQPMessage;

class Service extends BaseService
{
    /**
     * @var
     */
    private $callback = null;

    /**
     * @param mixed $message  消息数据
     *
     * @return bool
     * @throws Exception
     */
    public function publish(...$message): bool
    {
        // 声明交换机
        $this->declareExchange();
        // 声明队列
        $this->declareQueue();
        // 发送消息
        $this->channel->basic_publish(
            $this->normalizeMessage(['message' => $message, 'callback' => $this->callback]),
            $this->getExchange((bool)$this->delay),
            $this->getRoutingKey((bool)$this->delay)
        );
        // 关闭连接
        $this->close();

        return true;
    }

    /**
     * 设置消息回调方法
     *
     * @param mixed $callback  回调执行方法 [className, functionName] 或方法名，同call_user_func 参数1
     *
     * @return $this
     */
    public function setCallback($callback = []): self
    {
        $this->callback = $callback;

        return $this;
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
