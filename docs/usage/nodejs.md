---
title: NodeJS
weight: 7
---

The API for the NodeJS package closely mirrors the official `spatie/ray` package API, so it's likely that if it exists there, it's available to use in your NodeJS project.

### Importing the package

When working with NodeJS, import the package as you would normally:

```js 
// es module import:
import { ray } from 'node-ray';

// commonjs import:
const { ray } = require('node-ray');
```

When creating a bundle for use within a browser-based environment _(i.e. with webpack)_, import the `/web` variant:

```js 
// es module import:
import { ray } from 'node-ray/web';

// commonjs import:
const { ray } = require('node-ray/web');
```

To use `node-ray` directly in a webpage, include the standalone umd-format script via CDN. The standalone version is bundled with everything _except_ the axios library, which must be included separately and before the standalone script.

```html
    <script src="https://cdn.jsdelivr.net/npm/axios@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/node-ray@latest/dist/standalone.js"></script>
    <script>
        window.ray = Ray.ray;
        window.Ray = Ray.Ray;
    </script>
```

### Enabling and disabling

Ray can be enabled or disabled at runtime using `enable()` or `disable()`.

```js
ray('this is sent');
ray().disable();
ray('this is not sent');
ray().enable();
ray('this is also sent');
```

You can check if Ray is enabled or disabled with the `enabled` and `disabled` functions.

```js
ray().disable();

ray().enabled(); // false
ray().disabled(); // true

ray().enable();

ray().enabled(); // true
ray().disabled(); // false
```

### Working with screens

Ray can create or clear new screens of information.

```js
ray().newScreen();

ray().newScreen('my new screen');

ray().clearScreen();

ray().clearAll();
```

![screenshot](/docs/ray/v1/images/screen.jpg)

### App visibility

The Ray app can be shown or hidden programmatically.

```js
ray().showApp();

ray().hideApp();
```

### Payload types

Ray can display payloads of several types, including JSON, file contents, images, XML, and HTML.

```js
ray().json('['a' => 1, 'b' => ['c' => 3]]');

ray().toJson({name: 'my object'});

ray().file('test.txt');

ray().image('https://placekitten.com/200/300');

ray().html('<strong>hello</strong> world');

ray().xml('<one><two><three>3</three></two></one>');
```

![screenshot](/docs/ray/v1/images/json.png)

![screenshot](/docs/ray/v1/images/xml.png)

### Using colors

Ray can display the data you send with different colors.

Available colors:
- green
- orange
- red
- blue
- purple
- gray

```js
ray('this is green').green();

ray('this is purple').purple();
```
![screenshot](/docs/ray/v1/images/colors.jpg)

### Using sizes

Ray can display stuff in large, normal, or small text.

```js
ray('small').small();

ray('regular');

ray('large').large();
```

![screenshot](/docs/ray/v1/images/sizes.jpg)

### Displaying tables

Ray can display an object formatted in a table.  Complex items such as arrays and objects are pretty-printed and highlighted to make their contents pleasant to read.

```js
ray().table({
    First: 'First value',
    Second: 'Second value',
    Third: 'Third value',
});
```

![screenshot](/docs/ray/v1/images/table.png)

An array of items can also be displayed with formatted values.

```js
ray().table(['John', 'Paul', 'George', 'Ringo'], 'Beatles');
```

![screenshot](/docs/ray/v1/images/table-label.png)

### Counting

Ray can count the number of times a piece of code is called, optionally with a specific name.

```js
for(const n in [...Array(12).keys()]) {
    ray().count('first');
}

[1, 2].forEach(n => ray().count());
```
![screenshot](/docs/ray/v1/images/named-count.jpg)

### Reset counters

Counter values persist across multiple calls to `ray()`.  Reset all counters with `clearCounters()`:

```js
ray().count('first');
ray().count('first');
console.log(Ray.counters.get('first'); // displays '2'

ray().clearCounters();
console.log(Ray.counters.get('first'); // displays '0'
```

### Conditionally showing items

You can conditionally show things using the `showIf` and `showWhen` methods. If you pass a truthy value, the item will be displayed.

You may also pass a callback that returns a boolean value.

