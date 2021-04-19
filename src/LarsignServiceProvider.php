<?php

namespace HavenShen\Larsign;

use HavenShen\Larsign\LarsignService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

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
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$this->configPath() => config_path('larsign.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('larsign');
        }
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

        if ($this->app instanceof LaravelApplication) {
            $this->app->alias(LarsignService::class, 'larsign');
        } elseif ($this->app instanceof LumenApplication) {
            if (!class_exists('Larsign')) {
                class_alias(LarsignFacade::class, 'Larsign');
            }
        }
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
