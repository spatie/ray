---
title: Reference
weight: 1
---

<style>
    code {
        white-space:nowrap
    }
</style>

We asume you have completed the [installation](/docs/ray/v1/installation-in-your-project/introduction) of the Ray package or library in your project.

To display something in Ray use the `ray()` function. It accepts everything: strings, arrays, objects, ... you name it.

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
| `ray()->backtrace()` | Check entire backtrace |
| `ray(…)->blue()` | Output in blue |
| `ray()->caller()` | Discover where code is being called |
| `ray()->carbon($carbon)` | Send `Carbon` instances to Ray |
| `ray()->className($object)` | Send the classname of an object to Ray |
| `ray()->clearScreen()` | Clear current screen |
| `ray()->clearAll()` | Clear current and all previous screens |
| `ray()->count()` | Count how many times a piece of code is called |
| `ray(…)->die()` or `rd(…)` | Stop the PHP process |
| `ray()->file($path)` | Display contents of a file |
| `ray(…)->gray()` | Output in gray |
| `ray(…)->green()` | Output in green |
| `ray(…)->hide()` | Display something in Ray and make it collapse immediately |
| `ray()->hideApp()` | Hide the app |
| `ray()->html($html)` | Render a piece of HTML  |
| `ray()->image($path)` | Display an image form a path or URL  |
| `ray()->json($json, $another, …)` | Send one or more valid JSON strings to Ray | 
| `ray(…)->large()` | Output text bigger |
| `ray()->measure()` | Display runtime and memory usage. When measure is called again, the time between this and previous call is also displayed |
| `ray()->newScreen()` | Start a new screen |
| `ray()->newScreen('title')` | Start a new named screen |
| `ray(…)->notify($message)` | Display a notification |
| `ray(…)->orange()` | Output in orange |
| `ray(…)->pass($variable)` | Display something in Ray and return the value instead of a Ray instance |
| `ray()->pause()` | Pause execution |
| `ray()->phpinfo()` | Display PHP info |
| `ray()->phpinfo($key, $another, …)` | Display specific parts of PHP info |
| `ray(…)->purple()` | Output in purple |
| `ray()->raw($value)` | Send raw output of a value to Ray without fancy formatting |
| `ray(…)->red()` | Output in red |
| `ray()->showApp()` | Bring the app to the foreground |
| `ray(…)->showIf(true)` | Conditionally show things based on a truthy value or callable  |
| `ray(…)->small()` | Output text smaller |
| `ray()->table($array. $label)` | Format an associative array with optional label  |
| `ray()->toJson($variable, $another, … )` | Display the JSON representation of 1 or more values that can be converted |
| `ray()->trace()` | Check entire backtrace |

### Updating a Ray instance

| Call | Description |
| --- | --- |
| `$ray->large()` | Update the size of a Ray instance. Use `large()` or `small`   |
| `$ray->red()` | Update the color of a Ray instance. Use `green()`, `orange()`, `red()`, `blue()`,`purple()` or `gray()`   |
| `$ray->remove()` | Remove an item from Ray   |
| `$ray->removeWhen(true)` | Conditionally remove an item based on a truthy value or callable   |
| `$ray->send()` | Update the content of a Ray instance  |

Read more on [Framework agnostic PHP](/docs/ray/v1/usage/framework-agnostic-php-project)

## Laravel

| Call | Description |
| --- | --- |
| `ray()->disable()` | Disable sending stuff to Ray |
| `ray()->disabled()` | Check if Ray is disabled |
| `ray()->enable()` | Enable sending stuff to Ray |
| `ray()->enabled()` | Check if Ray is enabled |
| `ray()->mailable($mailable)` | Render a mailable  |
| `ray()->markdown($markdown)` | Render markdown  |
| `ray()->model($model)` | Display the attributes and relations of a model  |
| `ray()->showEvents()` | Display all events that are executed  |
| `ray()->showEvents(callable)` | Display all events that are executed within a callable |
| `ray()->stopShowingEvents()` | Stop displaying events  |
| `ray()->showJobs()` | Display all jobs that are executed  |
| `ray()->showJobs(callable)` | Display all jobs that are executed within a callable |
| `ray()->stopShowingJobs()` | Stop displaying jobs  |
| `ray()->showQueries()` | Display all queries that are executed  |
| `ray()->showQueries(callable)` | Display all queries that are executed within a callable |
| `ray()->stopShowingQueries()` | Stop displaying queries  |

### Macros &amp; Blade

| Call | Description |
| --- | --- |
| `collect([…])->ray('title')` | Use the Ray collection macro to easily send collections to Ray  |
| `@ray($variable, $another, …)` | Send to Ray from a Blade view  |
| `$this->get(…)->ray()->assertSuccessful()` | Send a `TestResponse` to Ray. Chain on any of Laravel's assertion methods |

Read more on [Laravel](/docs/ray/v1/usage/laravel)

## Wordpress

| Call | Description |
| --- | --- |
| `ray()->showQueries()` | Display all queries that are executed  |
| `ray()->stopShowingQueries()` | Stop displaying queries  |
| `ray()->showMails()` | Display all mails that are sent  |
| `ray()->stopShowingMails()` | Stop displaying mails  |
| `ray()->disable()` | Disable sending stuff to Ray |
| `ray()->enable()` | Enable sending stuff to Ray |

Read more on [WordPress](/docs/ray/v1/usage/wordpress)

## Yii

| Call | Description |
| --- | --- |
| `ray()->disable()` | Disable sending stuff to Ray |
| `ray()->disabled()` | Check if Ray is disabled |
| `ray()->enable()` | Enable sending stuff to Ray |
| `ray()->enabled()` | Check if Ray is enabled |
| `ray()->showEvents()` | Display all events that are executed  |
| `ray()->showEvents(callable)` | Display all events that are executed within a callable |
| `ray()->stopShowingEvents()` | Stop displaying events  |
| `ray()->showQueries()` | Display all queries that are executed  |
| `ray()->showQueries(callable)` | Display all queries that are executed within a callable |
| `ray()->stopShowingQueries()` | Stop displaying queries  |

Read more on [Yii](/docs/ray/v1/usage/yii)

## Craft

| Call | Description |
| --- | --- |
| `ray()->disable()` | Disable sending stuff to Ray |
| `ray()->disabled()` | Check if Ray is disabled |
| `ray()->enable()` | Enable sending stuff to Ray |
| `ray()->enabled()` | Check if Ray is enabled |
| `ray()->showEvents()` | Display all events that are executed  |
| `ray()->showEvents(callable)` | Display all events that are executed within a callable |
| `ray()->stopShowingEvents()` | Stop displaying events  |
| `ray()->showQueries()` | Display all queries that are executed  |
| `ray()->showQueries(callable)` | Display all queries that are executed within a callable |
| `ray()->stopShowingQueries()` | Stop displaying queries  |

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
