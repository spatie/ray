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

This is how that looks like in Ray.

![screenshot](/docs/ray/v1/images/named-count.png)

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

To display an image, call the `image` function and pass either a fully-qualified filename or url as its only argument.

```php
ray()->image('https://placekitten.com/200/300');
ray()->image('/home/user/kitten.jpg');
```

### Rendering HTML

To render a piece of HTML directly in Ray, you can use the `html` method.

```php
ray()->html('<b>Bold string<b>');
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
ray('will be show')->showIf(true);
ray('will not be shown')->showIf(false);
```

You can also pass a callable to `showIf`. If the callable returns a truthy value, it will be shown. Otherwise, it will
not.

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

To make all methods chainable, the `ray()` function returns and instance of `Spatie\Ray\Ray`. To quickly send something
to Ray and have that something return as a value, use the `pass` function.

```php
ray()->pass($anything) // $anything will be returned
```

This is handy when, for instance, debuggin return values.

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
