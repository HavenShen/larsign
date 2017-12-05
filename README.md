# Api Authorized Signature Middleware for Laravel 5


## About

The `larsign` package signature server.

## Features

* Handles larsign requests

## Installation

Require the `havenshen/larsign` package in your `composer.json` and update your dependencies:
```sh
$ composer require havenshen/larsign
```

Add the HavenShen\Larsign\LarsignServiceProvider to your `config/app.php` providers array:
```php
HavenShen\Larsign\LarsignServiceProvider::class,
```

Add the HavenShen\Larsign\LarsignFacade to your `config/app.php` aliases array:
```php
'Larsign' => HavenShen\Larsign\LarsignFacade::class,
```

## Global usage

To allow Larsign for all your routes, add the `HandleLarsign` middleware in the `$middleware` property of  `app/Http/Kernel.php` class:

```php
protected $middleware = [
    // ...
    \HavenShen\Larsign\HandleLarsign::class,
];
```

## Group middleware

If you want to allow Larsign on a specific middleware group or route, add the `HandleLarsign` middleware to your group:

```php
protected $middlewareGroups = [
    'web' => [
       // ...
    ],

    'api' => [
        // ...
        \HavenShen\Larsign\HandleLarsign::class,
    ],
];
```

## Application route middleware

If you want to allow Larsign on a specific application middleware or route, add the `HandleLarsign` middleware to your application route:

```php
protected $routeMiddleware = [
    // ...
    'auth.larsign' => \HavenShen\Larsign\HandleLarsign::class,
];
```

## Configuration

The defaults are set in `config/larsign.php`. Copy this file to your own config directory to modify the values. You can publish the config using this command:
```sh
$ php artisan vendor:publish --provider="HavenShen\Larsign\LarsignServiceProvider"
```

    
```php
return [
    /*
     |--------------------------------------------------------------------------
     | Larsign
     |--------------------------------------------------------------------------
     |
     */
    'headerName' => env('LARSIGN_HEADER_NAME', 'Larsign'),
    'accessKey' => env('LARSIGN_ACCESS_KEY', ''),
    'secretKey' => env('LARSIGN_SECRET_KEY', ''),
];
```

## License

Released under the MIT License, see [LICENSE](LICENSE).

