<?php


namespace Enoliu\EasyDev\Http\Base;

use GuzzleHttp\Client;

/**
 * Class Service
 *
 * @package Enoliu\EasyDev\Http\Base
 */
class Service
{
    /**
     * @var
     */
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * GET请求
     *
     * @param string $url    请求地址
     * @param array  $query  查询参数:[key => value]
     *
     * @return mixed
     */
    public function get(string $url, array $query = [])
    {
        $response = $this->httpClient()->get(
            $url,
            [
                'query' => $query,
            ]
        );

        return json_decode($response->getBody(), true);
    }

    /**
     * POST请求
     *
     * @param string $url   请求地址
     * @param array  $data  提交数据:[key => value]
     */
    public function post(string $url, array $data = [])
    {
        $response = $this->httpClient()->post(
            $url,
            [
                'body' => $data,
            ]
        );

        return json_decode($response->getBody(), true);
    }

    /**
     * PUT请求
     *
     * @param string $url   请求地址
     * @param array  $data  更新数据:[key => value]
     */
    public function put(string $url, array $data = [])
    {
        $response = $this->httpClient()->put(
            $url,
            [
                'body' => $data,
            ]
        );

        return json_decode($response->getBody(), true);
    }

    /**
     * DELETE请求
     *
     * @param string $url   请求地址
     * @param array  $data  删除数据:[key => value]
     */
    public function delete(string $url, array $data = [])
    {
        $response = $this->httpClient()->delete(
            $url,
            [
                'body' => $data,
            ]
        );

        return json_decode($response->getBody(), true);
    }

    /**
     * 设置header
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setHeader(string $key, $value)
    {
        $this->app->config['http']['headers'][$key] = $value;

        return $this;
    }

    /**
     * 设置多个header
     *
     * @param array $headers  header数组:[key => value]
     *
     * @return $this
     */
    public function setHeaders(array $headers = [])
    {
        $this->app->config['http']['headers'] = array_merge($this->app->config['http']['headers'] ?? [], $headers);

        return $this;
    }

    /**
     * 设置请求超时时间
     *
     * @param int $timeout
     *
     * @return $this
     */
    public function setTimeout(int $timeout = 10)
    {
        $this->app->config['http']['timeout'] = $timeout;

        return $this;
    }

    /**
     * 获取http请求实例
     *
     * @return Client
     */
    private function httpClient(): Client
    {
        return new Client($this->app->config['http']);
    }
}
