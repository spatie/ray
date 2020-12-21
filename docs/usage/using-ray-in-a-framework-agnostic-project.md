---
title: Using Ray in a framework agnostic project
weight: 2
---

To display something in Ray use the `ray()` function. It accepts everything: strings, arrays, objects, ... you name it.

```php
ray('a string')
ray(['an array'])
ray($anObject)
```

![screenshot](TODO: add screenshot)

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

### Using sizes

Ray can display things in different sizes

```php
ray('small')->small()
ray('regular');
ray('large')->large();
```

![screenshot](TODO: add screenshot)

### Creating a new screen

You can use `ray` to programmatically create a new screen.

```php
ray()->newScreen(); 
```

You can see values that were previously displayed, by clicking the little back button in the header of Ray.

Optionally, you can give a screen a name:

```php
ray()->newScreen('My debug screen')
```

![screenshot](TODO: add screenshot)

### See the caller of a function

Sometimes you want to know where your code is being called. You can quickly determine that by using the `caller` function.

```php
ray()->caller();
```

![screenshot](TODO: add screenshot)

If you want to see the entire backtrace, use the `backtrace`.

```php
ray()->backtrace();
```

![screenshot](TODO: add screenshot)

### Pausing execution

You can pause execution of a script by using the `pause` method.

```php
ray()->pause();
```

If you press the `continue` button in Ray, execution will continue.

![screenshot](TODO: add screenshot)

### Display the class name of an object

To quickly send the class name of an object to ray, use the `className` function.

```php
ray()->className($anObject)
```

![screenshot](TODO: add screenshot)

### Measuring performance and memory usage

You can use the `measure` function to display runtime and memory usage. When `measure` is called again, the time between this previous call is also displayed.

```php
ray()->measure();

sleep(1);

ray()->measure();
```

![screenshot](TODO: add screenshot)


The `measure` call optionally accepts a callable. Ray will output the time needed to run the callable and the maximum memory used.

```php
ray()->measure(function() {
    sleep(5);
})
```

![screenshot](TODO: add screenshot)

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

### Removing items

You can remove an item that is already displayed in Ray. To do this, call the `remove` function on an instance return by the `ray` function.

```php
$ray = ray('will be removed after 1 sec');

sleep(1);

$ray->remove();
```

### Collapsing items

You can display something in ray and make it collapsed immediately.

```php
ray($largeObject)->collapse()
```

![screenshot](TODO: add screenshot)

### Display a notification

You can use Ray to display a notification.

```php
ray()->notify('This is my notification')
```
