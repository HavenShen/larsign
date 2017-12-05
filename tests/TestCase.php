<?php
namespace Tests;

use HavenShen\Larsign\LarsignFacade;
use HavenShen\Larsign\LarsignServiceProvider;

/**
 * TestCase
 *
 * @author    Haven Shen <havenshen@gmail.com>
 * @copyright    Copyright (c) Haven Shen
 */
class TestCase extends \Orchestra\Testbench\TestCase
{
    protected $signatue;
    protected $baseUrl;
    protected $webRoute;
    protected $apiRoute;
    protected $config;
    protected $applicationMock;
    protected $serviceProvider;

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        // if (empty($this->config)) {
        //     $this->config = require __DIR__.'/../config/larsign.php';
        // }
        // $app['config']->set('larsign', $this->config);

        $app['config']['larsign'] = [
            'headerName' => 'Larsign',
            'accessKey' => 'soudryg08yoa4wt',
            'secretKey' => 'sry5yw4yoij[09',
        ];

    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LarsignServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Larsign' => LarsignFacade::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $this->baseUrl = 'http://larsign.t.dev';

        $this->webRoute = '/web/test';
        $this->apiRoute = '/api/test';

        $router = $app['router'];

        $this->addWebRoutes($router);
        $this->addApiRoutes($router);
    }

    /**
     * @param Router $router
     */
    protected function addWebRoutes($router)
    {
        $router->group(['middleware' => \HavenShen\Larsign\HandleLarsign::class], function () use ($router) {
            $router->get($this->webRoute, [
                'as' => 'web.test',
                'uses' => function () {
                    return 'test';
                }
            ]);
        });
    }

    /**
     * @param Router $router
     */
    protected function addApiRoutes($router)
    {
        $router->group(['middleware' => \HavenShen\Larsign\HandleLarsign::class], function () use ($router) {
            $router->get($this->apiRoute, [
                'as' => 'api.test',
                'uses' => function () {
                    return 'test';
                }
            ]);
        });
    }
}