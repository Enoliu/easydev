<?php


namespace Enoliu\EasyDev\RabbitMQ\Consumer;


use Enoliu\EasyDev\RabbitMQ\Base\BaseService;
use PhpAmqpLib\Message\AMQPMessage;
use think\facade\Log;

class Service extends BaseService
{
    /**
     * @var int
     */
    protected $qos_limit = 1;

    /**
     * @throws \ErrorException
     */
    public function consume()
    {
        // 声明交换机
        $this->declareExchange();
        // 声明队列
        $this->declareQueue();
        // 质量保证
        $this->channel->basic_qos(0, $this->qos_limit, false);
        // 消费消息
        $this->channel->basic_consume(
            $this->getQueueName(),
            '',
            false,
            false,
            false,
            false,
            function ($message) {
                $this->callback($message);
            }
        );

        while($this->channel->is_consuming()) {
            $this->channel->wait();
        }

        // 关闭连接
        $this->close();
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function qosLimit(int $limit): self
    {
        $this->qos_limit = $limit;

        return $this;
    }

    /**
     * 处理消息回调
     *
     * @param AMQPMessage $message
     *
     * @throws \Exception
     */
    private function callback(AMQPMessage $message)
    {
        try {
            $data = json_decode($message->body, true);
            // 解析失败，抛弃消息或置为死信
            if (json_last_error() != JSON_ERROR_NONE) $message->reject(false);

            // 回调方法判断
            $callback = $data['callback'];
            if (! $callback || empty($callback)) {
                // 无回调方法，直接ack
                $message->ack();
            } elseif (
                // 有回调信息，但方法不存在，转为死信处理
                (is_array($callback) && ! method_exists(...$callback))
                || (is_string($callback) && ! function_exists($callback))
            ) {
                $message->reject(false);
            } else {
                // 执行回调方法
                if (is_array($callback)) {
                    call_user_func([new $callback[0](), $callback[1]], ...$data['message']);
                }else {
                    call_user_func($callback, ...$data['message']);
                }
                $message->ack();
            }
        } catch (\Exception $exception) {
            Log::error('RABBITMQ_CALLBACK_ERROR:' . $exception->getMessage());
            $message->reject(false);
            throw $exception;
        }
    }
}