```js
ray('will be shown').showIf(() => true);

ray('will be shown').showWhen(true);

ray('will not be shown').showIf(false);

ray('will not be shown').showWhen(() => false);
```

### Removing items

You can remove an item that is already displayed in Ray. To do this, call the `remove` function on an instance return by
the `ray` function.

```js
const rayInstance = ray('will be removed after 1 sec');

sleep(1); // assuming sleep() is defined somewhere

rayInstance.remove();
```

You can also conditionally remove items with the `removeWhen` function (or the `removeIf` alias).

```js
ray('this one will be remove if the number is 2').removeWhen(number === 2);
```

`removeWhen` also accepts a callable.

```js
ray('this one will be remove if the number is 2').removeWhen(() => number === 2); // return true to remove the item
```

### See the caller of a function

Sometimes you want to know where your code is being called. You can quickly determine that by using the `caller`
function.

```js
ray().caller();
```

![screenshot](/docs/ray/v1/images/caller.jpg)

If you want to see the entire stack trace, use the `trace` function.

```js
ray().trace();
```

![screenshot](/docs/ray/v1/images/trace.jpg)

### Pausing code execution

Ray can pause code execution within async code.

```js
async function test() {
    ray('before pausing');

    await ray().pause();

    ray('after resuming');
});

test();
```

![screenshot](/docs/ray/v1/images/pause.jpg)


### Displaying a notification

You can use Ray to display a notification.

```js
ray().notify('This is my notification');
```

![screenshot](/docs/ray/v1/images/notification.jpg)


### Working with errors

Ray can display information about an `Error` or exception with the `error` method.

```js
ray().error(new Error('my error message'));
```

### Displaying class information

You can display the classname of an object with `className()`.

```js
const obj = new MyClass1();

ray().className(obj);
```

### Working with dates

