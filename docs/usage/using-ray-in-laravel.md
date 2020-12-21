---
title: Using Ray in Laravel
weight: 3
---

Inside a Laravel application, you can use all methods from [the framework agnostic version](TODO: add link).

Additionally, you can use these Laravel specific methods.

### Logging queries

You can display all queries that are executed by calling `logQueries`.

```php
ray()->logQueries();

User::all(); // this query will be displayed in Ray.
```

![screenshot](TODO: add screenshot)

To stop logging queries, call `stopLoggingQueries`

```php
ray()->logQueries();

User::all(); // this query will be displayed.

ray()->stopLoggingQueries()

User::all(); // this query won't be displayed.
```

Alternatively, you can pass a callable to `logQueries`. Only the queries performed inside that callable will be displayed in Ray.

```php

User::all(); // this query won't be displayed.

ray()->logQueries(function() {
    User::all(); // this query will be displayed.
});

User::all(); // this query won't be displayed.
```

### Handling models

Using the model function, you can display the attributes of relations of a model.

```php
ray()->model($user)
```

![screenshot](TODO: add screenshot)

### Displaying mailables

You can see the rendered version of mailable in Ray by passing a mailable to the `mailable` function.

```php
ray()->mailable(new TestMailable());
```

![screenshot](TODO: add screenshot)

### Enabling / disabling Ray

You can enable and disable sending stuff to Ray with the `enable` and `disable` functions.

```php
ray('one') // will be displayed in ray

ray()->disable();

ray('two') // won't be displayed in ray

ray()->enable();

ray('three') // will be displayed in ray
```


