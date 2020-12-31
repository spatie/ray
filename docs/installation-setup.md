---
title: Installation and setup
weight: 2
---

You can buy a license for the Mac and Windows app [in our store](https://spatie.be/products/ray).

To send debugging information to the Ray app you can install the Laravel specific, or framework agnostic PHP package.

## Installation in Laravel

If you use Laravel, this is the way.

```bash
composer require spatie/laravel-ray
```

By installing Ray this way it will also be installed in your production environment. This way your application will not break if you forget to remove a `ray` call.  The package will only attempt to transmit information to Ray when `APP_DEBUG` is set to `true`. 

You could opt to install `laravel-ray` as a dev dependency. If you go this route, make sure to remove every `ray` call in the code before deploying.

```bash
composer require spatie/laravel-ray --dev
```

## Publishing the config file (optional)

Ray will work well out the box, but if you want to customize some behaviour you can optionally publish the config file.

```bash
php artisan vendor:publish --provider="Spatie\LaravelRay\RayServiceProvider" --tag="config"
```

This is the content of the file that will be published at `config/ray.php`:

```php
return [
    /*
     *  By default, this package will only try to transmit info to Ray
     *  when APP_DEBUG is set to `true`.
     */
    'enable' => (bool) env('APP_DEBUG', false),

    /*
     * The port number to communicate with Ray.
     */
    'port' => 23517,

    /*
     * When enabled, all things logged to the application log
     * will be sent to Ray as well.
     */
    'send_log_calls_to_ray' => true,

    /*
     * When enabled, all things passed to `dump` or `dd`
     * will be sent to Ray as well.
     */
    'send_dumps_to_ray' => true,
];
```

All options should be self-explanatory.

## Installation in a framework agnostic project

To start using Ray in a non-laravel app, install the `ray` package.

```bash
php artisan spatie/ray
```

You should be able to use the `ray` function without any other steps.

To customize the port number, you should first create a new `Client`. Pass the port number you'd like to use to its constructor. Next, you should pass the client to the `useClient` function on `Ray`.

```php
$client = \Spatie\Ray\Client::create('1234');

\Spatie\Ray\Ray::useClient($client);

// from now on, all requests to ray will be made to port 1234
```