Ray can display information about a date in a nicely formatted table using the `date()` method.
Specifying the format is optional. It uses the [dayjs formatting](https://day.js.org/docs/en/display/format) style.

```js
ray().date(new Date(), 'YYYY-MM-DD hh:mm:ss');
```

![screenshot](/docs/ray/v1/images/carbon.jpg)


### Measuring performance

You can use the `measure` function to display runtime and memory usage. When `measure` is called again, the time between
this and previous call is also displayed.

```js
const sleep = (seconds) => {
    const start = new Date().getTime();
    while (new Date().getTime() < start + (seconds * 1000)) { }
};

ray().measure();

sleep(1);

ray().measure();

sleep(2);

ray().measure();
```

![screenshot](/docs/ray/v1/images/measure.jpg)

The `measure` call optionally accepts a callable. Ray will output the time needed to run the callable and the maximum
memory used.

```js
const sleep = (seconds) => {
    const start = new Date().getTime();
    while (new Date().getTime() < start + (seconds * 1000)) { }
};

ray().measure(() => {
    sleep(5);
});
```

The `stopTime` method can remove a stopwatch if you've previous called `measure()` with a name:
```js

ray().measure('my timer');

sleep(1);

ray().measure('my timer');

ray().stopTime('my timer');
```

Calling `stopTime()` without specifying a name will delete all existing stopwatches.

### Rendering HTML

To render a piece of HTML directly in Ray, you can use the `html` method.

```js
ray().html('<b>Bold string<b>');
```

### Displaying text content

To display raw text while preserving whitespace formatting, use the `text` method.  If the text contains HTML, it will be displayed as-is and is not rendered.

```js
ray().text('<em>this string is html encoded</em>');
ray().text('  whitespace formatting' . "\n" . '   is preserved as well.');
```

### Showing events

You can information about an event that has executed by calling `event(name, data)`, with `data` being optional.

```js
ray().event('TestEvent', ['my argument']);
```

![screenshot](/docs/ray/v1/images/event.jpg)

### Limiting the number of sent payloads

To limit the number of payloads sent by a particular `ray()` call, use the `limit` function.  It works well for debugging loops.

```js
for(let i = 0; i < 10; i++) {
    ray().limit(3).text(`A #${i}`); // counts to 3
    ray().limit(6).text(`B #${i}`); // counts to 6
    ray().text(`C #${i}`); // counts to 10
}
```

If the argument passed to `limit()` is a negative number or zero, limiting is disabled.


### Using a rate limiter

A rate limiter can help to reduce the amount of sent messages. This would avoid spamming the desktop app, which can be helpful when using Ray in loops.

```js
Ray.rateLimiter().max(10); // only 10 messages will be sent
```

```js
Ray.rateLimiter().perSecond(10); // only 10 messages per second will be sent
```

To remove the rate limits again
```js
Ray.rateLimiter().clear();
```

A message to the desktop app will be sent once to notify the user the rate limit has been reached.


### Sending a payload once

To only send a payload once, use the `once` function.  This is useful for debugging loops.

`once()` may be called with arguments:


```js
for(let i = 0; i < 10; i++) {
    ray().once($i); // only sends "0"
}
```

You can also use `once` without arguments. Any function you chain on `once` will also only be called once.

```php
for(let i = 0; i < 10; i++) {
    ray().once().html(`<strong>${i}</strong>`); // only sends "<strong>0</strong>"
}
```

### Conditionally sending items to Ray

If for any reason you do not want to send payloads to Ray _unless_ a condition is met, use the `if()` method.

You can call `if()` in two ways: only with a conditional, or with a conditional and a callback.  A conditional can be either a truthy
value or a callable that returns a truthy value.


Note that when `if()` is called with only a conditional, **all** following chained methods will only execute if the conditional 
is true.  When using a callback with `if()`, all additional chained methods will be called.

```js
for(let i = 0; i < 100; i++) {
    ray().if(i < 10).text(`value is less than ten: ${i}`).blue();
    
    ray().if(() => i === 25).text("value is twenty-five!").green();
    
    // display "value: #" for every item, and display 
    // even numbered values as red
    ray().text(`value: ${i}`)
        .if(i % 2 === 0)
        .red();
}
```

You can even chain multiple `if()` calls without callbacks:

```js
for(let i = 0; i < 100; i++) {
    // display "value: #" for every item, and display even values as red
    // and odd values as blue, except for 10 -- which is shown with large 
    // text and in green.
    ray()
        .text(`value: ${i}`)
        .if($i % 2 === 0)
            .red()
        .if($i % 2 !== 0)
            .blue()
        .if($i === 10)
            .large()
            .green();
}
```

Or chain multiple calls to `if()` with callbacks that don't affect the chained methods following them:

```js
for(let i = 0; i < 100; i++) {
    // display "value: #" for all items and make each item green.
    // items less than 20 will have their text changed.
    // when the value is an even number, the item will be displayed with large text.
    ray().text(`value: ${i}`)
        .if(i < 10, ($ray) => {
            $ray.text(`value is less than ten: ${i}`);
        })
        .if(i >= 10 && i < 20, ($ray) => {
            $ray->text(`value is less than 20: ${i}`);
        })
        .if(i % 2 === 0, ($ray) => {
            $ray->large();
        })
        .green();
}
```

### Feature demo

Here's a sample script that demonstrates a number of the features, both basic and advanced.

Save as `demo.js`, and run with `node demo.js`:

```js
const { ray } = require('node-ray');

function test1() { 
    return ray().count('test one');
}

function test2() {
    return ray().count();
}

async function alternatingColorCounter() {
    return new Promise(resolve => {
        const myRay = ray();

        for(const i in [...Array(10).keys()]) {
            setTimeout( () => {
                const colorName = ['green', 'red', 'blue', 'orange'][i % 4];

                myRay.html(`counter: <strong>${i + 1}</strong>`).color(colorName);

                if (i === 9) {
                    resolve(true);
                }
            }, i * 1500);
        }
    });
}

async function main() {
    ray().showApp();

    ray().xml('<one><two><three>3333</three></two></one>');

    ray().table(['a string', true, [1, 2, 3], {a:1, b:2}]);

    ray().sendCustom({ name: 'object' });

    await alternatingColorCounter();

    await ray().pause();

    [1,2].forEach(n => test1());

    [1,2,3,4].forEach(n => test2());

    ray().image('https://placekitten.com/200/300').blue();

    ray().html('<strong>hello world</strong>').small();
}

main();
```
