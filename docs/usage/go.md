---
title: Go
weight: 10
---

To display something in Ray use the `ray.Ray()` function. It accepts everything: strings, maps, booleans, ... you name it.

```go
ray.Ray("a string")

ray.Ray(true)

ray([]int{2,3,4})
```

`ray.Ray()` accepts multiple arguments. Each argument will be displayed in the Ray app.

```go
ray.Ray("as", "many" , "arguments", "as", "you", "like")
```

### Using colors

You can colorize things you sent to ray by using one of the color functions.

```go
ray.Ray("this is green").Green()
ray.Ray("this is orange").Orange()
ray.Ray("this is red").Red()
ray.Ray("this is blue").Blue()
ray.Ray("this is purple").Purple()
ray.Ray("this is gray").Gray()
```

![screenshot](/docs/ray/v1/images/colors.jpg)

### Using sizes

Ray can display things in different sizes.

```go
ray.Ray("small").Small()
ray.Ray("regular")
ray.Ray("large").Large()
```

![screenshot](/docs/ray/v1/images/sizes.jpg)

### Creating a new screen

You can use `NewScreen` (or `ClearScreen`) to programmatically create a new screen.

```go
ray.Ray().NewScreen("")
```

You can see values that were previously displayed, by clicking the little back button in the header of Ray.

Optionally, you can give a screen a name:

```go
ray.Ray().NewScreen("My debug screen")
```

![screenshot](/docs/ray/v1/images/screen.jpg)

You could opt to use `NewScreen` very early on in a request so you'll only see items that were sent to Ray in the
current request.

### Clearing everything including history

To clear the current screen and all previous screens, call `ClearAll`.

```go
ray.Ray().ClearAll()
```

### Pausing execution

You can pause execution of a script by using the `Pause` method.

```go
ray.Ray().Pause()
```

![screenshot](/docs/ray/v1/images/pause.jpg)

If you press the "Continue" button in Ray, execution will continue. When you press "Stop execution", Ray will throw an
exception in your app to halt execution.

If you are using Windows, you must set the maximum execution time to a high value, as the paused time will count against the maximum execution time.

### Working with Time

Ray can display information about a date in a nicely formatted table using the `Time()` method.

```go
ray.Ray().Time(time.Now())
```

You can also format the date different than the Go's default format by using `TimeWithFormat()`

```go
ray.Ray().TimeWithFormat(time.Now(), "01-02-2006 15:04:05")
```

### Displaying images

To display an image, call the `image` function and pass either a fully-qualified url as its only argument.

```go
ray.Ray().Image("https://placekitten.com/200/300")
```

### Rendering HTML

To render a piece of HTML directly in Ray, you can use the `Html` method.

```go
ray.Ray().Html("<b>Bold string<b>")
```

### Updating displayed items

You can update values that are already displayed in Ray. To do this, you must hold on the instance returned by the `ray`
function and call send on it.

Here's an example where you'll see a countdown from 10 to one.

```go
myRay := ray.Ray("counting down!")

for i := 0; i < 10; i++ {
	time.Sleep(1)
	myRay.Send(2)
}	
```

The instance `$ray` may also be used to add a color or size to items already on display. Here's an example where an
items will change color and size after a second

```go
myRay := ray.Ray("a string")

time.Sleep(1)

myRay.Red().Large()
```

### Conditionally showing items

You can conditionally show things using the `ShowIf` method. If you pass a truthy value, the item will be displayed.

```go
ray.Ray("will be show").ShowIf(true)
ray.Ray("will not be shown").ShowIf(false)
```

You can also pass a callable to `ShowIf`. If the callable returns a truthy value, it will be shown. Otherwise, it will
not.

### Removing items

You can remove an item that is already displayed in Ray. To do this, call the `Remove` function on an instance return by
the `ray` function.

```go
ray := ray.Ray("will be removed after 1 sec")

time.Sleep(time.Second)

ray.Remove()
```

You can also conditionally remove items with the `RemoveWhen` function (or the `RemoveIf` alias).

```go
ray.Ray("this one will be remove if the number is 2").RemoveWhen(num == 2);
```

`RemoveWhen` also accepts a callable that returns a boolean.

```go
ray.Ray("this one will be remove if the number is 2").RemoveWhen(func () bool { ... } // return true to remove the item);
```

### Hiding items

You can display something in ray and make it hidden immediately.

```go
ray.Ray().Hide()
```

![screenshot](/docs/ray/v1/images/hide.jpg)

### Displaying a notification

You can use Ray to display a notification.

```go
ray.Ray().Notify('This is my notification')
```

![screenshot](/docs/ray/v1/images/notification.jpg)

### Halting the Go process

You can stop the Go process by calling `die`.

```go
ray.Ray().Die()
```

### Showing and hiding the app

You can show and hide the Ray app via code.

```go
ray.Ray().ShowApp() // Ray will be brought to the foreground
ray.Ray().HideApp() // Ray will be hidden
```

### Enabling / disabling Ray

You can enable and disable sending stuff to Ray with the `Enable` and `Disable` functions.

```go
ray.Ray("one") // will be displayed in ray

ray.Ray().Disable()

ray.Ray("two") // won't be displayed in ray

ray.Ray().Enable()

ray.Ray("three") // will be displayed in ray
```

You can check if Ray is enabled or disabled with the `Enabled` and `Disabled` functions.

```GO
ray.Ray().Disable()

ray.Ray().Enabled() // false
ray.Ray().Disabled() // true

ray.Ray().Enable()

ray.Ray().Enabled() // true
ray.Ray().Disabled() // false
```
