---
title: WordPress
weight: 4
---

There are several ways to install Ray in WordPress.

By default, Ray is disabled in production environments, and will only transmit data in non-production environments. If you want to use Ray in a production environment, you must explicitly enable it with `ray()->enable()`.

## Manually cloning the repo

Inside the `wp-contents/plugin` directory run this command

```bash
git clone git@github.com/spatie/wordpress-ray
```

## Installing Ray via the WordPress admin UI

**Our plugin is currently under review, and will be available soon**

In the admin section of WordPress, go to "Plugins" > "Add New", and search for "Spatie Ray".

Install and activate the plugin.
