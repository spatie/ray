---
title: In Laravel
weight: 3
---

Inside a Laravel application, you can use all methods from [the framework agnostic version](/docs/ray/v1/usage/in-a-framework-agnostic-project).

Additionally, you can use these Laravel specific methods.

### Showing queries

You can display all queries that are executed by calling `showQueries` (or `queries`).

```php
ray()->showQueries();

User::firstWhere('email', 'john@example.com');; // this query will be displayed in Ray.
```

![screenshot](/docs/ray/v1/images/query.jpg)

To stop showing queries, call `stopShowingQueries`.

```php
ray()->showQueries();

User::firstWhere('email', 'john@example.com'); // this query will be displayed.

ray()->stopShowingQueries();

User::firstWhere('email', 'jane@example.com'); // this query won't be displayed.
```

Alternatively, you can pass a callable to `showQueries`. Only the queries performed inside that callable will be displayed in Ray.

```php
User::all(); // this query won't be displayed.

ray()->showQueries(function() {
    User::all(); // this query will be displayed.
});

User::all(); // this query won't be displayed.
```

### Showing events

You can display all events that are executed by calling `showEvents` (or `events`).

```php
ray()->showEvents();

event(new TestEvent());

event(new TestEventWithParameter('my argument'));
```

![screenshot](/docs/ray/v1/images/event.jpg)

To stop showing events, call `stopShowingEvents`.

```php
ray()->showEvents();

event(new MyEvent()); // this event will be displayed

ray()->stopShowingEvents();

event(new MyOtherEvent()); // this event won't be displayed.
```

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

![screenshot](/docs/ray/v1/images/model.jpg)

### Displaying mailables

You can see the rendered version of mailable in Ray by passing a mailable to the `mailable` function.

```php
ray()->mailable(new TestMailable());
```

![screenshot](/docs/ray/v1/images/mailable.jpg)

### Displaying collections

In a Laravel app, Ray will automatically register a `ray` collection macro to easily send collections to ray.

```php
collect(['a', 'b', 'c'])
    ->ray('original collection') // displays the original collection
    ->map(fn(string $letter) => strtoupper($letter))
    ->ray('uppercased collection'); // displays the modified collection
```

![screenshot](/docs/ray/v1/images/collection.jpg)

### Using Ray in Blade views

You can use the `@ray` directive to easily send variables to Ray from inside a Blade view. You can pass as many things as you'd like.

```blade
{{-- inside a view --}}

@ray($variable, $anotherVariables)
```

### Enabling / disabling Ray

You can enable and disable sending stuff to Ray with the `enable` and `disable` functions.

```php
ray('one') // will be displayed in ray

ray()->disable();

ray('two') // won't be displayed in ray

ray()->enable();

ray('three') // will be displayed in ray
```
