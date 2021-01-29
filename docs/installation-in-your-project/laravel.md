---
title: Laravel
weight: 3
---

If you use Laravel, this is the way.

## Installing the package

```bash
composer require spatie/laravel-ray
```

By installing Ray this way it will also be installed in your production environment. This way your application will not break if you forget to remove a `ray` call.  The package will not attempt to transmit information to Ray when the app environment is set to `production`.

You could opt to install `laravel-ray` as a dev dependency. If you go this route, make sure to remove every `ray` call in the code before deploying.

```bash
composer require spatie/laravel-ray --dev
```

## Creating a config file

Optionally, you can run an artisan command to publish [the config file](/docs/ray/v1/configuration/laravel) in to the project root.

```bash
php artisan ray:publish-config
```

You can also add an option for 'docker' or 'homestead' to give a base configuration for those dev environments.

```bash
php artisan ray:publish-config --docker
# or
php artisan ray:publish-config --homestead
```

## Using Ray in an Orchestra powered test suite

In order to use a Laravel specific functionality you must call Ray's service provider in your base test case.

```php
// add this to your base test case

protected function getPackageProviders($app)
{
    return [
        \Spatie\LaravelRay\RayServiceProvider::class,
    ];
}
```
