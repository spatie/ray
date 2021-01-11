---
title: Configuring Ray
weight: 4
---

You can optionally configure Ray by creating a file named `ray.php` in your project directory.  We recommend putting `ray.php` in your `.gitignore` so your fellow developers can use their own configuration.

Ray will also look for `ray.php` in all parent directories of your project. To configure multiple Ray for multiple projects in one go, you could create a `ray.php` file in the directory where all your projects reside in.

## Configuration in framework agnostic projects

In framework agnostic projects you can use this template.

```php
// save this in a file called "ray.php"

return [
    /*
     *  The host used to communicate with the Ray app.
     */
    'host' => 'localhost',

    /*
     *  The port number used to communicate with the Ray app. 
     */
    'port' => 23517,
    
    /*
     *  Absolute base path for your sites or projects in Homestead, Vagrant, Docker, or another remote development server.
     */
    'remote_path' => null,
    
    /*
     *  Absolute base path for your sites or projects on your local computer where your IDE or code editor is running on. 
     */
    'local_path' => null,
];
```

### Configuration in Laravel apps

Alternatively for Laravel projects you can create a `ray.php` file in your project directory (not in the `config` directory) using the following template.

You can use [Laravel environment variables](https://laravel.com/docs/master/configuration#environment-configuration) to change the configuration values, if desired. Doing so can make it easier for your fellow developers to use their own configuration.

Alternatively for Laravel projects you can create a `ray.php` file and use the following template:

```php
// save this in a file called "ray.php" in the root directory of your project; not in the Laravel "config" directory

return [
    /*
    * This settings controls whether data should be sent to Ray.
    *
    * By default, `ray()` will only transmit data in non-production environments.
    */
    'enable' => env('RAY_ENABLED', true),

    /*
    * When enabled, all things logged to the application log
    * will be sent to Ray as well.
    */
    'send_log_calls_to_ray' => env('SEND_LOG_CALLS_TO_RAY', true),

    /*
    * When enabled, all things passed to `dump` or `dd`
    * will be sent to Ray as well.
    */
    'send_dumps_to_ray' => env('SEND_DUMPS_TO_RAY', true),

    /*
    * The host used to communicate with the Ray app.
    * For usage in Docker on Mac or Windows, you can replace host with 'host.docker.internal'
    * For usage in Homestead on Mac or Windows, you can replace host with '10.0.2.2'
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
];
```

### Using Ray and Homestead

To use Ray with Homestead you should replace the host in the config file above with IP `10.0.2.2`, or the IP your are using for your Homestead environment.

### Using Ray and Docker

When developing using Docker, the Ray host should point to the internal IP of your Docker host by using 'host.docker.internal'. 

```php
// save this in a file called "ray.php"

return [
    /*
     *  The host used to communicate with the Ray app.
     */
    'host' => 'host.docker.internal',

    /*
     *  The port number used to communicate with the Ray app. 
     */
    'port' => 23517,
    
    /*
     *  Absolute base path for your sites or projects in Homestead, Vagrant, Docker, or another remote development server.
     */
    'remote_path' => null,
    
    /*
     *  Absolute base path for your sites or projects on your local computer where your IDE or code editor is running on. 
     */
    'local_path' => null,
];
```


On Linux, you will need to add an 'extra_hosts' parameter to your container definitions to expose 'host.docker.internal'.
```
#docker-compose.yml

services:
  site:
    image: nginx:stable-alpine
    container_name: nginx
    ports:
      - "80:80"
    depends_on:
      - php
      - db
    networks:
      - packt-api
    extra_hosts: # <-- this is required
      - "host.docker.internal:host-gateway" # <-- this is required
```

