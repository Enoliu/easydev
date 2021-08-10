<?php


namespace Enoliu\EasyDev\Pay\Alipay;


use Alipay\EasySDK\Kernel\CertEnvironment;
use Alipay\EasySDK\Kernel\Config;
use Alipay\EasySDK\Kernel\EasySDKKernel;
use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Kernel\Payment;

class Service
{
    /**
     * @var
     */
    private $app;
    /**
     * @var Payment
     */
    private $payment;
    /**
     * @var EasySDKKernel
     */
    private $_kernel;

    public function __construct($app)
    {
        $this->app = $app;
        $this->_kernel = new EasySDKKernel($this->getOptions());
        Factory::setOptions($this->getOptions());
        $this->payment = $this->factory();
    }

    /**
     * 支付宝APP支付
     *
     * @param array $data
     *
     * @return array
     */
    public function pay(array $data): array
    {
        $handle = $this->payment->app();

        // 自定义回调地址
        if ($data['notify_url']) $handle->optional('notifyUrl', $data['notify_url']);

        $response = $handle->pay($data['subject'], $data['out_trade_no'], $data['total_amount']);

        return $this->responseMap($response);
    }

    /**
     * 支付H5支付GET形式，post形式调用->factory()->wap()->pay()
     * @param $subject
     * @param $outTradeNo
     * @param $totalAmount
     * @param $quitUrl
     * @param $returnUrl
     *
     * @return string
     * @throws \Exception
     *
     * @author liuxiaolong <595929049@qq.com>
     * @date   2021/6/7
     */
    public function h5pay($subject, $outTradeNo, $totalAmount, $quitUrl, $returnUrl): string
    {
        $systemParams = [
            "method" => "alipay.trade.wap.pay",
            "app_id" => $this->_kernel->getConfig("appId"),
            "timestamp" => $this->_kernel->getTimestamp(),
            "format" => "json",
            "version" => "1.0",
            "alipay_sdk" => $this->_kernel->getSdkVersion(),
            "charset" => "UTF-8",
            "sign_type" => $this->_kernel->getConfig("signType"),
            "app_cert_sn" => $this->_kernel->getMerchantCertSN(),
            "alipay_root_cert_sn" => $this->_kernel->getAlipayRootCertSN()
        ];
        $bizParams = [
            "subject" => $subject,
            "out_trade_no" => $outTradeNo,
            "total_amount" => $totalAmount,
            "quit_url" => $quitUrl,
            "product_code" => "QUICK_WAP_WAY"
        ];
        $textParams = [
            "return_url" => $returnUrl
        ];
        $sign = $this->_kernel->sign($systemParams, $bizParams, $textParams, $this->_kernel->getConfig("merchantPrivateKey"));

        return $this->_kernel->generatePage("GET", $systemParams, $bizParams, $textParams, $sign);
    }

    /**
     * 查询订单
     *
     * @param string $out_trade_no
     *
     * @return array
     * @throws \Exception
     */
    public function query(string $out_trade_no): array
    {
        $response = $this->payment->common()->query($out_trade_no);

        return $this->responseMap($response);
    }

    /**
     * 查询订单是否已支付
     * @param string $out_trade_no
     *
     * @return bool
     * @throws \Exception
     */
    public function isPay(string $out_trade_no): bool
    {
        $result = $this->query($out_trade_no);
        return isset($result['code']) && $result['code'] == 10000 && isset($result['trade_status']) && $result['trade_status'] == 'TRADE_SUCCESS';
    }

    /**
     * 关闭订单
     *
     * @param string $out_trade_no
     *
     * @return array
     * @throws \Exception
     */
    public function close(string $out_trade_no): array
    {
        $response = $this->payment->common()->close($out_trade_no);

        return $this->responseMap($response);
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
     * @throws \Exception
     */
    public function refund(
        string $out_trade_no,
        string $refund_trade_no,
        $total_amount,
        $refund_amount,
        array $optional = []
    ): array {
        $response = $this->payment->common()
            ->batchOptional($optional)
            ->batchOptional(
                [
                    'out_request_no' => $refund_trade_no,
                    'total_amount'   => $total_amount,  // 此参数无用，仅统一接口标识
                ]
            )->refund($out_trade_no, $refund_amount);

        return $this->responseMap($response);
    }

    /**
     * 返回alipay支付工厂类
     *
     * @return Payment
     */
    public function factory(): Payment
    {
        return Factory::payment();
    }

    /**
     * @param $response
     *
     * @return array
     */
    private function responseMap($response): array
    {
        return is_array($response) ? $response : $response->toMap();
    }

    /**
     * 获取配置信息
     *
     * @return Config
     */
    private function getOptions(): Config
    {
        $config = $this->app->config['payment']['alipay'];

        $options = new Config();
        // 请求方式 通信协议
        $options->protocol = 'https';
        // 支付网关
        $options->gatewayHost = 'openapi.alipay.com';
        // 加密方式
        $options->signType = 'RSA2';
        // appid
        $options->appId = $config['app_id'];
        // 支付宝公钥
        $options->alipayPublicKey = $config['alipay_public_key'];
        // 商户秘钥
        $options->merchantPrivateKey = $config['merchant_private_key'];
        // 回调地址
        $options->notifyUrl = $config['notify_url'];
        // 商户证书地址
        $options->merchantCertPath = $config['merchant_cert_path'] ?: null;
        // 商户证书编号
        $options->merchantCertSN = $config['merchant_cert_sn'] ?: null;
        // 支付宝证书地址
        $options->alipayCertPath = $config['alipay_cert_path'] ?: null;
        // 支付宝证书编号
        $options->alipayCertSN = $config['alipay_cert_sn'] ?: null;
        // 阿里支付根证书
        $options->alipayRootCertPath = $config['alipay_root_cert_path'] ?: null;
        // 支付宝根证书编号
        $options->alipayRootCertSN = $config['alipay_root_cert_sn'] ?: null;
        // 加密密钥
        $options->encryptKey = $config['encrypt_key'] ?: null;
        // 文件密钥处理
        if (!empty($options->alipayCertPath)) {
            $certEnvironment = new CertEnvironment();
            $certEnvironment->certEnvironment($options->merchantCertPath, $options->alipayCertPath, $options->alipayRootCertPath);
            $options->merchantCertSN = $certEnvironment->getMerchantCertSN();
            $options->alipayRootCertSN = $certEnvironment->getRootCertSN();
            $options->alipayPublicKey = $certEnvironment->getCachedAlipayPublicKey();
        }
        return $options;
    }

}
