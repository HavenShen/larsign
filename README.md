# Api Authorized Signature Middleware for Laravel 5

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/HavenShen/larsign/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/HavenShen/larsign/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/HavenShen/larsign/badges/build.png?b=master)](https://scrutinizer-ci.com/g/HavenShen/larsign/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/HavenShen/larsign/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/HavenShen/larsign/?branch=master)
[![Build Status](https://travis-ci.org/HavenShen/larsign.svg?branch=master)](https://travis-ci.org/HavenShen/larsign)
[![Latest Stable Version](https://poser.pugx.org/HavenShen/larsign/v/stable.svg)](https://packagist.org/packages/HavenShen/larsign)
[![Latest Unstable Version](https://poser.pugx.org/HavenShen/larsign/v/unstable.svg)](https://packagist.org/packages/HavenShen/larsign)
[![Latest Stable Version](https://img.shields.io/packagist/v/HavenShen/larsign.svg?style=flat-square)](https://packagist.org/packages/HavenShen/larsign)
[![Total Downloads](https://img.shields.io/packagist/dt/HavenShen/larsign.svg?style=flat-square)](https://packagist.org/packages/HavenShen/slim-born)
[![License](https://img.shields.io/packagist/l/HavenShen/larsign.svg?style=flat-square)](https://packagist.org/packages/HavenShen/larsign)

## About

The `larsign` package authorized signature server.

## Features

* Handles larsign requests

## Installation

### Laravel

Require the `havenshen/larsign` package in your `composer.json` and update your dependencies:
```sh
$ composer require havenshen/larsign
```

Add the `HavenShen\Larsign\LarsignServiceProvider` to your `config/app.php` providers array:
```php
HavenShen\Larsign\LarsignServiceProvider::class,
```

Add the `HavenShen\Larsign\LarsignFacade` to your `config/app.php` aliases array:
```php
'Larsign' => HavenShen\Larsign\LarsignFacade::class,
```

### Lumen

Require the `havenshen/larsign` package in your `composer.json` and update your dependencies:
```sh
$ composer require havenshen/larsign
```

Register the package with lumen in `bootstrap/app.php` with the following:
```php
$app->register(HavenShen\Larsign\LarsignServiceProvider::class);
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

The defaults are set in `config/larsign.php`. Copy this file to your own config directory to modify the values. If you're using Laravel, you can publish the config using this command:

```sh
$ php artisan vendor:publish --provider="HavenShen\Larsign\LarsignServiceProvider"
```
    
If you're using Lumen, Copy the configuration [larsign.php](src/config/larsign.php) to your config/ directory

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

Add api route in `routes/api.php` Copy this.

```php
Route::middleware(['auth.larsign'])->group(function () {
    Route::get('/larsign', function () {
    return [
        'message' => 'done.'
    ]);
});
```
or

```php
Route::get('/larsign', function () {
    return [
        'message' => 'done.'
    ];
})->middleware('auth.larsign');
```
## Client

Generate `Larsign` signatures

1. Assume the following management credentials:

```shell
AccessKey = "test"
SecretKey = "123456"
```

2. Call interface address:

```shell
url = "https://larsign.dev/api/v1/test?page=1"
```

3. The original string to be signed:
> note: the time-stamping followed by a newline [currenttime + voucher valid seconds]

```shell
signingStr = "/api/v1/test?page=1\n1510986405"
```

4. Base64 url safe encode:

```shell
signingStrBase64UrlSafeEncode = "L2FwaS92MS90ZXN0P3BhZ2U9MQoxNTEwOTg2NDY1"
```

5. `hmac_sha1` carries `SecretKey` encryption then base64 url safe encode:

```shell
sign = "MLKnFIdI-0TOQ4mHn5TyCcmWACU="
```


6. The final administrative credentials are:
> note: stitching `headerName` Space `AccessKey`:`sign`:`signingStrBase64UrlSafeEncode`

```shell
larsignToken = "Larsign test:MLKnFIdI-0TOQ4mHn5TyCcmWACU=:L2FwaS92MS90ZXN0P3BhZ2U9MQoxNTEwOTg2NDY1"
```

7. Add http header:
> note: header key in `config/larsign.php -> headerName` 

```shell
Larsign:Larsign test:MLKnFIdI-0TOQ4mHn5TyCcmWACU=:L2FwaS92MS90ZXN0P3BhZ2U9MQoxNTEwOTg2NDY1
```

## Client signature authorization failed

```shell
Http Response: 403
```

## Testing

```shell
$ phpunit
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

