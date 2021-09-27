---
title: Laravel 
weight: 3
---

For Laravel projects you can create a `ray.php` file in your project directory (not in the `config` directory) using the following template as [the ray config file](/docs/ray/v1/configuration/general). Since the configuration file is developer specific, you might want to add it to the `.gitignore` of the project.

Note: if everyone working on the project needs the same configuration, you can put the file in the `config` directory as well.

```php
<?php
// Save this in a file called "ray.php" in the root directory of your project; not in the Laravel "config" directory

return [
    /*
    * This settings controls whether data should be sent to Ray.
    *
    * By default, `ray()` will only transmit data in non-production environments.
    */
    'enable' => env('RAY_ENABLED', true),

    /*
    * When enabled, all cache events  will automatically be sent to Ray.
    */
    'send_cache_to_ray' => env('SEND_CACHE_TO_RAY', false),

    /*
    * When enabled, all things passed to `dump` or `dd`
    * will be sent to Ray as well.
    */
    'send_dumps_to_ray' => env('SEND_DUMPS_TO_RAY', true),

    /*
    * When enabled all job events will automatically be sent to Ray.
    */
    'send_jobs_to_ray' => env('SEND_JOBS_TO_RAY', false),

    /*
    * When enabled, all things logged to the application log
    * will be sent to Ray as well.
    */
    'send_log_calls_to_ray' => env('SEND_LOG_CALLS_TO_RAY', true),

    /*
    * When enabled, all queries will automatically be sent to Ray.
    */
    'send_queries_to_ray' => env('SEND_QUERIES_TO_RAY', false),
    
    /**
     * When enabled, all duplicate queries will automatically be sent to Ray.
     */
    'send_duplicate_queries_to_ray' => env('SEND_DUPLICATE_QUERIES_TO_RAY', false),

    /*
    * When enabled, all requests made to this app will automatically be sent to Ray.
    */
    'send_requests_to_ray' => env('SEND_REQUESTS_TO_RAY', false),
    
    /*
    * When enabled, all views that are rendered automatically be sent to Ray.
    */
    'send_views_to_ray' => env('SEND_VIEWS_TO_RAY', false),

    /*
     * When enabled, all exceptions will be automatically sent to Ray.
     */
    'send_exceptions_to_ray' => env('SEND_EXCEPTIONS_TO_RAY', true),

    /*
    * The host used to communicate with the Ray app.
    * When using Docker on Mac or Windows, you can replace localhost with 'host.docker.internal'
    * When using Homestead with the VirtualBox provider, you can replace localhost with '10.0.2.2'
    * When using Homestead with the Parallels provider, you can replace localhost with '10.211.55.2'
    */
    'host' => env('RAY_HOST', 'localhost'),

    /*
    * The port number used to communicate with the Ray app.
    */
    'port' => env('RAY_PORT', 23517),

    /*
     * Absolute base path for your sites or projects in Homestead,
     * Vagrant, Docker, or another remote development server.
     */
    'remote_path' => env('RAY_REMOTE_PATH', null),

    /*
     * Absolute base path for your sites or projects on your local
     * computer where your IDE or code editor is running on.
     */
    'local_path' => env('RAY_LOCAL_PATH', null),
    
    /*
     * When this setting is enabled, the package will not try to format values sent to Ray.
     */
    'always_send_raw_values' => false,
];
```

## Docker
See [our Docker-specific configuration page](/docs/ray/v1/environment-specific-configuration/docker) for information about setting up Ray in combination with Docker. All changes also apply to a setup with Laravel.
