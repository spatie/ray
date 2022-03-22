---
title: Craft CMS 
weight: 7
---

For Craft CMS projects you can create a `craft-ray.php` file in your project's `config` directory.

```php
<?php
// Save this in a file called "craft-ray.php" in the config directory of your project

use craft\helpers\App;

return [
    /*
    * This settings controls whether data should be sent to Ray.
    *
    * By default, `ray()` will only transmit data in non-production environments.
    * Add `RAY_ENABLED=true` in your .env file.
    */
    'enable' => App::parseBooleanEnv('$RAY_ENABLED'),

    /*
    * The host used to communicate with the Ray app.
    * For usage in Docker on Mac or Windows, you can replace host with 'host.docker.internal'
    * For usage in Homestead on Mac or Windows, you can replace host with '10.0.2.2'
    * Add `RAY_HOST=localhost` in your .env file.
    */
    'host' => App::env('RAY_HOST'),

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
    
    /*
     * When this setting is enabled, the package will not try to format values sent to Ray.
     */
    'always_send_raw_values' => false,
];

```
