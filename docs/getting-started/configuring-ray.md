---
title: Configuring Ray
weight: 4
---

You can optionally configure Ray by creating a file named `ray.php` in your project directory.  We recommend putting `ray.php` in your `.gitignore` so your fellow developers can use their own configuration.

Ray will also look for `ray.php` in all parent directories of your project. To configure multiple Ray for multiple projects in one go, you could create a `ray.php` file in the directory where all your projects reside in.

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
]
```


For Laravel projects use this template:

```php
// save this in a file called "ray.php"

return [
    /*
     * This settings controls whether data should be sent to Ray.
     * 
     * By default, `ray()` will only transmit data in non-production environments.
     */
    'enable' => true,

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
    
    /*
     *  The url used to communicate with the Ray app.
     */
    'host' => 'http://localhost',
    
    /*
     *  The port number used to communicate with the Ray app. 
     */
    'port' => 23517,
];
```
