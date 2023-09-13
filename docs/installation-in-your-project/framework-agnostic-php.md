---
title: Framework agnostic PHP
weight: 2
---

To communicate with the Ray desktop app, we're going to use a function named `ray()`. 

## Global installation

To make the `ray()`, `dump()` and `ray()` functions available in any PHP file and project on your system, you can install the `spatie/global-ray` package.

```bash
composer global require spatie/global-ray
global-ray install
```

You can now use all of `ray()`'s [framework agnostic capabilities](https://spatie.be/docs/ray/v1/usage/framework-agnostic-php-project). 

To use framework specific functionality, such as [viewing queries in Laravel](https://spatie.be/docs/ray/v1/usage/laravel#showing-queries), or [displaying mails in WordPress](https://spatie.be/docs/ray/v1/usage/wordpress#displaying-mails), you should still [install the relevant package or library](https://spatie.be/docs/ray/v1/installation-in-your-project/introduction).

If a framework specific package is detected, it will be used instead of the global Ray.

## Installation in a single project

To start using Ray in a single PHP project, install the `ray` package in the project.

```bash
composer require spatie/ray
```

You should be able to use the `ray` function without any other steps.

If you use Laravel, you should use install [the Laravel specific package](/docs/ray/v1/installation-in-your-project/laravel) instead of `spatie/ray`.
