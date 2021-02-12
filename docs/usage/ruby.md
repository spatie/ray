---
title: Ruby
weight: 9
---

To display something in Ray use the `ray` function. It accepts everything: strings, hashes, objects, ... you name it.

```ruby
ray('a string')

ray(['an array'])

ray(anythingYouLike)
```

`ray` accepts multiple arguments. Each argument will be displayed in the Ray app.

```ruby
ray('as', 'many' , 'arguments', 'as', 'you', 'like');
```

### Using colors

You can colorize things you sent to ray by using one of the color functions.

```ruby
ray('this is green').green;
ray('this is orange').orange;
ray('this is red').red;
ray('this is blue').blue;
ray('this is purple').purple;
ray('this is gray').gray;
```

### Using sizes

Ray can display things in different sizes.

```ruby
ray('small').small;
ray('regular');
ray('large').large;
```

### Using sizes

Ray can display things in different sizes.

```ruby
ray('small').small;
ray('regular');
ray('large').large;
```

### Creating a new screen

You can use `newScreen` (or `clearScreen`) to programmatically create a new screen.

```ruby
ray.new_screen; 
```

You can see values that were previously displayed, by clicking the little back button in the header of Ray.

Optionally, you can give a screen a name:

```ruby
ray.newScreen('My debug screen');
```

You could opt to use `newScreen` very early on in a request, so you'll only see items that were sent to Ray in the
current request. In a Laravel app, a good place for this might be the service provider.

When running tests, you might use `newScreen` to get a new screen each time your run a test to debug some
code.

### Clearing everything including history

To clear the current screen and all previous screens, call `clearAll`.

```ruby
ray.clearAll; 
```

### Pausing execution

You can pause execution of a script by using the `pause` method.

```ruby
ray.pause;
```

If you press the "Continue" button in Ray, execution will continue. When you press "Stop execution", Ray will throw an
exception in your app to halt execution.

### See the caller of a function

Sometimes you want to know where your code is being called. You can quickly determine that by using the `caller`
function.

```ruby
ray.caller
```

If you want to see the entire backtrace, use `trace`.

```ruby
ray.trace;
```

### Display the class name of an object

To quickly send the class name of an object to ray, use the `class_name` function.

```ruby
ray.class_name(anything)
```

### Working with JSON

Want to display the JSON representation of anything you'd like in Ray? Use `to_json`..

```php
ray()->toJson(['a' => 1, 'b' => ['c' => 3]]);
```

### Updating displayed items

You can update values that are already displayed in Ray. To do this, you must hold on the instance returned by the `ray`
function and call send on it.

Here's an example where you'll see a countdown from 10 to one.

```ruby
rayInstance = ray('counting down!');

10..1.each do |digit|
    sleep(1);
    rayInstancesend(digit);
}
```

The `ray` instance may also be used to add a color or size to items already on display. Here's an example where an
items will change color and size after a second

```ruby
rayInstance = ray('a string');

sleep(1);

rayInstance.red.large
```

### Removing items

You can remove an item that is already displayed in Ray. To do this, call the `remove` function on an instance return by
the `ray` function.

```ruby
rayInstance = ray('will be removed after 1 sec');

sleep(1);

rayInstance.remove;
```

### Hiding items

You can display something in ray and make it hidden immediately.

```ruby
ray(largeObject).hide
```

### Displaying a notification

You can use Ray to display a notification.

```ruby
ray.notify('This is my notification');
```

### Showing and hiding the app

You can show and hide the Ray app via code.

```ruby
ray.showApp; // Ray will be brought to the foreground
ray.hideApp; // Ray will be hidden
```

### Returning items

To make all methods chainable, the `ray` function returns and instance of Ray. To quickly send something
to Ray and have that something return as a value, use the `pass` function.

```ruby
ray()->pass(yourVariable) // yourVariable will be returned
```
