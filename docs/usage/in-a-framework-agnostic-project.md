---
title: In a framework agnostic project
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
ray('as' 'many' , 'arguments', 'as', 'you', 'like');
```

### Using colors

You can colorize things your sent to ray by using one of the color functions. 

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
ray()->newScreen('My debug screen')
```

![screenshot](/docs/ray/v1/images/screen.jpg)

You could opt to use `newScreen` very early on in a request so you'll only see items that were sent to Ray in the current request. In a Laravel app, a good place for this might be the service provider.

When using PHPUnit to run tests, you might use `newScreen` to get a new screen each time your run a test to debug some code.

### See the caller of a function

Sometimes you want to know where your code is being called. You can quickly determine that by using the `caller` function.

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

If you press the "Continue" button in Ray, execution will continue. When you press "Stop execution" Ray will thrown an exception in your app to halt execution.

### Display the class name of an object

To quickly send the class name of an object to ray, use the `className` function.

```php
ray()->className($anObject)
```

### Measuring performance and memory usage

You can use the `measure` function to display runtime and memory usage. When `measure` is called again, the time between this previous call is also displayed.

```php
ray()->measure();

sleep(1);

ray()->measure();

sleep(2);

ray()->measure();

```

![screenshot](/docs/ray/v1/images/measure.jpg)

The `measure` call optionally accepts a callable. Ray will output the time needed to run the callable and the maximum memory used.

```php
ray()->measure(function() {
    sleep(5);
});
```

![screenshot](/docs/ray/v1/images/measure-closure.jpg)

### Updating displayed items

You can update values that are already displayed in Ray. To do this, you must hold on the instance returned by the `ray` function and call send on it.

Here's an example where you'll see a countdown from 10 to one.

```php
$ray = ray('counting down!');

foreach(range(10, 1) as $number) {
    sleep(1);
    $ray->send($number);
}
```

The instance `$ray` may also be used to add a color or size to items already on display. Here's an example where an items will change color and size after a second

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

You can also pass a callable to `showIf`. If the callable returns a truthy value, it will be shown. Otherwise, it will not.

### Removing items

You can remove an item that is already displayed in Ray. To do this, call the `remove` function on an instance return by the `ray` function.

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

To make all methods chainable, the `ray()` function returns and instance of `Spatie\Ray\Ray`. To quickly send something to Ray and have that something return as a value, use the `pass` function.

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
ray()->die();
```
