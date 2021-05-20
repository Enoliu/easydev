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
    // 微信相关配置
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
];
