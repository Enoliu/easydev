<?php

return [
    // rabbitMQ配置信息
    'rabbitMQ' => [
        'host'     => env('rabbitMQ.host', '127.0.0.1'),
        'port'     => env('rabbitMQ.port', '5672'),
        'user'     => env('rabbitMQ.user', 'guest'),
        'password' => env('rabbitMQ.password', 'guest'),
        'vhost'    => env('rabbitMQ.vhost', '/'),
    ],
    // 订单相关配置
    'order'    => [
        'model'   => '',    // 订单模型
        'timeout' => 30 * 60,   // 订单支付超时时间：30分钟
    ],
    // http相关配置，参考guzzleHttp
    'http'     => [
        'timeout' => 10,
    ],
    // OSS配置
    'oss'      => [
        'aliyun' => [
            'access_id'  => '',
            'access_key' => '',
            'end_point'  => '',
            'bucket'     => '',
            'is_cname'   => false,
            'use_ssl'    => false,
            'token'      => '',
        ],
        'local'  => [],
    ],
    // 短信相关配置
    'sms'      => [
        'aliyun' => [
            'access_key'        => '',
            'access_key_secret' => '',
            'region_id'         => 'cn-hangzhou',
            'sign_name'         => '',
        ],
    ],
    // 支付相关配置
    'payment'  => [
        'wechat' => [
            // 必要配置
            'app_id'        => 'xxxx',                     // 应用APPID
            'mch_id'        => 'your-mch-id',              // 商户号
            'key'           => 'key-for-signature',        // API 密钥
            'response_type' => 'array',                    // 数据返回类型
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path'     => 'path/to/your/cert.pem',    // XXX: 绝对路径！！！！
            'key_path'      => 'path/to/your/key',         // XXX: 绝对路径！！！！
            'notify_url'    => '默认的订单回调地址',           // 你也可以在下单时单独设置来想覆盖它
        ],
        'alipay' => [
            'app_id'                => '',  // 支付宝appid
            'alipay_public_key'     => '',  // 支付宝公钥
            'merchant_private_key'  => '',  // 商户秘钥
            'notify_url'            => '',  // 回调地址
            // 通常只需配置上面几个即可
            'merchant_cert_path'    => '',  // 商户证书地址：绝对路径
            'merchant_cert_sn'      => '',  // 商户证书编号
            'alipay_cert_path'      => '',  // 支付宝证书地址：绝对路径
            'alipay_cert_sn'        => '',  // 支付宝证书编号
            'alipay_root_cert_path' => '',  // 阿里支付根证书：绝对路径
            'alipay_root_cert_sn'   => '',  // 支付宝根证书编号
            'encrypt_key'           => '',  // 加密密钥
        ],
    ],
];
