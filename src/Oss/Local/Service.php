<?php


namespace Enoliu\EasyDev\Oss\Local;

use think\facade\Filesystem;
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

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * 单文件上传
     *
     * @param File        $file  文件
     * @param string      $path  储存目录
     * @param string|null $name  自定义文件名(不带后缀)，不传则自动生成
     *
     * @return string
     */
    public function upload(File $file, string $path, string $name = null): string
    {
        if (! $name) $name = md5((string)microtime(true) . mt_rand(1000, 9999));

        return Filesystem::disk('public')->putFileAs($path, $file, $name . '.' . $file->getOriginalExtension());
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
     * 删除文件
     *
     * @param string $path
     *
     * @return bool
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function delete(string $path): bool
    {
        return Filesystem::disk('public')->delete($path);
    }

    /**
     * 复制文件
     *
     * @param string $path
     * @param string $new_path
     *
     * @return bool
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function copy(string $path, string $new_path): bool
    {
        return Filesystem::disk('public')->copy($path, $new_path);
    }

    /**
     * 移动文件
     *
     * @param string $path
     * @param string $new_path
     *
     * @return bool
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function move(string $path, string $new_path)
    {
        $this->copy($path, $new_path);

        return $this->delete($path);
    }


}
