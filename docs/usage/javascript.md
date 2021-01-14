---
title: JavaScript
weight: 6
---

To display something in Ray use the `ray()` function. It accepts everything: strings, arrays, objects, ... you name it.

```js
ray('a string')

ray(['an array'])

ray({ text: 'an object' })
```

`ray` accepts multiple arguments. Each argument will be displayed in the Ray app.

```js
ray('as' 'many' , 'arguments', 'as', 'you', 'like')
```

### Using colors

You can colorize things your sent to ray by using the color function. 

```js
ray('this is green').color('green')
ray('this is orange').color('orange')
ray('this is red').color('red')
ray('this is blue').color('blue')
ray('this is purple').color('purple')
ray('this is gray').color('gray')
```

### Using sizes

Ray can display things in different sizes.

```js
ray('small').size('small')
ray('regular')
ray('large').size('large')
```

### Creating a new screen

You can use `newScreen` (or `clearScreen`) to programmatically create a new screen.

```js
ray().newScreen() 
```

You can see values that were previously displayed, by clicking the little back button in the header of Ray.

Optionally, you can give a screen a name:

```js
ray().newScreen('My debug screen')
```


You could opt to use `newScreen` very early on in a request so you'll only see items that were sent to Ray in the current request. In a Laravel app, a good place for this might be the service provider.

### Working with JSON

Want to display the json representation of anything you'd like in Ray? You can parse it into an object, then send it to Ray.

It will be displayed nicely and collapsable in Ray.

```php
const jsonString = JSON.parse(['a' => 1, 'b' => ['c' => 3]]);

ray(jsonString);
```

### Updating displayed items

You can update values that are already displayed in Ray. To do this, you must hold on the instance returned by the `ray` function and call send on it.

Here's an example where you'll see a countdown from 10 to one.

```js
const ray = ray('counting down!')

foreach(number in range(10, 1)) {
    setTimeout(() => ray.send(number), 1000)
}
```

The instance `ray` may also be used to add a color or size to items already on display. Here's an example where an items will change color and size after a second

```js
const ray = ray('a string');

setTimeout(() => ray.color('red').size('large'), 1000)
```

### Conditionally showing items

You can conditionally show things using the `showIf` method. If you pass a truthy value, the item will be displayed.

```js
ray('will be show').showIf(true);
ray('will not be shown').showIf(false);
```

You can also pass a callable to `showIf`. If the callable returns a truthy value, it will be shown. Otherwise, it will not.

### Removing items

You can remove an item that is already displayed in Ray. To do this, call the `remove` function on an instance return by the `ray` function.

```js
const ray = ray('will be removed after 1 sec')

setTimeout(() => ray.remove(), 1000)
```

You can also conditionally remove items with the `removeWhen` function (or the `removeIf` alias).

```js
ray('this one will be remove if the number is 2').removeWhen(number === 2)
```

`removeWhen` also accepts a callable.

```js
ray('this one will be remove if the number is 2').removeWhen(() => ... // return true to remove the item);
```

### Hiding items

You can display something in ray and make it hidden immediately.

```js
ray(largeObject).hide()
```

### Returning items

To make all methods chainable, the `ray()` function returns and instance of `Spatie\Ray\Ray`. To quickly send something to Ray and have that something return as a value, use the `pass` function.

```js
ray().pass(anything) // the value of "anything" will be returned
```

This is handy when, for instance, debugging return values.

You can change

```js
function foo() {
    return 'return value'
}
```

to 

```js
function foo() {
    return ray().pass('return value'),
}
```

### Displaying a notification

You can use Ray to display a notification.

```js
ray().notify('This is my notification');
```

![screenshot](/docs/ray/v1/images/notification.jpg)

> Note: The JS ray client does not support getting the caller of a function, pausing execution, performance and memory measurements, or halting the JS process. There's also no current support for showing the origin of the js request, althrough we hope to add that in soon.
