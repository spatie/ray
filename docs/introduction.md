---
title: Introduction
weight: 1
---

Ray is a beautiful, lightweight desktop app that helps you debug your app. There's a [free demo](https://myray.app) available.

After installing the [framework agnostic](/docs/ray/v1/getting-started/installation-in-a-framework-agnostic-php-project) or [Laravel specific package](https://spatie.be/docs/ray/v1/getting-started/installation-in-laravel), you can use the `ray()` function to quickly dump stuff. Any variable(s) that you pass to `ray` will be displayed.

Here's an example:

```
ray('Hello world');

ray(['a' => 1, 'b' => 2])->color('red');

ray('multiple', 'argments', 'are', 'welcome');

ray()->showQueries();

User::firstWhere('email', 'john@example.com');
```

Here's how that looks like in Ray.

![screenshot](/docs/ray/v1/images/intro.jpg)

There are many other helper functions available on Ray that allow you to display things that can help you debug such as [runtime and memory usage](/docs/ray/v1/usage/in-a-framework-agnostic-project#measuring-performance-and-memory-usage), [queries that were executed](/docs/ray/v1/usage/in-laravel#showing-queries), and much more. 

## Getting started

To get started you should buy a license for the Ray app [in our store](/products/ray), and [install the free package into your app](/docs/ray/v1/getting-started/installation-in-a-framework-agnostic-php-project).
