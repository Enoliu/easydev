<?php


namespace Enoliu\EasyDev\Oss\Aliyun;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Clients\StsClient;
use OSS\OssClient;
use think\File;

/**
 * Class Service
 *
 * @package Enoliu\EasyDev\Oss\Aliyun
 */
class Service
{
    /**
     * @var
     */
    private $app;

    /**
     * @var OssClient
     */
    private $client;
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
    private $endPoint;
    /**
     * @var mixed
     */
    private $bucket;
    /**
     * @var false|mixed
     */
    private $isCName;
    /**
     * @var mixed|null
     */
    private $token;
    /**
     * @var false|mixed
     */
    private $useSSL;
    /**
     * @var mixed
     */
    private $securityToken;

    /**
     * 直传默认配置
     *
     * @var array
     */
    private $directUploadConfig = [
        'dir'      => '',   // 上传目录
        'expire'   => 30,   // 秒
        'callback' => '',   // 回调地址
        'max_size' => 10485760    // 文件最大字节数，10M
    ];

    public function __construct($app)
    {
        $this->app = $app;
        $this->accessKeyId = $this->app->config['oss']['aliyun']['access_id'];
        $this->accessKeySecret = $this->app->config['oss']['aliyun']['access_key'];;
        $this->endPoint = $this->app->config['oss']['aliyun']['end_point'];
        $this->bucket = $this->app->config['oss']['aliyun']['bucket'];
        $this->isCName = $this->app->config['oss']['aliyun']['is_cname'] ?? false;
        $this->token = $this->app->config['oss']['aliyun']['token'] ?? null;
        $this->useSSL = $this->app->config['oss']['aliyun']['use_ssl'] ?? false;
        $this->securityToken = $this->app->config['oss']['aliyun']['securityToken'] ?? null;
    }

    /**
     * 单文件上传
     *
     * @param File        $file  文件
     * @param string      $path  储存目录
     * @param string|null $name  自定义文件名，不传则自动生成
     *
     * @return string
     */
    public function upload(File $file, string $path, string $name = null): string
    {
        if (! $name) $name = md5((string)microtime(true) . mt_rand(1000, 9999));

        $path = trim($path . '/' . $name . '.' . $file->getOriginalExtension(), '/');

        $this->ossClient()->putObject($this->bucket, $path, file_get_contents($file->getRealPath()));

        return $path;
    }

    /**
     * 多文件上传
     *
     * @param array       $files        File文件数组，
     * @param string      $path         存储目录
     * @param string|null $prefix_name  批量命名前缀
     *
     * @return array
     */
    public function batchUpload(array $files, string $path, string $prefix_name = null): array
    {
        $save_paths = [];
        foreach ($files as $key => $file) {
            $save_paths[] = $this->upload($file, $path, $prefix_name ? $prefix_name . '_' . ++$key : null);
        }

        return $save_paths;
    }

    /**
     * web端直传
     *
     * @param array $config
     *
     * @return array
     * @throws \Exception
     */
    public function webUpload(array $config = []): array
    {
        $this->directUploadConfig = array_merge($this->directUploadConfig, $config);
        $policy = $this->getPolicyBase64();

        return [
            'access_id' => $this->accessKeyId,
            'host'      => $this->bucket . '.' . $this->endPoint,
            'policy'    => $policy['base64_policy'],
            'signature' => base64_encode(hash_hmac('sha1', $policy['base64_policy'], $this->accessKeySecret, true)),
            'expire'    => $policy['expire_at'],
            'callback'  => $this->getCallbackBodyBase64(),
            'dir'       => $this->directUploadConfig['dir'],
        ];
    }

    /**
     * 微信小程序直传
     *
     * @param array $config
     *
     * @return array
     * @throws \Exception
     */
    public function miniProgramUpload(array $config = []): array
    {
        return $this->webUpload($config);
    }

