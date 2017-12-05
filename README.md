# Api Authorized Signature Middleware for Laravel 5


## About

The `larsign` package authorized signature server.

## Features

* Handles larsign requests

## Installation

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

1. Assume the following management credentials

```sh
AccessKey = "test"
SecretKey = "123456"
```

2. Call interface address

```sh
url = "https://larsign.dev/api/v1/test?page=1"
```

3. The original string to be signed
> note: the time-stamping followed by a newline [currenttime + voucher valid seconds]

```sh
signingStr = "/api/v1/test?page=1\n1510986405"
```

4. Base64 url safe encode

```sh
signingStrBase64UrlSafeEncode = "L2FwaS92MS90ZXN0P3BhZ2U9MQoxNTEwOTg2NDY1"
```

5. `hmac_sha1` carries `SecretKey` encryption then base64 url safe encode

```sh
sign = "MLKnFIdI-0TOQ4mHn5TyCcmWACU="
```


6. The final administrative credentials are 
> note: stitching `Larsign` Space `AccessKey`:`sign`:`signingStrBase64UrlSafeEncode`

```sh
larsignToken = "Larsign test:MLKnFIdI-0TOQ4mHn5TyCcmWACU=:L2FwaS92MS90ZXN0P3BhZ2U9MQoxNTEwOTg2NDY1"
```

7. Add http header
> note: header key in `config/larsign.php -> headerName` 

```sh
Larsign:Larsign test:MLKnFIdI-0TOQ4mHn5TyCcmWACU=:L2FwaS92MS90ZXN0P3BhZ2U9MQoxNTEwOTg2NDY1
```

## Client signature authorization failed

```sh
Http Response: 403
```

## Testing

```sh
$ phpunit
```

## License

Released under the MIT License, see [LICENSE](LICENSE).

