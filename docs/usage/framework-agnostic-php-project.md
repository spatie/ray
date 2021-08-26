---
title: Framework agnostic PHP 
weight: 2
---

To display something in Ray use the `ray()` function. It accepts everything: strings, arrays, objects, ... you name it.

```php
ray('a string');

ray(['an array']);

ray($anObject);
```

![screenshot](/docs/ray/v1/images/ray.jpg)

`ray` accepts multiple arguments. Each argument will be displayed in the Ray app.

```php
ray('as', 'many' , 'arguments', 'as', 'you', 'like');
```

### Using colors

You can colorize things you sent to ray by using one of the color functions.

```php
ray('this is green')->green();
ray('this is orange')->orange();
ray('this is red')->red();
ray('this is blue')->blue();
ray('this is purple')->purple();
ray('this is gray')->gray();
```

![screenshot](/docs/ray/v1/images/colors.jpg)

### Using sizes

Ray can display things in different sizes.

```php
ray('small')->small();
ray('regular');
ray('large')->large();
```

![screenshot](/docs/ray/v1/images/sizes.jpg)

### Adding a label

You can customize the label displayed next to item with the `label` function.

```php
ray(['John', 'Paul', 'George', 'Ringo'])->label('Beatles');
```

![screenshot](/docs/ray/v1/images/label.png)



### Creating a new screen

You can use `newScreen` (or `clearScreen`) to programmatically create a new screen.

```php
ray()->newScreen(); 
```

You can see values that were previously displayed, by clicking the little back button in the header of Ray.

Optionally, you can give a screen a name:

```php
ray()->newScreen('My debug screen');
```

![screenshot](/docs/ray/v1/images/screen.jpg)

You could opt to use `newScreen` very early on in a request so you'll only see items that were sent to Ray in the
current request. In a Laravel app, a good place for this might be the service provider.

When using PHPUnit to run tests, you might use `newScreen` to get a new screen each time your run a test to debug some
code.

### Clearing everything including history

To clear the current screen and all previous screens, call `clearAll`.

```php
ray()->clearAll(); 
```

### See the caller of a function

Sometimes you want to know where your code is being called. You can quickly determine that by using the `caller`
function.

```php
ray()->caller();
```

![screenshot](/docs/ray/v1/images/caller.jpg)

If you want to see the entire backtrace, use the `trace` (or `backtrace`).

```php
ray()->trace();
```

![screenshot](/docs/ray/v1/images/trace.jpg)

### Pausing execution

You can pause execution of a script by using the `pause` method.

```php
ray()->pause();
```

![screenshot](/docs/ray/v1/images/pause.jpg)

If you press the "Continue" button in Ray, execution will continue. When you press "Stop execution", Ray will throw an
exception in your app to halt execution.

If you are using Windows, you must set the maximum execution time to a high value, as the paused time will count against the maximum execution time.

### Counting execution times

You can display a count of how many times a piece of code was called using `count`.

Here's an example:

```php
foreach (range(1, 2) as $i) {
    ray()->count();

    foreach (range(1, 4) as $j) {
        ray()->count();
    }
}
```

This is how that looks like in Ray.

![screenshot](/docs/ray/v1/images/count.png)

Optionally, you can pass a name to `count`. Ray will display a count of how many times a `count` with that name was
executed.

Here's an example:

```php
foreach (range(1, 4) as $i) {
    ray()->count('first');

    foreach (range(1, 2) as $j) {
        ray()->count('first');

        ray()->count('second');
    }
}
```

You may access the value of a named counter using the  `counterValue` function.

```php
foreach (range(1, 4) as $i) {
    ray()->count('first');

    if (ray()->counterValue('first') === 2) {
        echo "counter value is two!";
    }
}
```

This is how that looks like in Ray.

![screenshot](/docs/ray/v1/images/named-count.png)

### Limiting the number of sent payloads

To limit the number of payloads sent by a particular `ray()` call, use the `limit` function.  It works well for debugging loops.

```php
foreach (range(1, 10) as $i) {
    ray()->limit(3)->text("A #{$i}"); // counts to 3
    ray()->limit(6)->text("B #{$i}"); // counts to 6
    ray()->text("C #{$i}"); // counts to 10
}
```

If the argument passed to `limit()` is a negative number or zero, limiting is disabled.


