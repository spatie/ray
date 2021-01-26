---
title: Overview
weight: 1
---

We asume you have completed the [installation](/docs/ray/v1/installation-in-your-project/introduction) of the Ray package or library in your project.

To display something in Ray use the `ray()` function. It accepts everything: strings, arrays, objects, ... you name it.

## Reference

- [Framework agnostic PHP](#framework-agnostic-php)
- [Laravel](#laravel)
- [WordPress](#wordpress)
- [Yii](#yii)
- [Craft](#craft)
- [Javascript](#javascript)

## Framework agnostic PHP

| Call | Description |
| --- | --- |
| `ray($variabele)` | Display a string, array or object |
| `ray($variabele, $another, …)` | Ray accepts multiple arguments |
| `ray()<span class="whitespace-nowrap">-&gt;</span>backtrace()` | Check entire backtrace |
| `ray(…)<span class="whitespace-nowrap">-&gt;</span>blue()` | Output in blue |
| `ray()<span class="whitespace-nowrap">-&gt;</span>caller()` | Discover where code is being called |
| `ray()<span class="whitespace-nowrap">-&gt;</span>carbon($carbon)` | Send `Carbon` instances to Ray |
| `ray()<span class="whitespace-nowrap">-&gt;</span>className($object)` | Send the classname of an object to Ray |
| `ray()<span class="whitespace-nowrap">-&gt;</span>clearScreen()` | Clear current screen |
| `ray()<span class="whitespace-nowrap">-&gt;</span>clearAll()` | Clear current and all previous screens |
| `ray()<span class="whitespace-nowrap">-&gt;</span>count()` | Count how many times a piece of code is called |
| `ray(…)<span class="whitespace-nowrap">-&gt;</span>die()` or `rd(…)` | Stop the PHP process |
| `ray()<span class="whitespace-nowrap">-&gt;</span>file($path)` | Display contents of a file |
| `ray(…)<span class="whitespace-nowrap">-&gt;</span>gray()` | Output in gray |
| `ray(…)<span class="whitespace-nowrap">-&gt;</span>green()` | Output in green |
| `ray(…)<span class="whitespace-nowrap">-&gt;</span>hide()` | Display something in Ray and make it collapse immediately |
| `ray()<span class="whitespace-nowrap">-&gt;</span>hideApp()` | Hide the app |
| `ray()<span class="whitespace-nowrap">-&gt;</span>html($html)` | Render a piece of HTML  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>image($path)` | Display an image form a path or URL  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>json($jsonString, $another, …)` | Send one or more valid JSON strings to Ray | 
| `ray(…)<span class="whitespace-nowrap">-&gt;</span>large()` | Output text bigger |
| `ray()<span class="whitespace-nowrap">-&gt;</span>measure()` | Display runtime and memory usage. When measure is called again, the time between this and previous call is also displayed |
| `ray()<span class="whitespace-nowrap">-&gt;</span>newScreen()` | Start a new screen |
| `ray()<span class="whitespace-nowrap">-&gt;</span>newScreen('title')` | Start a new named screen |
| `ray(…)<span class="whitespace-nowrap">-&gt;</span>notify($message)` | Display a notification |
| `ray(…)<span class="whitespace-nowrap">-&gt;</span>orange()` | Output in orange |
| `ray(…)<span class="whitespace-nowrap">-&gt;</span>pass($variable)` | Display something in Ray and return the value instead of a Ray instance |
| `ray()<span class="whitespace-nowrap">-&gt;</span>pause()` | Pause execution |
| `ray()<span class="whitespace-nowrap">-&gt;</span>phpinfo()` | Display PHP info |
| `ray()<span class="whitespace-nowrap">-&gt;</span>phpinfo($key, $another, …)` | Display specific parts of PHP info |
| `ray(…)<span class="whitespace-nowrap">-&gt;</span>purple()` | Output in purple |
| `ray()<span class="whitespace-nowrap">-&gt;</span>raw($value)` | Send raw output of a value to Ray without fancy formatting |
| `ray(…)<span class="whitespace-nowrap">-&gt;</span>red()` | Output in red |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showApp()` | Bring the app to the foreground |
| `ray(…)<span class="whitespace-nowrap">-&gt;</span>showIf(true)` | Conditionally show things based on a truthy value or callable  |
| `ray(…)<span class="whitespace-nowrap">-&gt;</span>small()` | Output text smaller |
| `ray()<span class="whitespace-nowrap">-&gt;</span>table($array. $label)` | Format an associative array with optional label  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>toJson($variable, $another, … )` | Display the JSON representation of 1 or more values that can be converted |
| `ray()<span class="whitespace-nowrap">-&gt;</span>trace()` | Check entire backtrace |

### Updating a Ray instance

| Call | Description |
| --- | --- |
| `$ray<span class="whitespace-nowrap">-&gt;</span>large()` | Update the size of a Ray instance. Use `large()` or `small`   |
| `$ray<span class="whitespace-nowrap">-&gt;</span>red()` | Update the color of a Ray instance. Use `green()`, `orange()`, `red()`, `blue()`,`purple()` or `gray()`   |
| `$ray<span class="whitespace-nowrap">-&gt;</span>remove()` | Remove an item from Ray   |
| `$ray<span class="whitespace-nowrap">-&gt;</span>removeWhen(true)` | Conditionally remove an item based on a truthy value or callable   |
| `$ray<span class="whitespace-nowrap">-&gt;</span>send()` | Update the content of a Ray instance  |

Read more on [Framework agnostic PHP](/docs/ray/v1/usage/framework-agnostic-php-project)

## Laravel

| Call | Description |
| --- | --- |
| `ray()<span class="whitespace-nowrap">-&gt;</span>disable()` | Disable sending stuff to Ray |
| `ray()<span class="whitespace-nowrap">-&gt;</span>disabled()` | Check if Ray is disabled |
| `ray()<span class="whitespace-nowrap">-&gt;</span>enable()` | Enable sending stuff to Ray |
| `ray()<span class="whitespace-nowrap">-&gt;</span>enabled()` | Check if Ray is enabled |
| `ray()<span class="whitespace-nowrap">-&gt;</span>mailable($mailable)` | Render a mailable  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>markdown($markdown)` | Render markdown  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>model($model)` | Display the attributes and relations of a model  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showEvents()` | Display all events that are executed  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showEvents(callable)` | Display all events that are executed within a callable |
| `ray()<span class="whitespace-nowrap">-&gt;</span>stopShowingEvents()` | Stop displaying events  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showJobs()` | Display all jobs that are executed  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showJobs(callable)` | Display all jobs that are executed within a callable |
| `ray()<span class="whitespace-nowrap">-&gt;</span>stopShowingJobs()` | Stop displaying jobs  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showQueries()` | Display all queries that are executed  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showQueries(callable)` | Display all queries that are executed within a callable |
| `ray()<span class="whitespace-nowrap">-&gt;</span>stopShowingQueries()` | Stop displaying queries  |

### Macros &amp; Blade

| Call | Description |
| --- | --- |
| `collect(['a', 'b', 'c'])<span class="whitespace-nowrap">-&gt;</span>ray('title')` | Use the Ray collection macro to easily send collections to Ray  |
| `@ray($variable, $another, …)` | Send to Ray from a Blade view  |
| `$this<span class="whitespace-nowrap">-&gt;</span>get('api/my-endpoint')<span class="whitespace-nowrap">-&gt;</span>ray()<span class="whitespace-nowrap">-&gt;</span>assertSuccessful()` | Send a `TestResponse` to Ray. Chain on any of Laravel's assertion methods |

Read more on [Laravel](/docs/ray/v1/usage/laravel)

## Wordpress

| Call | Description |
| --- | --- |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showQueries()` | Display all queries that are executed  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>stopShowingQueries()` | Stop displaying queries  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showMails()` | Display all mails that are sent  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>stopShowingMails()` | Stop displaying mails  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>disable()` | Disable sending stuff to Ray |
| `ray()<span class="whitespace-nowrap">-&gt;</span>enable()` | Enable sending stuff to Ray |

Read more on [WordPress](/docs/ray/v1/usage/wordpress)

## Yii

| Call | Description |
| --- | --- |
| `ray()<span class="whitespace-nowrap">-&gt;</span>disable()` | Disable sending stuff to Ray |
| `ray()<span class="whitespace-nowrap">-&gt;</span>disabled()` | Check if Ray is disabled |
| `ray()<span class="whitespace-nowrap">-&gt;</span>enable()` | Enable sending stuff to Ray |
| `ray()<span class="whitespace-nowrap">-&gt;</span>enabled()` | Check if Ray is enabled |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showEvents()` | Display all events that are executed  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showEvents(callable)` | Display all events that are executed within a callable |
| `ray()<span class="whitespace-nowrap">-&gt;</span>stopShowingEvents()` | Stop displaying events  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showQueries()` | Display all queries that are executed  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showQueries(callable)` | Display all queries that are executed within a callable |
| `ray()<span class="whitespace-nowrap">-&gt;</span>stopShowingQueries()` | Stop displaying queries  |

Read more on [Yii](/docs/ray/v1/usage/yii)

## Craft

| Call | Description |
| --- | --- |
| `ray()<span class="whitespace-nowrap">-&gt;</span>disable()` | Disable sending stuff to Ray |
| `ray()<span class="whitespace-nowrap">-&gt;</span>disabled()` | Check if Ray is disabled |
| `ray()<span class="whitespace-nowrap">-&gt;</span>enable()` | Enable sending stuff to Ray |
| `ray()<span class="whitespace-nowrap">-&gt;</span>enabled()` | Check if Ray is enabled |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showEvents()` | Display all events that are executed  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showEvents(callable)` | Display all events that are executed within a callable |
| `ray()<span class="whitespace-nowrap">-&gt;</span>stopShowingEvents()` | Stop displaying events  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showQueries()` | Display all queries that are executed  |
| `ray()<span class="whitespace-nowrap">-&gt;</span>showQueries(callable)` | Display all queries that are executed within a callable |
| `ray()<span class="whitespace-nowrap">-&gt;</span>stopShowingQueries()` | Stop displaying queries  |

### Twig

| Call | Description |
| --- | --- |
| `{{ ray(variable, another) }}` | Use Ray in Twig  |
| <code>{{ variable &#124; ray }}</code> | Use Ray as filter  |
| `{{ ray.clearScreen }}` | Ray methods are also available on the global variable  |

Read more on [Craft](/docs/ray/v1/usage/craft)

## JavaScript

| Call | Description |
| --- | --- |
| `ray(variabele)` | Display a string, array or object |
| `ray(variabele, another, …)` | Ray accepts multiple arguments |
| `ray(…).color('blue')` | Output in color. Use `green`, `orange`, `red`, `blue`,`purple` or `gray` |
| `ray().clearScreen()` | Clear current screen |
| `ray().clearAll()` | Clear current and all previous screens |
| `ray(…).hide()` | Display something in Ray and make it collapse immediately |
| `ray(JSON.parse([…]))` | Send JSON to Ray | 
| `ray().newScreen()` | Start a new screen |
| `ray().newScreen('title')` | Start a new named screen |
| `ray(…).notify(message)` | Display a notification |
| `ray(…).pass(variable)` | Display something in Ray and return the value instead of a Ray instance |
| `ray(…).showIf(true)` | Conditionally show things based on a truthy value or callable  |
| `ray(…).size('small')` | Output text smaller or bigger. Use `large` or `small`|

### Updating a Ray instance

| Call | Description |
| --- | --- |
| `ray.size('large')` | Update the size of a Ray instance. Use `large` or `small`   |
| `ray.color('red')` | Update the color of a Ray instance. Use `green`, `orange`, `red`, `blue`,`purple` or `gray`   |
| `$ray.remove()` | Remove an item from Ray   |
| `$ray.removeWhen(true)` | Conditionally remove an item based on a truthy value or callable   |
| `$ray.send()` | Update the content of a Ray instance  |

Read more on [JavaScript](/docs/ray/v1/usage/javascript)
