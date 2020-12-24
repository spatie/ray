---
title: Using Ray in Laravel
weight: 3
---

Inside a Laravel application, you can use all methods from [the framework agnostic version](TODO: add link).

Additionally, you can use these Laravel specific methods.

### Showing queries

You can display all queries that are executed by calling `showQueries`.

```php
ray()->showQueries();

User::all(); // this query will be displayed in Ray.
```

![screenshot](TODO: add screenshot)

To stop showing queries, call `stopLoggingQueries`

```php
ray()->showQueries();

User::all(); // this query will be displayed.

ray()->stopShowingQueries()

User::all(); // this query won't be displayed.
```

![Screenshot](TODO: add screenshot)

Alternatively, you can pass a callable to `showQueries`. Only the queries performed inside that callable will be displayed in Ray.

```php

User::all(); // this query won't be displayed.

ray()->showQueries(function() {
    User::all(); // this query will be displayed.
});

User::all(); // this query won't be displayed.
```

### Showing events

You can display all events that are executed by calling `showEvents`.

```php
ray()->showEvents();

event(new MyEvent())
```

![screenshot](TODO: add screenshot)

To stop showing queries, call `stopLoggingQueries`

```php
ray()->showEvents();

event(new MyEvent()) // this event will be displayed

ray()->stopShowingEvents()

event(new MyOtherEvent()) // this event won't be displayed.
```

![Screenshot](TODO: add screenshot)

Alternatively, you can pass a callable to `showEvents`. Only the events fired inside that callable will be displayed in Ray.

```php

event(new MyEvent()); // this event won't be displayed.

ray()->showEvents(function() {
    event(new MyEvent()); // this event will be displayed.
});

event(new MyEvent()); // this event won't be displayed.
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


