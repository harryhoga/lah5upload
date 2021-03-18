<?php

namespace Hoga\lah5upload;

use Hoga\lah5upload\Achieves\Aliyun;
use Encore\Admin\Admin;
use Hoga\lah5upload\Interfaces\ThirdPartyUpload;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class lah5uploadServiceProvider extends ServiceProvider
{

    /**
     * {@inheritdoc}
     */
    public function boot(lah5upload $extension)
    {
        if (!lah5upload::boot()) {
            return;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'lah5upload');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [
                    $assets => public_path('vendor/laravel-admin-ext/lah5upload'), //静态文件
                    $assets . '/config/' => config_path(''), //配置文件
                ],
                'lah5upload'
            );
        }
        Admin::booting(function () {
            Admin::js('vendor/laravel-admin-ext/lah5upload/js/lib/md5/md5.js?v=' . rand(1, 100));
            Admin::js('vendor/laravel-admin-ext/lah5upload/js/lah5upload.js?v=' . rand(1, 100));
            Admin::js('vendor/laravel-admin-ext/lah5upload/js/ali-oss-sdk/aliyun-oss-sdk.min.js');
            Admin::css('vendor/laravel-admin-ext/lah5upload/css/lah5upload.css');
        });
        $this->app->booted(function () {
            lah5upload::routes(__DIR__ . '/../routes/web.php');
        });
    }

    /**
     * 注册服务提供者
     */
    function register()
    {
        $this->app->bind(ThirdPartyUpload::class, function (Application $application) {
            $type_dev = config('lah5upload.type_dev');
            $dev_map = [
                'ali' => Aliyun::class
            ];
            return new $dev_map[$type_dev]($type_dev);
        });
    }
}
