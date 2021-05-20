<?php


namespace Enoliu\EasyDev\Sms\Aliyun;

use AlibabaCloud\Client\AlibabaCloud;

/**
 * Class Service
 *
 * @package Enoliu\EasyDev\sms\Aliyun
 */
class Service
{
    /**
     * @var
     */
    private $app;
    /**
     * @var mixed
     */
    private $accessKeyId;
    /**
     * @var mixed
     */
    private $accessKeySecret;
    /**
     * @var mixed
     */
    private $regionId;
    /**
     * @var mixed
     */
    private $signName;

    public function __construct($app)
    {
        $this->app = $app;
        $this->accessKeyId = $this->app->config['sms']['aliyun']['access_key'];
        $this->accessKeySecret = $this->app->config['sms']['aliyun']['access_key_secret'];;
        $this->regionId = $this->app->config['sms']['aliyun']['region_id'];
        $this->signName = $this->app->config['sms']['aliyun']['sign_name'];
        $this->initClientSDK();
    }

    private function initClientSDK()
    {
        AlibabaCloud::accessKeyClient($this->accessKeyId, $this->accessKeySecret)
            ->regionId($this->regionId)
            ->asDefaultClient();
    }

    /**
     * 发送短信
     *
     * @param string $phone           手机号
     * @param string $template_code   模板code
     * @param array  $template_param  模板参数 ['code' => '123456']
     *
     * @return array
     * @throws \AlibabaCloud\Client\Exception\ClientException
     * @throws \AlibabaCloud\Client\Exception\ServerException
     */
    public function send(string $phone, string $template_code, array $template_param = []): array
    {
        return AlibabaCloud::rpc()
            ->product('Dysmsapi')
            ->version('2017-05-25')
            ->action('SendSms')
            ->method('POST')
            ->options(
                [
                    'query' => [
                        'RegionId'      => $this->regionId,
                        //需要发送到那个手机
                        'PhoneNumbers'  => $phone,
                        //必填项 签名(需要在阿里云短信服务后台申请)
                        'SignName'      => $this->signName,
                        //必填项 短信模板code (需要在阿里云短信服务后台申请)
                        'TemplateCode'  => $template_code,
                        //如果在短信中添加了${code} 变量则此项必填 要求为JSON格式
                        'TemplateParam' => json_encode($template_param),
                    ],
                ]
            )
            ->request()->jsonSerialize();
    }
}
