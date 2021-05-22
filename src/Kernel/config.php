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
            'app_id'     => 'xxxx',                 // 应用APPID
            'mch_id'     => 'your-mch-id',          // 商户号
            'key'        => 'key-for-signature',    // API 密钥
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path'  => 'path/to/your/cert.pem',    // XXX: 绝对路径！！！！
            'key_path'   => 'path/to/your/key',     // XXX: 绝对路径！！！！
            'notify_url' => '默认的订单回调地址',       // 你也可以在下单时单独设置来想覆盖它
        ],
    ],
];
