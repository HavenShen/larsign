<?php

namespace HavenShen\Larsign;

use HavenShen\Larsign\LarsignService;
use Illuminate\Support\ServiceProvider;

/**
 * LarsignServiceProvider
 *
 * @author    Haven Shen <havenshen@gmail.com>
 * @copyright    Copyright (c) Haven Shen
 */
class LarsignServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([$this->configPath() => config_path('larsign.php')]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'larsign');

        $this->app->bind(LarsignService::class, function ($app) {
            return new LarsignService($app['config']->get('larsign'));
        });

        $this->app->alias(LarsignService::class, 'larsign');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['larsign'];
    }

    protected function configPath()
    {
        return __DIR__ . '/../config/larsign.php';
    }
}
