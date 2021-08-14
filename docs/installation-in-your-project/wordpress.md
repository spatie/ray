---
title: WordPress
weight: 4
---

There are several ways to install Ray in WordPress.

## Manually cloning the repo

Inside the `wp-contents/plugins` directory run this command

```bash
git clone git@github.com:spatie/wordpress-ray
```

## Installing Ray via the WordPress admin UI

Ray is also registered as [a plugin on WordPress.org](https://wordpress.org/plugins/spatie-ray/). In the admin section of WordPress, go to "Plugins" > "Add New", and search for "Spatie Ray".

![screenshot](/docs/ray/v1/images/wp-install.png)

Install and activate the plugin.

## Must use Plugins

By default Wordpress loads your plugins in the following order:
- Checks for any must-use plugins directory (default = /wp-content/mu-plugins).
- Then, if you're running a multisite installation, it checks for plugins that are network-activated and loads those.
- Then it checks for all other active plugins by looking at the active_plugins entry of the wp_options database table, and loops through those. The plugins will be listed alphabetically.

If you wish to debug your plugins within the Ray app it is recommended that you install the plugin into your `/wp-content/mu-plugins` directory. Further details on Must Use Plugins can be [found here](https://wordpress.org/support/article/must-use-plugins/):

To install, inside the `wp-content/mu-plugins` directory run this command

```bash
git clone git@github.com:spatie/wordpress-ray
```

You'll then need to create `ray-loader.php` within `/wp-content/mu-plugins` and include the following code:

```php
require WPMU_PLUGIN_DIR.'/wordpress-ray/wp-ray.php';
```

## Setting Environment variable

When developing locally you should have `WP_ENVIRONMENT_TYPE` set as `local` in your `wp-config.php` otherwise Ray won't work.

```php
define( 'WP_ENVIRONMENT_TYPE', 'local' );
```

