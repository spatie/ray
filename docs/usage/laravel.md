---
title: Laravel
weight: 3
---

Inside a Laravel application, you can use all methods from [the framework agnostic version](/docs/ray/v1/usage/framework-agnostic-php-project).

Additionally, you can use these Laravel specific methods.

### Showing queries

You can display all queries that are executed by calling `showQueries` (or `queries`).

```php
ray()->showQueries();

User::firstWhere('email', 'john@example.com'); // this query will be displayed in Ray.
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

### Showing jobs

You can display all jobs that are executed by calling `showJobs` (or `jobs`).

```php
ray()->showJobs();

dispatch(new TestJob('my-test-job'));

```

![screenshot](/docs/ray/v1/images/job.png)

To stop showing jobs, call `stopShowingJobs`.

```php
ray()->showJobs();

dispatch(new TestJob()); // this job will be displayed

ray()->stopShowingJobs();

dispatch(new MyTestOtherJob()); // this job won't be displayed.
```

Alternatively, you can pass a callable to `showJobs`. Only the jobs dispatch inside that callable will be displayed in Ray.

```php
event(new TestJob()); // this job won't be displayed.

ray()->showJobs(function() {
    dispatch(new TestJob()); // this job will be displayed.
});

event(new TestJob()); // this job won't be displayed.
```

### Showing cache events

You can display all cache events using `showCache`

```php
ray()->showCache();

Cache::put('my-key', ['a' => 1]);

Cache::get('my-key');

Cache::get('another-key');
```

![screenshot](/docs/ray/v1/images/cache.png)

To stop showing cache events, call `stopShowingCache`.

### Handling models

Using the `model` function, you can display the attributes and relations of a model.

```php
ray()->model($user);
```

![screenshot](/docs/ray/v1/images/model.jpg)

The `model` function can also accept multiple models and even collections.

```php
// all of these models will be displayed in Ray
ray()->model($user, $anotherUser, $yetAnotherUser);

// all models in the collection will be display
ray()->model(User::all());

// all models in all collections will be displayed
ray()->model(User::all(), OtherModel::all());
```

Alternatively, you can use `models()` which is an alias for `model()`.

### Displaying mailables

You can see the rendered version of mailable in Ray by passing a mailable to the `mailable` function.

```php
ray()->mailable(new TestMailable());
```

![screenshot](/docs/ray/v1/images/mailable.jpg)

### Showing which views are rendered

You can display all views that are rendered by calling `showViews`.

```php
ray()->showViews();

// typically you'll do this in a controller
view('welcome', ['name' => 'John Doe'])->render();
```

![screenshot](/docs/ray/v1/images/views.png)

To stop showing views, call `stopShowingViews`.

### Displaying markdown

View the rendered version of a markdown string in Ray by calling the `markdown` function.

```php
ray()->markdown('# Hello World');
```

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

### Using Ray with test responses

When testing responses, you can send a `TestResponse` to Ray using the `ray()` method. 

`ray()` is chainable, so you can chain on any of Laravel's assertion methods.

```php
// somewhere in your app
Route::get('api/my-endpoint', function () {
    return response()->json(['a' => 1]);
});

// somewhere in a test
/** test */
public function my_endpoint_works_correctly() 
{
    $this
        ->get('api/my-endpoint')
        ->ray()
        ->assertSuccessful();
}
```

![screenshot](/docs/ray/v1/images/response.png)

### Displaying requests

To display all requests made in your Laravel app in Ray, you can call `ray()->showRequests()`. A typical place to put this would be in a service provider.

![screenshot](/docs/ray/v1/images/request.png)

To enable this behaviour by default, you can set the `send_requests_to_ray` option in [the config file](https://spatie.be/docs/ray/v1/configuration/laravel) to `true`.

