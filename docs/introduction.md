---
title: Introduction
weight: 1
---

Ray is a beautiful, lightweight app that helps you debug your app. You can use the `ray()` function to quickly dump stuff. Any variable(s) that you pass to `ray` will be displayed.

Here's an example:

```
ray('Hello world');
ray(['a' => 1, 'b' => 2])->color('red');
ray('multiple', 'argments', 'are', 'welcome');
ray()->logQueries($userModel);
```

Here's how that looks like in Ray.

[TODO: add screenshot]

There are many other helper functions available on Ray that allow you to display things that can help you debug such as [runtime and memory usage](TODO: add link), [queries that were executed](TODO: add link), and much more. 

## Getting started

To get started you should buy a license for the Ray app [in our store](TODO: add link), and [install the free package into your app](TODO: add link).
