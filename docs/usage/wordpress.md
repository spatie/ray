---
title: WordPress
weight: 4
---

In WordPress, you can use all methods from [the framework agnostic version](/docs/ray/v1/usage/in-a-framework-agnostic-project).

Additionally, you can use these following WordPress specific methods.

## Showing queries

You can display all queries that are executed by calling `showQueries` (or `queries`).

```php
ray()->showQueries();

// somewhere else in your WordPress app
global $wpdb;
$result = $wpdb->get_results( "SELECT * FROM wp_usermeta WHERE meta_key = 'points' AND user_id = '1'");
```

![screenshot](/docs/ray/v1/images/wordpress-queries.png)

To stop showing queries, call `stopShowingQueries()`

### Displaying mails

To show all mails sent in Ray call `showMails()`.

```php
ray()->showMails();

// somewhere else in your WordPress app
wp_mail('to@email.com', 'my subject', 'the content');
```

![screenshot](/docs/ray/v1/images/wordpress-mails.png)


To stop showing mail, call `stopShowingMails()`
