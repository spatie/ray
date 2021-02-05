---
title: Yii2
weight: 5
---

Inside a Yii2 application, you can use all methods from [the framework agnostic version](/docs/ray/v1/usage/framework-agnostic-php-project).

Additionally, you can use these Laravel specific methods.

### Showing queries

You can display all queries that are executed by calling `showQueries` (or `queries`).

```php
ray()->showQueries();

Yii::$app->db->createCommand('SELECT * FROM users')->queryAll(); // this query will be displayed in Ray.
```

To stop showing queries, call `stopShowingQueries`.

```php
ray()->showQueries();

Yii::$app->db->createCommand('SELECT * FROM users')->queryAll(); // this query will be displayed.

ray()->stopShowingQueries();

Yii::$app->db->createCommand('SELECT * FROM users')->queryAll(); // this query won't be displayed.
```

Alternatively, you can pass a callable to `showQueries`. Only the queries performed inside that callable will be displayed in Ray.

```php
Yii::$app->db->createCommand('SELECT * FROM users')->queryAll(); // this query won't be displayed.

ray()->showQueries(function() {
    Yii::$app->db->createCommand('SELECT * FROM users')->queryAll(); // this query will be displayed.
});

Yii::$app->db->createCommand('SELECT * FROM users')->queryAll(); // this query won't be displayed.
```


### Showing events

You can display all events that are executed by calling `showEvents` (or `events`).

```php
ray()->showEvents();

Yii::$app->trigger('test-event', new TestEvent());

```

![screenshot](/docs/ray/v1/images/event.jpg)

To stop showing events, call `stopShowingEvents`.

```php
ray()->showEvents();

Yii::$app->trigger('myEvent', new MyEvent()); // this event will be displayed

ray()->stopShowingEvents();

Yii::$app->trigger('myEvent', new MyOtherEvent()); // this event won't be displayed.
```

Alternatively, you can pass a callable to `showEvents`. Only the events fired inside that callable will be displayed in Ray.

```php
Yii::$app->trigger('myEvent', new MyEvent()); // this event won't be displayed.

ray()->showEvents(function() {
    Yii::$app->trigger('myEvent', new MyEvent()); // this event will be displayed.
});

Yii::$app->trigger('myEvent', new MyEvent()); // this event won't be displayed.
```

