<?php

namespace Three\LaravelResponse;

use Three\LaravelResponse\Contract\ResponseFormat;
use Three\LaravelResponse\Exceptions\Handler;
use Three\LaravelResponse\Support\Format;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->setupConfig();

        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            Handler::class
        );

        $this->app->singleton(ResponseFormat::class, function ($app) {
            $formatter = $app->config->get('response.format.class');
            $config = $app->config->get('response.format.config');

            return match (true) {
                class_exists($formatter) && is_subclass_of($formatter, ResponseFormat::class) => new $formatter($config),
                default => new Format($config),
            };
        });
    }

    protected function setupConfig()
    {
        $path = dirname(__DIR__).'/config/response.php';

        if ($this->app->runningInConsole()) {
            $this->publishes([$path => config_path('response.php')], 'response');
        }

        $this->mergeConfigFrom($path, 'response');
    }
}
