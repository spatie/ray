---
title: WordPress
weight: 4
---

There are several ways to install Ray in WordPress.

## Manually cloning the repo

Inside the `wp-contents/plugin` directory run this command

```bash
git clone git@github.com:spatie/wordpress-ray
```

## Installing Ray via the WordPress admin UI

Ray is also registered as [a plugin on WordPress.org](https://wordpress.org/plugins/spatie-ray/). In the admin section of WordPress, go to "Plugins" > "Add New", and search for "Spatie Ray".

![screenshot](/docs/ray/v1/images/wp-install.png)

Install and activate the plugin.

## Setting Environment variable

When developing locally you should have `WP_ENVIRONMENT_TYPE` set as `local` in your `wp-config.php` otherwise Ray won't work.

```php
define( 'WP_ENVIRONMENT_TYPE', 'local' );
```
