<?php

namespace Enoliu\EasyDev\Redis;

use Enoliu\EasyDev\Kernel\ServiceContainer;

/**
 * Class Application
 *
 * @package Enoliu\EasyDev\Order
 * @method bool exists(string $key) 检测是否存在某值
 * @method bool setex(string $key, int $expire, mixed $value) 为指定的 key 设置值及其过期时间
 * @method bool del(string $key) 该命令用于在 key 存在时删除 key
 * @method bool expire(string $key, int $seconds) 为给定 key 设置过期时间，以秒计
 * @method bool expireat(string $key, int $timestamp) 以 UNIX 时间戳(unix timestamp)格式设置 key 的过期时间。key 过期后将不再可用
 * @method bool pexpire(string $key, int $milliseconds) 设置 key 的过期时间以毫秒计
 * @method bool pexpireat(string $key, int $milliseconds) 设置 key 过期时间的时间戳(unix timestamp) 以毫秒计。key 过期后将不再可用
 * @method array keys(string $pattern) 查找所有符合给定模式(pattern)的 key
 * @method bool persist(string $key) 移除 key 的过期时间，key 将持久保持
 * @method int ttl(string $key) 以秒为单位，返回给定 key 的剩余生存时间(TTL, time to live)
 * @method int pttl(string $key) 以毫秒为单位，返回给定 key 的剩余生存时间(TTL, time to live)
 * @method string randomkey() 从当前数据库中随机返回一个 key
 * @method string rename(string $key, string $new_key) 修改 key 的名称
 * @method string renamenx(string $key, string $new_key) 仅当 newkey 不存在时，将 key 改名为 newkey
 * @method string type(string $key) 返回 key 所储存的值的类型，返回值：none (key不存在), string, string, set, zset, hash
 * 字符串相关方法
 * @method bool set(string $key, mixed $value) 设置指定 key 的值
 * @method mixed get(string $key) 获取指定 key 的值
 * @method string getRange(string $key) 获取指定 key 的值
 * 列表相关方法
 * 集合相关方法
 * @method bool sadd(string $key, mixed $value) 将一个或多个成员元素加入到集合中，已经存在于集合的成员元素将被忽略
 * @method bool sismember(string $key, mixed $value) 判断成员元素是否是集合的成员
 * @method int scard(string $key) 返回集合中元素个数
 * 有序集合相关方法
 * 哈希相关方法
 * @method bool hdel(string $key, mixed $field) 删除一个或多个哈希表字段
 * @method bool hexists(string $key, mixed $field) 查看哈希表 key 中，指定的字段是否存在
 * @method mixed hget(string $key, mixed $field) 获取存储在哈希表中指定字段的值
 * @method array hgetall(string $key) 获取在哈希表中指定 key 的所有字段和值
 * @method int hincrby(string $key, mixed $field, int $increment) 为哈希表 key 中的指定字段的整数值加上增量 increment
 * @method float hincrbyfloat(string $key, mixed $field, int $increment) 为哈希表 key 中的指定字段的浮点数值加上增量 increment
 * @method array hkeys(string $key) 获取所有哈希表中的字段
 * @method int hlen(string $key) 获取哈希表中字段的数量
 * @method array hmget(string $key, array $fields) 获取所有给定字段的值
 * @method bool hmset(string $key, array $fields) 同时将多个 field-value (域-值)对设置到哈希表 key 中
 * @method bool hset(string $key, mixed $field, mixed $value) 将哈希表 key 中的字段 field 的值设为 value
 * @method bool hsetnx(string $key, mixed $field, mixed $value) 只有在字段 field 不存在时，设置哈希表字段的值
 * @method array hvals(string $key) 获取哈希表中所有值
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
