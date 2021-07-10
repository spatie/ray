---
title: Yii2 
weight: 6
---

For Yii projects you can create a `ray.php` file in your project's `config` directory.

```php
<?php
// Save this in a file called "ray.php" in the config directory of your project

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
    
    /*
     * When this setting is enabled, the package will not try to format values sent to Ray.
     */
    'always_send_raw_values' => false,
];

```
