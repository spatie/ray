# The Ray PHP client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/ray.svg?style=flat-square)](https://packagist.org/packages/spatie/ray)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/ray/run-tests?label=tests)](https://github.com/spatie/ray/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/ray.svg?style=flat-square)](https://packagist.org/packages/spatie/ray)

This package can be used to send item to Ray. Using the `send` method you can send anything you want to be displayed.

```php
\Spatie\Ray\Ray::create()->send('a string', ['an array'], new MyClass())
```

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/package-skeleton-php.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/package-skeleton-php)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/ray
```

## Usage

You can use `send` method you can send anything you want to be displayed.

```php
\Spatie\Ray\Ray::create()->send('a string', ['an array'], new MyClass())
```

### Setting the size and color

You can use `color` and `size` to format a thing displayed in Ray.

```php
\Spatie\Ray\Ray::create()->send('a large green string')->color('green')->size('lg')
```

### Clear screen

You can use `clearScreen` to clear the screen in Ray.

```php
\Spatie\Ray\Ray::create()->newScreen())
```

### Customizing the endpoint

You can use `clearScreen` to clear the screen in Ray.

```php
$client = new \Spatie\Ray\Client('https://otherdomain.com', $portNumber)

\Spatie\Ray\Ray::create($client)->newScreen())
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
