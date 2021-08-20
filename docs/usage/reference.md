---
title: Reference
weight: 1
---

<style>
    code {
        white-space:nowrap
    }
</style>

We assume you have completed the [installation](/docs/ray/v1/installation-in-your-project/introduction) of the Ray package or library in your project.

To display something in Ray use the `ray()` function. It accepts everything: strings, arrays, objects, ... you name it.

- [Framework agnostic PHP](#framework-agnostic-php)
- [Laravel](#laravel)
- [WordPress](#wordpress)
- [Yii](#yii)
- [Craft](#craft)
- [Javascript](#javascript)
- [NodeJS](#nodejs)
- [Vue](#vue)
- [Go](#go)
- [Alpine.js](#alpinejs)
- [Bash](#bash)

## Framework agnostic PHP

| Call | Description |
| --- | --- |
| `ray($variable)` | Display a string, array or object |
| `ray($variable, $another, …)` | Ray accepts multiple arguments |
| `ray()->backtrace()` | Check entire backtrace |
| `ray(…)->blue()` | Output in blue |
| `ray()->caller()` | Discover where code is being called |
| `ray()->carbon($carbon)` | Send `Carbon` instances to Ray |
| `ray($callable)->catch([$callable, $classname, …])` | Handle any exceptions encountered by `try` |
| `ray()->className($object)` | Send the classname of an object to Ray |
| `ray()->clearScreen()` | Clear current screen |
| `ray()->clearAll()` | Clear current and all previous screens |
| `ray()->count()` | Count how many times a piece of code is called |
| `ray()->counterValue(name)` | Return the value of a named counter |
| `ray(…)->die()` or `rd(…)` | Stop the PHP process |
| `ray()->disable()` | Disable sending stuff to Ray |
| `ray()->disabled()` | Check if Ray is disabled |
| `ray()->enable()` | Enable sending stuff to Ray |
| `ray()->enabled()` | Check if Ray is enabled |
| `ray()->exception($e)` | Display information about an Exception |
| `ray()->file($path)` | Display contents of a file |
| `ray(…)->gray()` | Output in gray |
| `ray(…)->green()` | Output in green |
| `ray(…)->hide()` | Display something in Ray and make it collapse immediately |
| `ray()->hideApp()` | Hide the app |
| `ray()->html($html)` | Render a piece of HTML  |
| `ray()->image($path)` | Display an image from a path or URL  |
| `ray()->if(true, callback)` | Conditionally show things based on a truthy value or callable |
| `ray()->json($json, $another, …)` | Send one or more valid JSON strings to Ray |
| `ray(…)->label($name)` | Set the label name |
| `ray(…)->large()` | Output text bigger |
| `ray()->limit(N)->…` | Limit the number of payloads that can be sent to Ray to N; used for debugging within loops |
| `ray()->measure()` | Display runtime and memory usage. When measure is called again, the time between this and previous call is also displayed |
| `ray()->newScreen()` | Start a new screen |
| `ray()->newScreen('title')` | Start a new named screen |
| `ray(…)->notify($message)` | Display a notification |
| `ray()->once($arg1, …)` | Only send a payload once when in a loop |
| `ray(…)->orange()` | Output in orange |
| `ray(…)->pass($variable)` | Display something in Ray and return the value instead of a Ray instance |
| `ray()->pause()` | Pause execution |
| `ray()->phpinfo()` | Display PHP info |
| `ray()->phpinfo($key, $another, …)` | Display specific parts of PHP info |
| `ray(…)->purple()` | Output in purple |
| `ray()->rateLimiter()->max(int $maxCalls)` | Limits the amount of calls sent to Ray |
| `ray()->rateLimiter()->perSecond($maxCalls)` | Limits the amount of calls sent to Ray in a second |
| `ray()->rateLimiter()->clear()` | Clears the rate limits |
| `ray()->raw($value)` | Send raw output of a value to Ray without fancy formatting |
| `ray(…)->red()` | Output in red |
| `ray()->showApp()` | Bring the app to the foreground |
| `ray(…)->small()` | Output text smaller |
| `ray()->table($array, $label)` | Format an associative array with optional label  |
| `ray()->text($string)` | Display the raw text for a string while preserving whitespace formatting  |
| `ray()->toJson($variable, $another, … )` | Display the JSON representation of 1 or more values that can be converted |
| `ray()->trace()` | Check entire backtrace |
| `ray()->xml($xmlString)` | Display formatted XML in Ray |

### Updating a Ray instance

| Call | Description |
| --- | --- |
| `$ray->large()` | Update the size of a Ray instance. Use `large()` or `small`   |
| `$ray->red()` | Update the color of a Ray instance. Use `green()`, `orange()`, `red()`, `blue()`,`purple()` or `gray()`   |
| `$ray->remove()` | Remove an item from Ray   |
| `$ray->removeIf(true)` | Conditionally remove an item based on a truthy value or callable   |
| `$ray->removeWhen(true)` | Conditionally remove an item based on a truthy value or callable   |
| `$ray->send()` | Update the content of a Ray instance  |

Read more on [Framework agnostic PHP](/docs/ray/v1/usage/framework-agnostic-php-project)

## Laravel

| Call | Description |
| --- | --- |
| `ray()->env([name1, name2, ...])` | Display environment variables, optionally the specified names only  |
| `ray()->mailable($mailable)` | Render a mailable  |
| `ray()->markdown($markdown)` | Render markdown  |
| `ray()->model($model)` | Display the attributes and relations of a model  |
| `ray()->showCache()` | Display all cache events that are executed  |
| `ray()->showCache(callable)` | Display all cache events that are executed within a callable |
| `ray()->stopShowingCache()` | Stop displaying cache events  |
| `ray()->showEvents()` | Display all events that are executed  |
| `ray()->showEvents(callable)` | Display all events that are executed within a callable |
| `ray()->stopShowingEvents()` | Stop displaying events  |
| `ray()->showJobs()` | Display all jobs that are executed  |
| `ray()->showJobs(callable)` | Display all jobs that are executed within a callable |
| `ray()->stopShowingJobs()` | Stop displaying jobs  |
| `ray()->showQueries()` | Display all queries that are executed  |
| `ray()->showQueries(callable)` | Display all queries that are executed within a callable |
| `ray()->countQueries(callable)` | Count all queries that are executed within a callable |
| `ray()->stopShowingQueries()` | Stop displaying queries  |
| `ray()->showRequests()` | Display all requests  |
| `ray()->stopShowingRequests()` | Stop displaying requests  |
| `ray()->showViews()` | Display all views  |
| `ray()->stopShowingViews()` | Stop displaying views  |

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

Read more on [WordPress](/docs/ray/v1/usage/wordpress)

## Yii

| Call | Description |
| --- | --- |
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
| `ray(variable)` | Display a string, array or object |
| `ray(variable, another, …)` | Ray accepts multiple arguments |
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

## NodeJS

| Call | Description |
| --- | --- |
| `ray(variable)` | Display a string, array or object |
| `ray(variable, another, …)` | Ray accepts multiple arguments |
| `ray(…).blue()` | Output in color. Use `green`, `orange`, `red`, `blue`,`purple` or `gray` |
| `ray()->caller()` | Discover where code is being called |
| `ray().clearScreen()` | Clear current screen |
| `ray().clearAll()` | Clear current and all previous screens |
| `ray().count(name)` | Count how many times a piece of code is called, with optional name |
| `ray().date(date, format)` | Display a formatted date, the timezone, and its timestamp |
| `ray().die()` | Halt code execution - NodeJS only |
| `ray().disable()` | Disable sending stuff to Ray |
| `ray().disabled()` | Check if Ray is disabled |
| `ray().enable()` | Enable sending stuff to Ray |
| `ray().enabled()` | Check if Ray is enabled |
| `ray().error(err)` | Display information about an error or exception |
| `ray().event(name, data)` | Display information about an event with optional data |
| `ray().exception(err)` | Display extended information about an Error or Exception |
| `ray().file(filename)` | Display contents of a file - NodeJS only |
| `ray(…).hide()` | Display something in Ray and make it collapse immediately |
| `ray().hideApp()` | Programmatically hide the Ray app window |
| `ray().html(string)` | Send HTML to Ray |
| `ray().if(true, callback)` | Conditionally show things based on a truthy value or callable |
| `ray().image(url)` | Display an image in Ray |
| `ray().json([…])` | Send JSON to Ray |
| `ray().limit(N).…` | Limit the number of payloads that can be sent to Ray to N; used for debugging within loops |
| `ray().measure(callable)` | Measure the performance of a callback function |
| `ray().measure()` | Begin measuring the overall time and elapsed time since previous `measure()` call |
| `ray().newScreen()` | Start a new screen |
| `ray().newScreen('title')` | Start a new named screen |
| `ray(…).notify(message)` | Display a notification |
| `ray().once(arg1, …)` | Only send a payload once when in a loop |
| `ray(…).pass(variable)` | Display something in Ray and return the value instead of a Ray instance |
| `ray().pause()` | Pause code execution within your code; must be called using `await` |
| `ray().rateLimiter().max(int maxCalls)` | Limits the amount of calls sent to Ray |
| `ray().rateLimiter().perSecond(maxCalls)` | Limits the amount of calls sent to Ray in a second |
| `ray().rateLimiter().clear()` | Clears the rate limits |
| `ray.remove()` | Remove an item from Ray   |
| `ray.removeIf(true)` | Conditionally remove an item based on a truthy value or callable   |
| `ray.removeWhen(true)` | Conditionally remove an item based on a truthy value or callable   |
| `ray().showApp()` | Programmatically show the Ray app window |
| `ray(…).showIf(true)` | Conditionally show things based on a truthy value or callable  |
| `ray(…).small()` | Output text smaller or bigger. Use `large` or `small`|
| `ray().stopTime(name)` | Removes a named stopwatch if specified, otherwise removes all stopwatches |
| `ray().table(…)` | Display an array of items or an object formatted as a table; Objects and arrays are pretty-printed |
| `ray()->trace()` | Check entire backtrace |
| `ray().xml(string)` | Send XML to Ray |

## Vue

| Call | Description |
| --- | --- |
| `this.$ray().<method>` | All NodeJS methods [listed above](#nodejs) are also available |
| `this.$ray().data()` | Send the component data object to Ray |
| `this.$ray().props()` | Send the component props to Ray |
| `this.$ray().ref(name)` | Display the `innerHTML` of a named ref in Ray |
| `this.$ray().track(name)` | Display changes to a component's data variable in real time |
| `this.$ray().untrack(name)` | Stop displaying changes to a component's data variable |

## Go

| Call | Description |
| --- | --- |
| `ray.Ray(variable)` | Display a string, array or object |
| `ray.Ray(variable, another, …)` | Ray accepts multiple arguments |
| `ray.Ray(…).Blue()` | Output in color. Use `Green`, `Orange`, `Red`, `Blue`,`Purple` or `Gray` |
| `ray.Ray().ClearScreen()` | Clear current screen |
| `ray.Ray().ClearAll()` | Clear current and all previous screens |
| `ray.Ray().Time(date)` | Display a formatted date, the timezone, and its time stamp |
| `ray.Ray().TimeWithFormat(date, format)` | Display a formatted date, the timezone, and its time stamp |
| `ray.Ray().Die()` | Halt code execution |
| `ray.Ray().Disable()` | Disable sending stuff to Ray |
| `ray.Ray().Disabled()` | Check if Ray is disabled |
| `ray.Ray().Enable()` | Enable sending stuff to Ray |
| `ray.Ray().Enabled()` | Check if Ray is enabled |
| `ray.Ray(…).Hide()` | Display something in Ray and make it collapse immediately |
| `ray.Ray().Pause()` | Pause execution |
| `ray.Ray().HideApp()` | Hide the app |
| `ray.Ray().Html(string)` | Send HTML to Ray |
| `ray.Ray().Image(url)` | Display an image in Ray |
| `ray.Ray().NewScreen("")` | Start a new screen |
| `ray.Ray().NewScreen("title")` | Start a new named screen |
| `ray.Ray(…).Notify(message)` | Display a notification |
| `ray.Ray().ShowApp()` | Bring the app to the foreground |
| `ray.Ray(…).ShowIf(true)` | Conditionally show things based on a truthy value or callable |
| `ray.Ray(…).ShowWhen(true)` | Conditionally show things based on a truthy value or callable  |
| `ray.Ray(…).Small()` | Output text smaller or bigger. Use `Large` or `Small` |
| `ray.Ray(…).RemoveWhen(true)` | Conditionally remove an item based on a truthy value or callable |

## AlpineJS

All methods available to [NodeJS](#nodejs) are available to the Alpine.js integration.

### Updating a Ray instance

| Call | Description |
| --- | --- |
| `ray.size('large')` | Update the size of a Ray instance. Use `large` or `small`   |
| `ray.color('red')` | Update the color of a Ray instance. Use `green`, `orange`, `red`, `blue`,`purple` or `gray`   |
| `$ray.remove()` | Remove an item from Ray   |
| `$ray.removeWhen(true)` | Conditionally remove an item based on a truthy value or callable   |
| `$ray.send()` | Update the content of a Ray instance  |

Read more on [JavaScript](/docs/ray/v1/usage/javascript)

## Bash

| Command | Description |
| --- | --- |
| `clear` | Clear the current screen |
| `clear-all` | Clear the current and all previous screens |
| `color <uuid> <color>` | Change the color of a payload that has already been sent |
| `file <filename>` | Show the contents of `filename` |
| `hide-app` | Hide the Ray app |
| `html <content>` | Display rendered html |
| `image <location>` | Display an image from a URL or file |
| `json <content>` | Display formatted JSON |
| `notify <message>` | Display a desktop notification |
| `pause` | Pause code execution |
| `remove <uuid>` | Remove a payload |
| `send <payload>` | Send a payload to Ray |
| `show-app` | Show the Ray app |
| `size <uuid> <size>` | Change the text size of a payload that has already been sent _(sizes are 'large' or 'small')_ |
| `text <data>` | Display a text string with whitespace preserved |
| `xml <data>` | Display formatted XML |

Read more on [Bash](/docs/ray/v1/usage/bash)