### Using a rate limiter

A rate limiter can help to reduce the amount of sent messages. This would avoid spamming the desktop app, which can be helpful when using Ray in loops.

```php
Ray::rateLimiter()->max(10); // only 10 messages will be sent
```

```php
Ray::rateLimiter()->perSecond(10); // only 10 messages per second will be sent
```

To remove the rate limits again
```php
Ray::rateLimiter()->clear();
```

A message to the desktop app will be sent once to notify the user the rate limit has been reached.


### Sending a payload once

To only send a payload once, use the `once` function.  This is useful for debugging loops.

`once()` may be called with arguments:


```php
foreach (range(1, 10) as $i) {
    ray()->once($i); // only sends "1"
}
```

You can also use `once` without arguments. Any function you chain on `once` will also only be called once.

```php
foreach (range(1, 10) as $i) {
    ray()->once()->html("<strong>{$i}</strong>"); // only sends "<strong>1</strong>"
}
```

### Display the class name of an object

To quickly send the class name of an object to ray, use the `className` function.

```php
ray()->className($anObject)
```

### Measuring performance and memory usage

You can use the `measure` function to display runtime and memory usage. When `measure` is called again, the time between
this and previous call is also displayed.

```php
ray()->measure();

sleep(1);

ray()->measure();

sleep(2);

ray()->measure();
```

![screenshot](/docs/ray/v1/images/measure.jpg)

The `measure` call optionally accepts a callable. Ray will output the time needed to run the callable and the maximum
memory used.

```php
ray()->measure(function() {
    sleep(5);
});
```

![screenshot](/docs/ray/v1/images/measure-closure.jpg)

### Working with JSON

