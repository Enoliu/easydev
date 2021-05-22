<?php


namespace Enoliu\EasyDev\Pay\Wechat;


use EasyWeChat\Factory;

/**
 * Class Service
 *
 * @package Enoliu\EasyDev\Pay\Wechat
 * @method array bridgeConfig(string $prepayId)  WeixinJSBridge/小程序支付配置信息
 * @method array sdkConfig(string $prepayId)  JSSDK支付配置信息
 * @method array appConfig(string $prepayId)  APP支付配置信息
 */
class Service
{
    /**
     * @var
     */
    private $app;
    /**
     * @var \EasyWeChat\Payment\Application
     */
    private $payment;

    public function __construct($app)
    {
        $this->app = $app;
        $this->app->config['payment']['wechat']['response_type'] = 'array'; // 统一返回数组格式
        $this->payment = Factory::payment($this->app->config['payment']['wechat']);
    }

    /**
     * 支付下单
     *
     * @param array $data
     *
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pay(array $data): array
    {
        return $this->payment->order->unify($data);
    }

    /**
     * 查询订单
     *
     * @param string $out_trade_no
     *
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function query(string $out_trade_no): array
    {
        return $this->payment->order->queryByOutTradeNumber($out_trade_no);
    }

    /**
     * 关闭订单
     *
     * @param string $out_trade_no
     *
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function close(string $out_trade_no): array
    {
        return $this->payment->order->close($out_trade_no);
    }

    /**
     * 提现到用户零钱
     *
     * @param array $data
     *
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function withdraw(array $data): array
    {
        return $this->payment->transfer->toBalance($data);
    }

    /**
     * 根据商户订单号退款
     *
     * @param string $out_trade_no     商户订单号
     * @param string $refund_trade_no  商户退款单号
     * @param mixed  $total_amount     订单金额(元)
     * @param mixed  $refund_amount    退款金额(元)
     * @param array  $optional         其他参数
     *
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function refund(
        string $out_trade_no,
        string $refund_trade_no,
        $total_amount,
        $refund_amount,
        array $optional = []
    ): array {
        return $this->payment->refund->byOutTradeNumber(
            $out_trade_no,
            $refund_trade_no,
            $total_amount * 100,
            $refund_amount * 100,
            $optional
        );
    }

    /**
     * 其他方法
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // 生成jssdk相关配置方法
        if (in_array($name, ['bridgeConfig', 'sdkConfig', 'appConfig'])) {
            return $this->payment->jssdk->{$name}(...$arguments);
        }

        return null;
    }
}
