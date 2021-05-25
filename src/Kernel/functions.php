<?php

use Enoliu\EasyDev\Douaiwan;

if (! function_exists('publisher')) {
    /**
     * 加入队列
     *
     * @param mixed $data      消息数据
     * @param mixed $callback  消息处理方法
     * @param int   $delay     延迟时间
     *
     * @throws Exception
     */
    function publisher($data, $callback, int $delay = 0)
    {
        Douaiwan::rabbitMQ()->producer->delay($delay)->setCallback($callback)->publish($data);
    }
}

if (! function_exists('consume')) {
    /**
     * 消费队列
     *
     * @param int $qos  质量控制参数
     *
     * @throws ErrorException
     */
    function consume($qos = 1)
    {
        Douaiwan::rabbitMQ()->consumer->qosLimit($qos)->consume();
    }
}

if (! function_exists('redis')) {
    /**
     * 获取redis句柄，简化操作
     *
     * @return \Enoliu\EasyDev\Redis\Application
     */
    function redis()
    {
        return Douaiwan::redis();
    }
}

if (! function_exists('setnxLock')) {
    /**
     * 设置操作锁，只有当不存在锁时才返回成功
     *
     * @param string $key
     * @param int    $expire  锁过期时间（秒）
     *
     * @return bool
     */
    function setnxLock(string $key, int $expire = 10): bool
    {
        if (redis()->exists($key)) return false;
        redis()->setex($key, $expire, 'lock:' . $key);

        return true;
    }
}

if (! function_exists('releaseLock')) {
    /**
     * 释放操作锁
     *
     * @param string $key
     *
     * @return bool
     */
    function releaseLock(string $key): bool
    {
        return redis()->del($key);
    }
}

if (! function_exists('createOrderNo')) {
    /**
     * 生成唯一订单号
     *
     * @param string $prefix  订单前缀
     *
     * @return string
     */
    function createOrderNo(string $prefix = 'douaiwan'): string
    {
        $minute_time = date('YmdHi');
        //生成key
        $key = 'order_create_time:' . $minute_time;

        //判断缓存是否存在
        if (! redis()->exists($key)) redis()->setex($key, 120, 1000000);

        return $prefix . $minute_time . redis()->incr($key);
    }
}

if (! function_exists('gmt_iso8601')) {
    /**
     * @param int $time
     *
     * @return string
     * @throws Exception
     * @author liuxiaolong
     */
    function gmt_iso8601(int $time): string
    {
        $expiration = (new DateTime(date("c", $time)))->format(DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);

        return $expiration . "Z";
    }
}

if (! function_exists('is_json')) {
    /**
     * 判断字符串是否为json字符串
     *
     * @param string $value
     *
     * @return bool
     */
    function is_json(string $value): bool
    {
        $value = json_decode($value, true);

        return $value && (is_object($value) || is_array($value));
    }
}