Want to display the JSON representation of anything you'd like in Ray? Use `toJson`. You can provide any value that can
be converted to JSON with [json_encode](https://www.php.net/json_encode).

```php
ray()->toJson(['a' => 1, 'b' => ['c' => 3]]);
```

![screenshot](/docs/ray/v1/images/to-json.png)

The `toJson` function can also accept multiple arguments.

```php
// all of these will be displayed in Ray
$object = new \stdClass();
$object->company = 'Spatie';

ray()->toJson(
    ['a' => 1, 'b' => ['c' => 3]],
    ['d' => ['e' => 5]],
    $object
);
```

You can send a valid JSON string to Ray with the `json` function.

It will be displayed nicely and collapsable in Ray.

```php
$jsonString = json_encode(['a' => 1, 'b' => ['c' => 3]]);

ray()->json($jsonString);
```

![screenshot](/docs/ray/v1/images/json.png)

The `json` function can also accept multiple valid JSON strings.

```php
// all of these will be displayed in Ray
ray()->json($jsonString, $anotherJsonString, $yetAnotherJsonString);
```

### Working with XML

You can send a valid XML string to Ray with the `xml` function.

It will be displayed as formatted XML and collapsable in Ray.

```php
$xmlString = '<one><two><three>3</three></two></one>';

ray()->xml($xmlString);
```

![screenshot](/docs/ray/v1/images/xml.png)

### Working with Carbon instances

[Carbon](https://carbon.nesbot.com/docs/) is a popular datetime package. You can send instances of `Carbon` to Ray with `carbon`.

```php
ray()->carbon(new \Carbon\Carbon());
```

![screenshot](/docs/ray/v1/images/carbon.png)

### Working with files

You can display the contents of any file in Ray with the `file` function.

```php
ray()->file('somefile.txt');
```

### Displaying a table

You can send an associative array to Ray with the `table` function.

```php
ray()->table([
    'First' => 'First value',
    'Second' => 'Second value',
    'Third' => 'Third value',
]);
```

![screenshot](/docs/ray/v1/images/table.png)

As a second argument, you can pass a label that will be displayed next to the table.

```php
ray()->table(['John', 'Paul', 'George', 'Ringo'], 'Beatles');
```

![screenshot](/docs/ray/v1/images/table-label.png)

### Displaying images

To display an image, call the `image` function and pass a fully-qualified filename, url, or a valid base64-encoded image as its only argument.

```php
ray()->image('https://placekitten.com/200/300');
ray()->image('/home/user/kitten.jpg');

// display base64-encoded images
ray()->image('data:image/png;base64,iVBORw0KGgoAAA...truncated');
ray()->image('iVBORw0KGgoAAA...truncated');
```

### Rendering HTML

To render a piece of HTML directly in Ray, you can use the `html` method.

```php
ray()->html('<b>Bold string<b>');
```

### Displaying text content

To display raw text while preserving whitespace formatting, use the `text` method.  If the text contains HTML, it will be displayed as-is and is not rendered.

```php
ray()->text('<em>this string is html encoded</em>');
ray()->text('  whitespace formatting' . PHP_EOL . '   is preserved as well.');
```

### Updating displayed items

You can update values that are already displayed in Ray. To do this, you must hold on the instance returned by the `ray`
function and call send on it.

Here's an example where you'll see a countdown from 10 to one.

```php
$ray = ray('counting down!');

foreach(range(10, 1) as $number) {
    sleep(1);
    $ray->send($number);
}
```

The instance `$ray` may also be used to add a color or size to items already on display. Here's an example where an
items will change color and size after a second

```php
$ray = ray('a string');

sleep(1);

$ray->red()->large()
```

### Conditionally showing items

You can conditionally show things using the `showIf` method. If you pass a truthy value, the item will be displayed.

```php
ray('will be shown')->showIf(true);
ray('will not be shown')->showIf(false);
```

You can also pass a callable to `showIf`. If the callable returns a truthy value, it will be shown. Otherwise, it will
not.

### Conditionally sending items to Ray

If for any reason you do not want to send payloads to Ray _unless_ a condition is met, use the `if()` method.

You can call `if()` in two ways: only with a conditional, or with a conditional and a callback.  A conditional can be either a truthy
value or a callable that returns a truthy value.


Note that when `if()` is called with only a conditional, **all** following chained methods will only execute if the conditional 
is true.  When using a callback with `if()`, all additional chained methods will be called.

```php
foreach(range(1, 100) as $number) {
    ray()->if($number < 10)->text("value is less than ten: $number")->blue();
    
    ray()->if(function() use ($number) {
        return $number == 25;
    })->text("value is twenty-five!")->green();
    
    // display "value: #" for every item, and display 
    // even numbered values as red
    ray()->text("value: $number")
        ->if($number % 2 === 0)
        ->red();
}
```

You can even chain multiple `if()` calls without callbacks:

```php
foreach(range(1, 10) as $number) {
    // display "value: #" for every item, and display even values as red
    // and odd values as blue, except for 10 -- which is shown with large 
    // text and in green.
    ray()
        ->text("value: $number")
        ->if($number % 2 === 0)
            ->red()
        ->if($number % 2 !== 0)
            ->blue()
        ->if($number === 10)
            ->large()
            ->green();
}
```

Or chain multiple calls to `if()` with callbacks that don't affect the chained methods following them:

```php
foreach(range(1, 100) as $number) {
    // display "value: #" for all items and make each item green.
    // items less than 20 will have their text changed.
    // when the value is an even number, the item will be displayed with large text.
    ray()->text("value: $number")
        ->if($number < 10, function($ray) use ($number) {
            $ray->text("value is less than ten: $number");
        })
        ->if($number >= 10 && $number < 20, function($ray) use ($number) {
            $ray->text("value is less than 20: $number");
        })
        ->if($number % 2 === 0, function($ray) {
            $ray->large();
        })
        ->green();
}
```


### Removing items

You can remove an item that is already displayed in Ray. To do this, call the `remove` function on an instance return by
the `ray` function.

```php
$ray = ray('will be removed after 1 sec');

sleep(1);

$ray->remove();
```

You can also conditionally remove items with the `removeWhen` function (or the `removeIf` alias).

```php
ray('this one will be remove if the number is 2')->removeWhen($number === 2);
```

`removeWhen` also accepts a callable.

```php
ray('this one will be remove if the number is 2')->removeWhen(fn() => ... // return true to remove the item);
```

### Hiding items

You can display something in ray and make it hidden immediately.

```php
ray($largeObject)->hide()
```

![screenshot](/docs/ray/v1/images/hide.jpg)

### Returning items

To make all methods chainable, the `ray()` function returns an instance of `Spatie\Ray\Ray`. To quickly send something
to Ray and have that something return as a value, use the `pass` function.

```php
ray()->pass($anything) // $anything will be returned
```

This is handy when, for instance, debugging return values.

You can change

```php
function foo() {
    return 'return value',
}
```

to

```php
function foo() {
    return ray()->pass('return value'),
}
```

### Displaying a notification

You can use Ray to display a notification.

```php
ray()->notify('This is my notification');
```

![screenshot](/docs/ray/v1/images/notification.jpg)

### Halting the PHP process

You can stop the PHP process by calling `die`.

```php
ray($anything)->die();
```

Alternatively, you can use the `rd` function.

```php
rd($anything);
```

### Showing PHP info

Using `phpinfo()` you can quickly see some information about your PHP environment.
You can also pass ini options to see the value of those options.

```php
ray()->phpinfo();
ray()->phpinfo('xdebug.enabled', 'default_mimetype');
```

![screenshot](/docs/ray/v1/images/php-info.png)


### Displaying exceptions

You can display information about an Exception in Ray, including a snippet of source code showing where it was thrown.

```php
try {
  throw new \Exception('test');
} catch(\Exception $e) {
  ray()->exception($e);
}
```

### Callables and handling exceptions 

You can use Ray to handle exceptions using when passing a callable to `ray` using the `catch` function.  If no exceptions are thrown, the result of the callable is sent to the Ray app.

`catch` accepts several parameters to customize how and which exceptions are handled.  If no parameters are passed, all Exceptions are swallowed and execution continues.

```php
ray($callable)->catch();
// execution will continue. 
```

You can also pass a callable to `catch` to customize the handling of an Exception.  If you typehint the `$exception` variable, only Exceptions of that type will be handled.  PHP 8 union types are supported.

```php
ray($callable)->catch(function(MyException $exception) {
   // do something with $exception if it is of the MyException type 
});

ray($callable)->catch(function($exception) {
   // handle any exception type
});
```

The `catch` callable also accepts a second, optional parameter - `$ray` - that provides access to the current instance of the `Ray` class if you'd like more control over

If you prefer to swallow all exceptions of a given type without specifying a callback, simply pass the Exception class name or names:
```php
ray($callable)->catch(CustomExceptionOne::class);

ray($callable)->catch([
    CustomExceptionOne::class,
    CustomExceptionTwo::class,
]);
```

You can even pass multiple callables and/or classnames as an array to `catch` and they will be treated as possible handlers for any Exceptions:

```php
ray($callable)->catch([
    function(CustomExceptionOne $exception) {
       // handle CustomExceptionOne exceptions
    },
    function(CustomExceptionTwo $exception) {
       // handle CustomExceptionTwo exceptions
    },    
    \Exception::class,
]);
```

If you would like to immediately throw any unhandled exceptions from the callable after calling `ray`, chain the `throwExceptions` function onto the `ray` call.  If `throwExceptions` is not chained, it will be called when PHP finishes executing the script or application.

```php
// immediately throw unhandled exceptions
ray($callable)
    ->catch(CustomExceptionOne::class)
    ->throwExceptions();
```

After calling `catch`, you may continue to chain methods that will be called regardless of whether there was an exception handled or not.

### Showing raw values

When you sent certain values to Ray, such as Carbon instances or Eloquent models, these values will be displayed in nice way. To see all private, protected, and public properties of such values, you can use the `raw()` method.

```php
$eloquentModel = User::create(['email' => 'john@example.com']);

ray(new Carbon, $eloquentModel)); // will be formatted nicely

ray()->raw(new Carbon, $eloquentModel) // no custom formatting, all properties will be shown in Ray.
```

### Showing and hiding the app

You can show and hide the Ray app via code.

```php
ray()->showApp(); // Ray will be brought to the foreground
ray()->hideApp(); // Ray will be hidden
```

### Enabling / disabling Ray

You can enable and disable sending stuff to Ray with the `enable` and `disable` functions.

```php
ray('one'); // will be displayed in ray

ray()->disable();

ray('two'); // won't be displayed in ray

ray()->enable();

ray('three'); // will be displayed in ray
```

You can check if Ray is enabled or disabled with the `enabled` and `disabled` functions.

```php
ray()->disable();

ray()->enabled(); // false
ray()->disabled(); // true

ray()->enable();

ray()->enabled(); // true
ray()->disabled(); // false
```