    /**
     * app直传
     *
     * @param array $config
     *
     * @return array
     * @throws \AlibabaCloud\Client\Exception\ClientException
     * @throws \AlibabaCloud\Client\Exception\ServerException
     */
    public function appUpload(array $config): array
    {
        $this->directUploadConfig = array_merge($this->directUploadConfig, $config);

        AlibabaCloud::accessKeyClient($this->accessKeyId, $this->accessKeySecret)->regionId('cn-beijing')->name(
            'default'
        );
        $result = StsClient::v20150401()
            ->assumeRole()
            ->withRoleArn($this->directUploadConfig['RoleArn'])
            ->withRoleSessionName('douaiwan-sts')
            ->withPolicy(json_encode($this->directUploadConfig['policy']))
            ->withDurationSeconds(900)
            ->request();

        return $result->jsonSerialize()['Credentials'];
        /*
        return [
            'StatusCode'      => 200,   // 获取Token的状态，获取成功时，返回值是200。
            'AccessKeyId'     => '',    // Android、iOS移动应用初始化OSSClient获取的 AccessKey ID。
            'AccessKeySecret' => '',    // Android、iOS移动应用初始化OSSClient获取AccessKey Secret。
            'Expiration'      => '',    // 该Token失效的时间。Android SDK会自动判断Token是否失效，如果失效，则自动获取Token。
            'SecurityToken'   => '',    // Android、iOS移动应用初始化的Token。
        ];
        */
    }

    /**
     * 删除文件
     *
     * @param string $path
     *
     * @return bool
     */
    public function delete(string $path): bool
    {
        return (bool)$this->ossClient()->deleteObject($this->app->config['oss']['aliyun']['bucket'], $path);
    }

    /**
     * 复制文件
     *
     * @param string $path
     * @param string $new_path
     *
     * @return bool
     * @throws \OSS\Core\OssException
     */
    public function copy(string $path, string $new_path): bool
    {
        return (bool)$this->ossClient()->copyObject($this->bucket, $path, $this->bucket, $new_path);
    }

    /**
     * 移动文件
     *
     * @param string $path
     * @param string $new_path
     *
     * @return bool
     * @throws \OSS\Core\OssException
     */
    public function move(string $path, string $new_path)
    {
        $this->copy($path, $new_path);

        return $this->delete($path);
    }

    /**
     * 路径转url地址
     *
     * @param string $path  存储路径
     *
     * @return string
     */
    public function path2url(string $path): string
    {
        return sprintf(
            "%s%s.%s/%s",
            $this->useSSL ? 'https://' : 'http://',
            $this->bucket,
            trim($this->endPoint, '/'),
            trim($path, '/')
        );
    }

    /**
     * oss实例对象
     *
     * @return OssClient
     * @throws \OSS\Core\OssException
     */
    private function ossClient(): OssClient
    {
        return $this->client ?: $this->client = new OssClient(
            $this->accessKeyId,
            $this->accessKeySecret,
            $this->endPoint,
            $this->isCName,
            $this->securityToken
        );
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getPolicyBase64(): array
    {
        $expire_at = time() + $this->directUploadConfig['expire'];
        $base64_policy = base64_encode(
            json_encode(
                [
                    'expiration' => gmt_iso8601($expire_at),
                    'conditions' => [
                        [
                            0 => 'content-length-range',
                            1 => 0,
                            2 => $this->directUploadConfig['max_size'],
                        ],
                        [
                            0 => 'starts-with',
                            1 => '$key',
                            2 => $this->directUploadConfig['dir'],
                        ],
                    ],
                ]
            )
        );

        return compact('expire_at', 'base64_policy');
    }

    /**
     * @return string
     */
    private function getCallbackBodyBase64(): string
    {
        return base64_encode(
            json_encode(
                [
                    'callbackUrl'      => $this->directUploadConfig['callback'],
                    'callbackBody'     => 'filename=${object}&size=${size}&mimeType=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}',
                    'callbackBodyType' => 'application/x-www-form-urlencoded',
                ]
            )
        );
    }
}
