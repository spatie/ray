---
title: Yii2
weight: 6
---

If you use Yii2, this is the way.

## Installing the package

```bash
composer require spatie/yii-ray
```

By installing Ray this way it will also be installed in your production environment. This way your application will not break if you forget to remove a `ray` call.  The package will not attempt to transmit information to Ray when the app environment is set to anything other than `dev`.

You could opt to install `yii-ray` as a dev dependency. If you go this route, make sure to remove every `ray` call in the code before deploying.

```bash
composer require spatie/yii-ray --dev
```

## Configuring Ray

To configure Ray settings, create a `config/ray.php` file in your project.

```php
<?php

return [
    /*
    * This settings controls whether data should be sent to Ray.
    *
    * By default, `ray()` will only transmit data in non-production environments.
    */
    'enable' => true,

    /*
    * The host used to communicate with the Ray app.
    * For usage in Docker on Mac or Windows, you can replace host with 'host.docker.internal'
    * For usage in Homestead on Mac or Windows, you can replace host with '10.0.2.2'
    */
    'host' => 'localhost',

    /*
    * The port number used to communicate with the Ray app.
    */
    'port' => 23517,

    /*
     * Absolute base path for your sites or projects in Homestead,
     * Vagrant, Docker, or another remote development server.
     */
    'remote_path' => null,

    /*
     * Absolute base path for your sites or projects on your local
     * computer where your IDE or code editor is running on.
     */
    'local_path' => null,
];

```
